<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameBlogPostTagTableToTaggables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_post_tag', function (Blueprint $table) {
            $table->dropForeign(['blog_post_id']);
            // we must drop foreign key before change table's name because the foreign key's full name based on
            // the table name 
            // here, foreign key's full name is blog_post_tag_blog_post_id 
            $table->dropColumn('blog_post_id');
        });
        Schema::rename('blog_post_tag', 'taggables');
        Schema::table('taggables', function (Blueprint $table) {
            $table->morphs('taggable');// morphs also base on table name
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taggables', function (Blueprint $table) {
            $table->dropMorphs('taggable');
        });// morphs base on table name so we must dropMorphs before changing table name
        Schema::rename('taggables', 'blog_post_tag');
        Schema::disableForeignKeyConstraints();
        // sometimes when rollback, we have some invalid datas, so we must disable constraints
        Schema::table('blog_post_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('blog_post_id')->index();
            $table->foreign('blog_post_id')->references('id')
                ->on('blog_posts')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }
}
