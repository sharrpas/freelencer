<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToProjectsTable extends Migration
{

    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained('users');
        });
    }


    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
