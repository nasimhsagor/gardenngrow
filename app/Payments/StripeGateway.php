<?php

declare(strict_types=1);

namespace App\Payments;

use App\Contracts\PaymentGateway;
use App\Events\PaymentReceived;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeGateway implements PaymentGateway
{
    public function __construct()
    {
        Stripe::setApiKey(config('payment.stripe.secret_key'));
    }

    public function initiate(Order $order): array
    {
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'bdt',
                    'unit_amount' => (int) ($order->total * 100),
                    'product_data' => ['name' => "Order #{$order->order_number}"],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}&order=' . $order->order_number,
            'cancel_url' => route('checkout.index'),
            'metadata' => ['order_number' => $order->order_number],
        ]);

        Payment::create([
            'order_id' => $order->id,
            'transaction_id' => $session->id,
            'payment_method' => 'stripe',
            'amount' => $order->total,
            'currency' => 'BDT',
            'status' => 'pending',
        ]);

        return ['redirect' => $session->url];
    }

    public function verify(Request $request): Payment
    {
        $session = Session::retrieve($request->get('session_id'));
        $order = Order::where('order_number', $request->get('order'))->firstOrFail();
        $payment = $order->payment;

        if ($session->payment_status === 'paid') {
            $payment->update(['status' => 'completed', 'paid_at' => now()]);
            $order->update(['payment_status' => 'paid', 'status' => 'confirmed']);
            event(new PaymentReceived($payment));
        }

        return $payment;
    }

    public function refund(Payment $payment): bool
    {
        try {
            \Stripe\Refund::create(['payment_intent' => $payment->gateway_response['payment_intent'] ?? '']);
            $payment->update(['status' => 'refunded']);
            return true;
        } catch (\Exception) {
            return false;
        }
    }
}
