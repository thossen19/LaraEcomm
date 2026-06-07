<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Payment;
use App\Models\Order;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'gateway' => 'required|in:stripe,paypal,manual,cod',
            'payment_method_id' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::findOrFail($request->order_id);
        
        if ($order->payment_status === 'paid') {
            return response()->json(['message' => 'Order already paid'], 422);
        }

        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Process payment based on gateway
        $result = $this->processGatewayPayment($request->gateway, $order, $request);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null
            ], 422);
        }

        // Create payment record
        $payment = Payment::create([
            'order_id' => $order->id,
            'transaction_id' => $result['transaction_id'] ?? null,
            'gateway' => $request->gateway,
            'amount' => $request->amount,
            'currency' => 'USD',
            'status' => 'paid',
            'gateway_response' => $result['response'] ?? null,
            'paid_at' => now(),
        ]);

        // Update order status
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);

        return response()->json([
            'message' => 'Payment processed successfully',
            'payment' => $payment->load('order')
        ]);
    }

    public function getPaymentMethods()
    {
        $methods = [
            [
                'id' => 'stripe',
                'name' => 'Credit/Debit Card',
                'description' => 'Pay with Visa, Mastercard, or other cards',
                'icon' => 'credit-card',
                'enabled' => true,
            ],
            [
                'id' => 'paypal',
                'name' => 'PayPal',
                'description' => 'Pay with your PayPal account',
                'icon' => 'paypal',
                'enabled' => true,
            ],
            [
                'id' => 'cod',
                'name' => 'Cash on Delivery',
                'description' => 'Pay when you receive your order',
                'icon' => 'cash',
                'enabled' => true,
            ],
            [
                'id' => 'manual',
                'name' => 'Bank Transfer',
                'description' => 'Transfer directly to our bank account',
                'icon' => 'bank',
                'enabled' => true,
            ],
        ];

        return response()->json($methods);
    }

    public function getPaymentHistory(Request $request)
    {
        $user = $request->user();
        
        $payments = Payment::with(['order'])
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 10));

        return response()->json($payments);
    }

    public function refundPayment(Request $request, $id)
    {
        $user = $request->user();
        
        $payment = Payment::with(['order'])
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->findOrFail($id);

        if ($payment->status !== 'paid') {
            return response()->json(['message' => 'Payment cannot be refunded'], 422);
        }

        if ($payment->created_at->diffInDays(now()) > 30) {
            return response()->json(['message' => 'Refund period expired'], 422);
        }

        // Process refund based on gateway
        $result = $this->processGatewayRefund($payment);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null
            ], 422);
        }

        $payment->update([
            'status' => 'refunded',
            'gateway_response' => array_merge(
                $payment->gateway_response ?? [],
                ['refund' => $result['response'] ?? null]
            ),
        ]);

        // Update order status
        $payment->order->update([
            'payment_status' => 'refunded',
            'status' => 'cancelled',
        ]);

        return response()->json([
            'message' => 'Refund processed successfully',
            'payment' => $payment
        ]);
    }

    private function processGatewayPayment($gateway, $order, $request)
    {
        switch ($gateway) {
            case 'stripe':
                return $this->processStripePayment($order, $request);
            case 'paypal':
                return $this->processPayPalPayment($order, $request);
            case 'cod':
                return $this->processCashOnDelivery($order, $request);
            case 'manual':
                return $this->processManualPayment($order, $request);
            default:
                return ['success' => false, 'message' => 'Invalid payment gateway'];
        }
    }

    private function processStripePayment($order, $request)
    {
        // Stripe implementation would go here
        // For now, simulate successful payment
        return [
            'success' => true,
            'transaction_id' => 'stripe_' . uniqid(),
            'response' => ['status' => 'succeeded'],
        ];
    }

    private function processPayPalPayment($order, $request)
    {
        // PayPal implementation would go here
        // For now, simulate successful payment
        return [
            'success' => true,
            'transaction_id' => 'paypal_' . uniqid(),
            'response' => ['status' => 'completed'],
        ];
    }

    private function processCashOnDelivery($order, $request)
    {
        return [
            'success' => true,
            'transaction_id' => 'cod_' . uniqid(),
            'response' => ['status' => 'pending_delivery'],
        ];
    }

    private function processManualPayment($order, $request)
    {
        return [
            'success' => true,
            'transaction_id' => 'manual_' . uniqid(),
            'response' => ['status' => 'pending_verification'],
        ];
    }

    private function processGatewayRefund($payment)
    {
        switch ($payment->gateway) {
            case 'stripe':
                return $this->processStripeRefund($payment);
            case 'paypal':
                return $this->processPayPalRefund($payment);
            case 'cod':
                return ['success' => true, 'message' => 'COD refund processed'];
            case 'manual':
                return ['success' => true, 'message' => 'Manual refund processed'];
            default:
                return ['success' => false, 'message' => 'Refund not supported for this gateway'];
        }
    }

    private function processStripeRefund($payment)
    {
        // Stripe refund implementation would go here
        return [
            'success' => true,
            'response' => ['refund_id' => 'refund_' . uniqid()],
        ];
    }

    private function processPayPalRefund($payment)
    {
        // PayPal refund implementation would go here
        return [
            'success' => true,
            'response' => ['refund_id' => 'paypal_refund_' . uniqid()],
        ];
    }
}
