<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Stripe Checkout Session ID
            $table->string('stripe_session_id')->nullable()->after('status');
            
            // Stripe Payment Intent ID
            $table->string('stripe_payment_intent_id')->nullable()->after('stripe_session_id');
            
            // Stripe Customer ID (for future recurring purchases)
            $table->string('stripe_customer_id')->nullable()->after('stripe_payment_intent_id');
            
            // Payment status: unpaid, paid, failed, refunded
            $table->string('payment_status')->default('unpaid')->after('stripe_customer_id');
            
            // Payment method type (card, paypal, etc.)
            $table->string('payment_method')->nullable()->after('payment_status');
            
            // Card brand if paid by card (visa, mastercard, etc.)
            $table->string('card_brand')->nullable()->after('payment_method');
            
            // Last 4 digits of card
            $table->string('card_last_four')->nullable()->after('card_brand');
            
            // Currency (usd, eur, etc.)
            $table->string('currency')->default('usd')->after('card_last_four');
            
            // Stripe charge/refund IDs for record keeping
            $table->string('stripe_charge_id')->nullable()->after('currency');
            
            // When the payment was completed
            $table->timestamp('paid_at')->nullable()->after('stripe_charge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_session_id',
                'stripe_payment_intent_id',
                'stripe_customer_id',
                'payment_status',
                'payment_method',
                'card_brand',
                'card_last_four',
                'currency',
                'stripe_charge_id',
                'paid_at',
            ]);
        });
    }
};
