<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // If old column change_amount exists, make it nullable
            if (Schema::hasColumn('orders', 'change_amount')) {
                $table->decimal('change_amount', 10, 2)->nullable()->change();
            }

            // Ensure paid_amount exists
            if (!Schema::hasColumn('orders', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->default(0)->after('payment_method');
            }

            // Ensure change_return exists
            if (!Schema::hasColumn('orders', 'change_return')) {
                $table->decimal('change_return', 10, 2)->default(0)->after('paid_amount');
            }

            // Ensure customer_id exists
            if (!Schema::hasColumn('orders', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        //
    }
};