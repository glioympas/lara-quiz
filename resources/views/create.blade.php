<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Create new quiz</title>

	<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="https://rawgit.com/leizongmin/js-xss/master/dist/xss.js"></script>
</head>
<body>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add a new question</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="question-errors"></div>
        <input type="text" placeholder="Question Title" id="newquestion-title" class="form-control" name="">
        <p>Add your answers</p>
        <input type="text" class="form-control" placeholder="Answer 1" id="newquestion-1"  name="">
        <input type="text" class="form-control" placeholder="Answer 2" id="newquestion-2"  name="">
        <input type="text" class="form-control" placeholder="Answer 3" id="newquestion-3"  name="">

        <p class="mt-2">
        	Correct Answer:
        	1 <input type="radio" name="correct-answer" class="correct-answer" checked selected value="1">
        	2 <input type="radio" name="correct-answer" class="correct-answer" value="2">
        	3 <input type="radio" name="correct-answer" class="correct-answer" value="3">
        </p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="submit-question" class="btn btn-primary">Submit Question</button>
      </div>
    </div>
  </div>
</div>


<div class="container">



	<main>
		<h1 class="text-center mt-4">Create a new quiz</h1>
		<p class="text-center">
			Don't forget that that is a demo application. Many quizes will be deleted to avoid spamming and keeping my portfolio clean.
		</p>

		<a href="{{ route('home') }}" class="btn btn-warning">Back to main</a>

		<div class="card mt-4">
		  <div class="card-header">
		    Create your quiz
		  </div>
		  <div class="p-4">
		  	<div class="errors">
		  		
		  	</div>
		  	Quiz Title:
		  	<input type="text" id="quiz-title" placeholder="Quiz Title" class="form-control">

		  	<div class="questions mt-4">
		  		

		  		

		  	</div>

		  	<div class="mt-4">
		  		<a href="#" data-toggle="modal" data-target="#exampleModal">( + ) Add new question</a>
		  	</div>

		  	<button class="btn btn-success submit-quiz mt-4">Create Quiz</button>	


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

	function validateEmpty(errors , elem, elemName)
	{
		if(elem == '' || elem == undefined)
			errors.push(elemName + " can't be empty.");
	}

	function validateSize(errors , elem , elemName , min , max)
	{
		if(elem.length < min)
			errors.push(elemName + "'s length must be more than " + min + " charaters.");

		if(elem.length > max)
			errors.push(elemName + "'s length must be less than " + max + " characters.");
	}

	function validateQuestions(errors , questions , min)
	{
		if(questions.length < min)
			errors.push('You must put at least ' + min + " questions in your quiz");
	}

	$(document).ready(function(){

		$('.submit-quiz').on('click' , function(){
			$(this).prop('disabled',true);
			$('.errors').html('');
			var errors = [];

			//validate quiz title not to be empty and not to big
			var quizTitle = filterXSS($('#quiz-title').val());
			validateEmpty(errors , quizTitle , 'Quiz Title');
			validateSize(errors , quizTitle , 'Quiz Title' , 5 , 40);

			//validate questions so has at least 2 for example
			var questions = $('.question');
			validateQuestions(errors , questions, 2);

			var quizQuestions = [];

			questions.each(function(){
				var question = $(this);
				var questionTitle = question.children()[0].innerText;
				var questionCorrectAnswer = question.attr('correctAnswer');

				var questionAnswers = [];

				//0 is the title
				//last is the hr element :D
				//so we need from 1 to n-1
				for(var i=1; i< question.children().length - 1; i++)
					questionAnswers.push(question.children()[i].innerText.substring(3));

				var finalQuestion = {'title' : questionTitle , 'answers' : questionAnswers, 'questionCorrectAnswer':questionCorrectAnswer};

				quizQuestions.push(finalQuestion);
			});

			if(errors.length > 0)
			{
				errors.forEach(function(err){
					$('.errors').append(`<div class="alert alert-danger"> ${err} </div>`)
				})
				$('.submit-quiz').prop('disabled',false);
				return;
				
			}

			$.ajax({
				type:"POST",
				url: "{{ route('create-quiz-post') }}",
				data: { "quizTitle":quizTitle, "quizQuestions":quizQuestions, "_token":"{{ csrf_token() }}" },
				success: function(response)
				{
					if(response.quiz_exists)
					{
						$('.errors').append(`<div class="alert alert-danger"> Quiz with that name already exists. </div>`)
						$('.submit-quiz').prop('disabled',false);
						return;
					}

					$('.errors').append(`<div class="alert alert-success"> Your quiz created successfully! </div>`)

					if(response.created)
						window.location.href = "{{route('home')}}";
				},
				error: function(error)
				{
					//console.log(error.responseYext);
					alert('Something gone bad?');
					$('.submit-quiz').prop('disabled',false);
				}
			});



		});

		$('#submit-question').on('click' , function(){
			$('#question-errors').html('');
			var errors = [];
			var questionTitle = filterXSS($('#newquestion-title').val());
			var answer1 = filterXSS($('#newquestion-1').val());
			var answer2 = filterXSS($('#newquestion-2').val());
			var answer3 = filterXSS($('#newquestion-3').val());

			var correctAnswer = 1;
			$('.correct-answer').each(function(){
				var correct = $(this);
				if(correct.is(':checked'))
					correctAnswer = correct.val();
			});

			validateEmpty(errors , questionTitle, 'Question Title');
			validateEmpty(errors , answer1, 'Answer 1');
			validateEmpty(errors , answer2, 'Answer 2');
			validateEmpty(errors , answer3, 'Answer 3');
			validateSize(errors , questionTitle , "Question Title" , 6 , 50);

			if(errors.length > 0)
			{
				errors.forEach(function(err){
					$('#question-errors').append(`<div class="alert alert-danger"> ${err} </div>`)
				})
				return;
			}

			$('.questions').append(`
				<div class="question mt-2" correctAnswer="${correctAnswer}">

					<h3>${questionTitle}</h3>
					<div class="answer">1) ${answer1}</div>
					<div class="answer">2) ${answer2}</div>
					<div class="answer">3) ${answer3}</div>
					<hr>

				</div>
			`);

			$('#newquestion-title').val('');
			$('#newquestion-1').val('');
			$('#newquestion-2').val('');
			$('#newquestion-3').val('');

			$('#exampleModal').modal('toggle');



		})


	});

</script>


</body>
</html>