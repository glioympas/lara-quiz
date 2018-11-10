<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Quiz - {{ $quiz->title }}</title>

	<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
	<script src="{{ asset('js/app.js') }}"></script>
</head>
<body>


<div class="container">

	<main>
		<h1 class="text-center mt-4">Quiz: {{ $quiz->title }}</h1>
		<h3>Good Luck! Thanks for your interest about my portfolio.</h3>

		<a href="{{ route('home') }}" class="btn btn-warning">Back to main</a>

		<div class="card mt-4">
		  <div class="card-header">
		     Please answer all the  {{ count($quiz->questions) }} questions.
		  </div>
		 
		  <div class="questions p-3 mt-3">
			  @foreach($quiz->questions as $question)

			  	<div class="question-body">
			  		<h3>{{ $question->title }}</h3>
			  		<?php $counter=1; ?>
			  		@foreach($question->answers as $answer)

			  			

			  				<div>
			  					{{$counter}}) {{$answer->title}}
			  					<input type="radio" {{ $counter == 1 ? 'checked' : '' }} value="{{ $answer->id }}" answerId="{{$answer->id}}" answerQuestion="{{$question->id}}" class="answer" name="{{ $question->title }}">
			  				</div>

			  			<?php $counter++; ?>
			  		@endforeach
			  		<hr>
			  	</div>


			  @endforeach

			  <button class="btn btn-success" id="complete-quiz">Complete</button>
		  </div>

		</div>
	</main>
</div>


<footer class="footer mt-5">
      <div class="container">
        <span class="text-muted">George Lioympas 2018 - <a href="#">Github Code</a></span>
      </div>
 </footer>

<script>
	
	$(document).ready(function(){


		$('#complete-quiz').on('click' , function(){

			var quizSlug = "{{$quiz->slug}}";

			var results = []; // { questionId: answerId}
		
			$('.answer').each(function(){
				var answer = $(this);

				var questionId = answer.attr('answerQuestion');
				var answerId = -1;
				
				if(answer.is(':checked')){
					answerId = answer.attr('answerId');
					var result = {'questionId' : questionId, 'answerId':answerId};
					results.push(result);
					
				}

				

			});



			//send results to server

			$.ajax({
				type:"POST",
				url: "{{ route('start-quiz-post', $quiz->slug) }}",
				data:{ "_token":"{{ csrf_token() }}" , "results" : results },
				success:function(response)
				{
					// console.log(response);

					$('.questions').html(`
						<h4>You completed the quiz! Congratulations.</h4>
						<div class="alert alert-warning">Correct Answers: ${response.correctAnswers} </div>
						<div class="alert alert-success">Score : ${response.score} %</div>

						<div>
							<a class="btn btn-warning" href="{{ route('start-quiz' , $quiz->slug) }}">Try again</a>
						</div>
					`);

				}


			});

		});


	});

</script>

</body>
</html>