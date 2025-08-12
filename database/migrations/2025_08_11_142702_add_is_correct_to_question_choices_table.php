<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCorrectToQuestionChoicesTable extends Migration
{
    public function up()
    {
        Schema::table('question_choices', function (Blueprint $table) {
            $table->boolean('is_correct')->default(false)->after('value');
        });
    }

    public function down()
    {
        Schema::table('question_choices', function (Blueprint $table) {
            $table->dropColumn('is_correct');
        });
    }
}
