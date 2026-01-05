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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('trip_number')->unique();
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('driver_id');
            $table->string('a_code')->nullable();
            $table->string('destination_from');
            $table->string('destination_to');
            $table->string('status')->default('not_started');
            $table->decimal('mileage', 10, 2)->nullable();
            $table->text('cmr')->nullable();
            $table->text('driver_description')->nullable();
            $table->text('admin_description')->nullable();
            $table->date('trip_date');
            $table->string('invoice_number')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('trip_number');
            $table->index('driver_id');
            $table->index('vehicle_id');
            $table->index('trip_date');
            $table->index('status');


            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('restrict');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
