<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserToBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            // add column to blog_posts table
            $table->unsignedBigInteger('user_id')
            ->nullable();
            $table->foreign('user_id')->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            //
            $table->dropForeign(['user_id']);// use [] to tell Laravel delete the fk was created on this field
            // remember that name column and name forein key are different
            $table->dropColumn('user_id');// drop column user_id
            // drop the key before drop the column
        });
    }
}
