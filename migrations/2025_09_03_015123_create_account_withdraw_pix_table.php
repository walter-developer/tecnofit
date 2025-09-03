<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_withdraw_pix', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('account_withdraw_id');
            $table->string('type');
            $table->string('key');
            $table->datetimes();
            $table->datetime('deleted_at')->nullable();
            $table->foreign('account_withdraw_id')
                ->references('id')->on('account_withdraw');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_withdrwaw_pix');
    }
};
