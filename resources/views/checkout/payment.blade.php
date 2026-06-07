@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div style="max-width: 600px; margin: 0 auto;">
        <div class="content-card">
            <h2 style="margin-bottom: 20px; color: #2c3e50;">Complete Payment</h2>
            
            <!-- Order Summary -->
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                <h3 style="margin-bottom: 15px; color: #2c3e50;">Order Summary</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <p style="margin: 0 0 5px 0; color: #7f8c8d; font-size: 14px;">Order Number:</p>
                        <p style="margin: 0; font-weight: 600; color: #2c3e50;">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p style="margin: 0 0 5px 0; color: #7f8c8d; font-size: 14px;">Total Amount:</p>
                        <p style="margin: 0; font-weight: 600; color: #e74c3c; font-size: 18px;">${{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Options -->
            <div style="margin-bottom: 30px;">
                <h3 style="margin-bottom: 20px; color: #2c3e50;">Select Payment Method</h3>
                
                <!-- Stripe Payment -->
                <div style="margin-bottom: 20px;">
                    <form action="{{ route('checkout.payment.process', $order->id) }}" method="POST" id="stripe-form">
                        @csrf
                        <input type="hidden" name="payment_method" value="stripe">
                        
                        <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
                            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                                <img src="https://img.icons8.com/color/48/000000/stripe.png" alt="Stripe" style="width: 48px; height: 48px; margin-right: 15px;">
                                <div>
                                    <h4 style="margin: 0; color: #2c3e50;">Pay with Credit Card</h4>
                                    <p style="margin: 0; color: #7f8c8d; font-size: 14px;">Secure payment via Stripe</p>
                                </div>
                            </div>
                            
                            <!-- Stripe Elements Placeholder -->
                            <div id="stripe-card-element" style="padding: 15px; border: 1px solid #ddd; border-radius: 6px; background: white; margin-bottom: 15px;">
                                <!-- Stripe Elements will be mounted here -->
                                <div style="color: #7f8c8d; text-align: center;">Loading payment form...</div>
                            </div>
                            
                            <div id="stripe-card-errors" role="alert" style="color: #e74c3c; margin-bottom: 15px; font-size: 14px;"></div>
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%;" id="stripe-submit">
                                <i class="fas fa-lock"></i> Pay ${{ number_format($order->total_amount, 2) }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PayPal Payment -->
                <div style="margin-bottom: 20px;">
                    <form action="{{ route('checkout.payment.process', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" value="paypal">
                        
                        <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
                            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                                <img src="https://img.icons8.com/color/48/000000/paypal.png" alt="PayPal" style="width: 48px; height: 48px; margin-right: 15px;">
                                <div>
                                    <h4 style="margin: 0; color: #2c3e50;">Pay with PayPal</h4>
                                    <p style="margin: 0; color: #7f8c8d; font-size: 14px;">Fast, secure payment</p>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%; background: #ffc439 !important; color: #2c3e50 !important;">
                                <i class="fab fa-paypal"></i> Pay with PayPal
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Cash on Delivery Option -->
                <div>
                    <form action="{{ route('checkout.payment.process', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" value="cod">
                        
                        <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
                            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                                <div style="width: 48px; height: 48px; background: #27ae60; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                    <i class="fas fa-money-bill-wave" style="color: white; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <h4 style="margin: 0; color: #2c3e50;">Cash on Delivery</h4>
                                    <p style="margin: 0; color: #7f8c8d; font-size: 14px;">Pay when you receive your order</p>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-outline-success" style="width: 100%; border-color: #27ae60 !important; color: #27ae60 !important;">
                                <i class="fas fa-truck"></i> Confirm Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Info -->
            <div style="text-align: center; margin-top: 30px;">
                <p style="color: #7f8c8d; margin-bottom: 15px;">
                    <i class="fas fa-info-circle"></i> Your order will be processed once payment is complete
                </p>
                <a href="{{ route('cart.show') }}" style="color: #3498db; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Back to Cart
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.content-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.btn {
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: none;
    gap: 8px;
}

.btn-primary {
    background: #3498db !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
}

.btn-primary:hover {
    background: #2980b9 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
}

.btn-outline-success {
    background: transparent !important;
    color: #27ae60 !important;
    border: 2px solid #27ae60 !important;
}

.btn-outline-success:hover {
    background: #27ae60 !important;
    color: white !important;
    transform: translateY(-2px);
}
</style>

<!-- Stripe.js (would be loaded in production) -->
<script>
// This is a placeholder for Stripe integration
// In production, you would load Stripe.js and implement the payment flow
document.addEventListener('DOMContentLoaded', function() {
    // Simulate Stripe loading
    setTimeout(() => {
        const cardElement = document.getElementById('stripe-card-element');
        if (cardElement) {
            cardElement.innerHTML = '<div style="color: #7f8c8d; text-align: center;">Stripe payment form would be loaded here</div>';
        }
    }, 1000);

    // Handle form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // Simulate payment processing
            setTimeout(() => {
                // In production, this would handle actual payment processing
                form.submit();
            }, 2000);
        });
    });
});
</script>
@endsection
