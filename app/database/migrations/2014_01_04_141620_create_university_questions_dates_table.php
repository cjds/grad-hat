<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUniversityQuestionsDatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('university_questions_dates', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('post_id');
			$table->string('question_number');
			$table->string('month_year');
			$table->timestamps();
		
			$table->index('post_id');
			$table->foreign('post_id')->references('post_id')->on('university_questions');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('university_questions_dates');
	}

}