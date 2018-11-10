<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Quizes</title>

	<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
	<script src="{{ asset('js/app.js') }}"></script>
</head>
<body>


<div class="container">

	<main>
		<h1 class="text-center mt-4">A simple quiz management application</h1>
		<h3 class="text-center">Create your quizes with your own answers / questions.</h3>
		<h5 class="text-center">The creation page contains some cool clean - coded Javascript functionality.</h5>
		<p class="text-center">
			Don't forget that that is a demo application. Many quizes will be <strong>deleted</strong> to avoid spamming and keeping my portfolio clean.
		</p>

		<a href="{{ route('create-quiz') }}" class="btn btn-success">Create new quiz</a>

		<div class="card mt-4">
		  <div class="card-header">
		    All the quizes ( {{ count($quizes) }} )
		  </div>
		  <ul class="list-group list-group-flush">

		  	@foreach($quizes as $quiz)

			    <li class="list-group-item"> 
			    	<a href=" {{ route('start-quiz' , $quiz->slug) }} "><h4>{{ $quiz->title }}</h4></a>  <span class="text-muted"> created {{ $quiz->created_at->diffForHumans() }}</span>
			    	<p>Times completed: {{ $quiz->times_done }}</p>
			    	<a href=" {{ route('start-quiz' , $quiz->slug) }} " class="btn btn-warning">Start Quiz</a>
			    </li>
		    
		    @endforeach
		  
		  </ul>
		</div>
	</main>
</div>


<footer class="footer mt-5">
      <div class="container">
        <span class="text-muted">George Lioympas 2018 - <a href="#">Github Code</a></span>
      </div>
 </footer>



</body>
</html>