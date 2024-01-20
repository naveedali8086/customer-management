<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            // customer_code is unique. It can be used as per the use case i.e. for claiming loyalty.
            $table->string('customer_code')->unique()->nullable();
            // It determines if a customer can be sent marketing or promotion email,SMS,WhatsApp,...
            $table->unsignedTinyInteger('enable_notification');
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 5); // M,F,Other
            $table->string('gender_other')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
