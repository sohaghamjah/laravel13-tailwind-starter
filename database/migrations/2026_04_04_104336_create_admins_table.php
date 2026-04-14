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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('user_name',100)->index();
            $table->string('email',191)->index();
            $table->string('password',255);
            $table->string('image',191)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('mobile_code',10)->nullable();
            $table->string('phone',50)->nullable()->index();
            $table->text('address',500)->nullable();
            $table->string('device_id',255)->nullable();
            $table->text('device_info',500)->nullable();
            $table->timestamp("last_logged_in")->nullable();
            $table->timestamp("last_logged_out")->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('delatable')->default(true);
            $table->boolean("login_status")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
