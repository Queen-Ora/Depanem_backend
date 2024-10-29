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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('technician_id');
            $table->boolean('isChecked')->default(false);
            
            // Champs pour latitude et longitude
            // $table->decimal('latitude', 10, 7)->nullable();
            // $table->decimal('longitude', 10, 7)->nullable();
            
            // Champs pour le statut de la demande
            // $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            
            // Définition des clés étrangères avec suppression en cascade
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('technician_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
