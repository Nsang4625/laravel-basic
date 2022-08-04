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
            if(env('DB_CONNECTION') == 'sqlite_testing'){
                $table->unsignedBigInteger('user_id')
                ->default(0);
            } else{
                $table->unsignedBigInteger('user_id')->nullable();
            }

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
            // we must drop the key before drop the column
            // can't drop a column that has a key
        });
    }
}
