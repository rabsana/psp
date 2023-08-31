<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRabsanaInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config("rabsana-psp.database.invoices.table", 'invoices'), function (Blueprint $table) {
            $table->id();

            $table->foreignId('merchant_id')->constrained()->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreignId('currency_id')->nullable()->constrained()->onDelete('RESTRICT')->onUpdate('RESTRICT');

            $table->string('token')->unique();
            $table->string('status')->default('CREATED');

            $table->double('qty', 40, 20)->nullable();
            $table->double('amount', 40, 20);

            $table->string('base')->nullable();
            $table->string('quote')->nullable();

            $table->string('callback_url')->nullable();
            $table->string('inquiry_url')->nullable();

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
        Schema::dropIfExists(config("rabsana-psp.database.invoices.table", 'invoices'));
    }
}
