<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRabsanaCurrencyMerchantPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config("rabsana-psp.database.currency_merchant.table", 'currency_merchant'), function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained()->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('merchant_id')->constrained()->onDelete('CASCADE')->onUpdate('CASCADE');
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
        Schema::dropIfExists(config("rabsana-psp.database.currency_merchant.table", 'currency_merchant'));
    }
}
