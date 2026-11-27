<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sign-in auditing reads OPTV2LOGS by log type, account and time window.
 * Without this index each refused attempt scans the whole table, so a flood
 * costs a table scan per request. Additive only: no column or row changes.
 */
class AddSigninIndexToOptv2Logs extends Migration
{
    private const INDEX = 'OPTV2LOGS_SIGNIN_INDEX';

    public function up()
    {
        Schema::table('OPTV2LOGS', function (Blueprint $table) {
            $table->index(['LOGTYPE', 'REFERENCE', 'DOCDATE'], self::INDEX);
        });
    }

    public function down()
    {
        Schema::table('OPTV2LOGS', function (Blueprint $table) {
            $table->dropIndex(self::INDEX);
        });
    }
}
