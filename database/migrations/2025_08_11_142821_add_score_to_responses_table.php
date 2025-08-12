<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScoreToResponsesTable extends Migration
{
    public function up()
    {
        Schema::table('responses', function (Blueprint $table) {
            $table->decimal('score', 8, 2)->default(0)->after('submitted_at');
        });
    }

    public function down()
    {
        Schema::table('responses', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }
}
