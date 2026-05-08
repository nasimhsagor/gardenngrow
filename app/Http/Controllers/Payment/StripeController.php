<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StripeController extends Controller
{
    public function success(Request $request): RedirectResponse
    {
        $order = Order::where('order_number', $request->query('order'))->firstOrFail();

        return redirect()->route('checkout.success', $order->order_number)
            ->with('success', 'Payment successful!');
    }

    public function cancel(Request $request): RedirectResponse
    {
        return redirect()->route('checkout.index')->with('warning', 'Payment was cancelled.');
    }

    public function webhook(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('payment.gateways.stripe.webhook_secret');

        if (!$secret) {
            return response('Webhook secret not configured', 400);
        }

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $payment = Payment::where('transaction_id', $session->id)->first();

            if ($payment) {
                $payment->update([
                    'status' => PaymentStatus::Paid,
                    'paid_at' => now(),
                ]);
                $payment->order->update(['payment_status' => PaymentStatus::Paid]);
            }
        }

        return response('OK', 200);
    }
}
