<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processed_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 64); // Assuming a 64 character string for the order number
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company')->nullable();
            $table->string('address');
            $table->string('apartetc')->nullable();
            $table->string('city');
            $table->string('country');
            $table->string('state');
            $table->string('postal');
            $table->string('phone')->nullable();
            $table->string('card_number');
            $table->string('card_name');
            $table->string('card_expiration');
            $table->string('card_cvc');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2);
            $table->decimal('taxes', 10, 2);
            $table->jsonb('items'); // Using JSONB for the items
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processed_orders');
    }
};
