<?php

use App\Enums\PlanStatus;
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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('plan'); // basic, pro, advance/enterprise
            $table->timestamp('trial_starts_at')->nullable(); // When trial begins
            $table->timestamp('trial_ends_at')->nullable();   // When trial will end
            $table->timestamp('starts_at')->nullable();;
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(false); // is_active determines whether the user can access premium content
            $table->string('status')->default(PlanStatus::INACTIVE->value)->index(); // status tracks where in the subscription lifecycle the user is
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
