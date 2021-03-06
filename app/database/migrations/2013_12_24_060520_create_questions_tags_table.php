<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionsTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('question_tag', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger ('question_id');
			$table->unsignedInteger('tag_id');
			$table->timestamps();

			$table->index('question_id');
			$table->foreign('question_id')->references('post_id')->on('questions');
			$table->index('tag_id');
			$table->foreign('tag_id')->references('tags_id')->on('tags');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('question_tag');
	}

}
