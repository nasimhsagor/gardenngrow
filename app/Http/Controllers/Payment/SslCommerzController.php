<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SslCommerzController extends Controller
{
    public function success(Request $request): RedirectResponse
    {
        $payment = Payment::where('transaction_id', $request->val_id)->first();

        if (!$payment) {
            return redirect()->route('home')->with('error', 'Payment not found.');
        }

        if ($request->status === 'VALID' || $request->status === 'VALIDATED') {
            $payment->update([
                'status' => PaymentStatus::Paid,
                'gateway_response' => $request->all(),
                'paid_at' => now(),
            ]);
            $payment->order->update(['payment_status' => PaymentStatus::Paid]);
        }

        return redirect()->route('checkout.success', $payment->order->order_number)
            ->with('success', 'Payment successful!');
    }

    public function fail(Request $request): RedirectResponse
    {
        $payment = Payment::where('transaction_id', $request->val_id)->first();

        if ($payment) {
            $payment->update([
                'status' => PaymentStatus::Failed,
                'gateway_response' => $request->all(),
            ]);
        }

        return redirect()->route('checkout.index')->with('error', 'Payment failed. Please try again.');
    }

    public function cancel(Request $request): RedirectResponse
    {
        return redirect()->route('checkout.index')->with('warning', 'Payment was cancelled.');
    }

    public function ipn(Request $request): void
    {
        $payment = Payment::where('transaction_id', $request->val_id)->first();

        if ($payment && ($request->status === 'VALID' || $request->status === 'VALIDATED')) {
            $payment->update([
                'status' => PaymentStatus::Paid,
                'gateway_response' => $request->all(),
                'paid_at' => now(),
            ]);
            $payment->order->update(['payment_status' => PaymentStatus::Paid]);
        }
    }
}
