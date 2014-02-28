@extends('layouts.master')

@section('content')

<style>
	.sem:hover, .branch:hover{
		cursor: pointer;
		color: #0079a1;
	}

	td{
		vertical-align: top;
	}
</style>

<script type='text/javascript'>
	$(document).ready(function(){
		$("[name = 'modules']").click(function(){
			var modules = $(this).data('subject');
			var content = "<table>";
			$.each(modules, function(){
				var module_id = (this).module_id;
				content += "<tr><td><a href={{url('univquestions/view')}}?mid=" + module_id +">";
				content += (this).module_name;
				content += "</a></td></tr>";
			});
			content += "</table>";
			$(this).html(content);
		});

		$("[name = 'papers']").click(function(){
			var content = "<table>";
			var year = (new Date).getFullYear();
			var month = (new Date).getMonth()+1;
			var subject_id = $(this).data('subjectid');
				if (month >= 6) {
					content += "<tr><td><a href={{url('univquestions/view/paper', 'May" + year + "')}}?sid=" + subject_id + ">";
					content += "May/June" + year
					content += "</td>";
				}
				if (month == 12) {
					content += "<td><a href={{url('univquestions/view/paper', 'December" + year + "')}}?sid=" + subject_id + ">";
					content += "December " + year;
					content += "</td>";
				}
				content += "</tr>";
			for (var i = year-1; i >= 2010; i--) {
				content += "<tr><td>";
				content += "<tr><td><a href={{url('univquestions/view/paper', 'May" + i + "')}}?sid=" + subject_id + ">";
				content += "May/June " + i;
				content += "</td><td><a href={{url('univquestions/view/paper', 'December" + i + "')}}?sid=" + subject_id + ">";
				content += "December " + i;
				content += "</td></tr>";
				
			};
			content += "</table>";
			$(this).html(content);
		});

		$(".branch").click(function(){
			$(this).next().find(".sem").toggle(250);
		})
		$(".sem").click(function(){
			$(this).next().fadeToggle("slow");
		});
	});
</script>

<div class="row">
	<div class="large-8 large-offset-2 small-12 columns box-sides box-top box-bottom">
		@foreach ($branches as $branch)
			<h3 class="branch">{{$branch->branch_name}}</h3>
			<ul class="no-bullet">
			@for ($i=3; $i <= 8; $i++)
				<li>
				<div class="sem" style="display:none">Semester {{$i}}</div>
				<table class="toShow " style="display:none">
					<tr>
						<td>
							@foreach ($branch->subjects as $subject)
							@if($subject->subject_sem == $i)
							<td>{{HTML::link('univquestions/view?sid='.$subject->subject_id, $subject->subject_name);}}</td>
							<td name="modules" data-subject='{{$subject->modules}}'><button>Modules</button></td>
							<td name="papers" data-subjectid='{{$subject->subject_id}}'><button>Papers</button></td>
							@endif
							@endforeach
						</td>
					</tr>
				</table> 
				</li>
			@endfor
			</ul>
		@endforeach
	</div>
</div>

@stop