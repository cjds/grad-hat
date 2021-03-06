@extends('layouts.master')

@section('content')

{{HTML::style('css/markdown.css');}}
<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
    jax: ["input/TeX","output/HTML-CSS"],
    tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]], displayMath: [ ["$$","$$"] ],mathsize: "90%",
    processEscapes: true},
    "HTML-CSS":{linebreaks:{automatic:true}},
     TeX: { noUndefined: { attributes: 
{ mathcolor: "red", mathbackground: "#FFEEEE", mathsize: "90%" } } }, 

  });
</script>


{{HTML::script('http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML');}}


<script type="text/javascript">
	$(document).ready(function(){
		var post_id=-1;
		var post_type='';
		var data_type='';
		jsonReview();

		function jsonReview(){
			$.getJSON( "{{url('json/moderator/nextflag')}}", function( json ) {
				console.log(json);
				if(json.status=='success'){
					$('#explanation').html(json.edit_explanation);
					post_id=json.post_id;
					var html='';
					if(json.type=='question'){
						post_type=json.type;
						html+='Question<br>';
						html+='<h3>'+json.title+'</h3>';
						html+=json.body+'<br><br>';
						html+="<h3>Flag Reasons</h3>";
	  					for(var i=0;i<json.flags.length;i++){
	  						html+=json.flags[i].flag_reason+"<br>";
	  					}
	  				}
	  				else{
	  					html+='Answer<br>';
						html+='<h3>'+json.question_title+'</h3>';
						html+=json.question_body+'<br><br>';
						html+='<h3>	Answer Text:</h3> '+json.body+'<br><br>';
						html+="<h3>Flag Reasons</h3>";
	  					for(var i=0;i<json.flags.length;i++){
	  						html+=json.flags[i].flag_reason+"<br>";
	  					}	
	  				}
	  				$('#flags').html(html);
	  			}
	  			else if(json.status=='fail'){
	  				post_id=-1;
	  				if(json.type=='no_flag_left'){
	  					$('#flags').html('<h2>No More Flags Left</h2>');
	  				}
	  			}
	  			
	 		});
		}
		
		$('input.review-btn').click(function(e){
			e.preventDefault();
			data_type=$(this).attr('data-type');
			$.ajax ({ 
				type:"POST",
				url:"{{url('json/moderator/nextflag')}}",
				dataType:'json',
				data: {status:$(this).attr('data-status') ,type:$(this).attr('data-type'), post_id : post_id}
			})
			.done(function( json ) {
				if(data_type=='edit'){
					window.location.href = '{{url('/')}}'+'/edit/'+post_type+'?qid='+post_id;	
				}
				else{
    				jsonReview();
				}
  			});

		});
	});
  
</script>

<div class="row">
	<div class="large-11 small-12 columns large-offset-1">
	<div id='flags'></div>
	<br>
	<div class="span8">
		<h4>Approve and</h4>
		<input type='button' class='button review-btn' data-status='approve' data-type='nothing' value='Do Nothing'/>
		<input type='button' class='button review-btn' data-status='approve' data-type='edit' value='Edit Post'/>
		<input type='button' class='button review-btn' data-status='approve' data-type='close' value='Close Post'/>
		<input type='button' class='button review-btn' data-status='approve' data-type='delete' value='Delete Post'/>
		<input type='button' class='button review-btn' data-status='approve' data-type='block' value='Block User'/>
		<br>
		<h4>Reject and</h4>
		<input type='button' class='button review-btn' data-status='reject' data-type='nothing' value='Do Nothing'/>
		<input type='button' class='button review-btn' data-status='reject' data-type='edit' value='Edit Post'/>
	</div>
</div>
@stop