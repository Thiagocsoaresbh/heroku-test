<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add index for 'account_id'
            $table->index('account_id');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remove index for 'account_id'php
            $table->dropIndex(['account_id']);
        });
    }
}
