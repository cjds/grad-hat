<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSuggestedEditsAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('suggested_edits_answers', function(Blueprint $table) {
			$table->increments('suggested_edits_id');
			$table->string('suggested_edits_answer_body');
			$table->timestamps();
			
			$table->foreign('suggested_edits_id')->references('suggested_edits_id')->on('suggested_edits');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('suggested_edits_answers');
	}

}