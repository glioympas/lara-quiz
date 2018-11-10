<?php

namespace App\Http\Controllers;

use App\Question;
use App\Answer;
use App\Quiz;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
    	$quizes = Quiz::orderBy('created_at' , 'desc')->get();
    	return view('home', compact('quizes'));
    }

    public function startQuiz($slug)
    {
    	$quiz = Quiz::whereSlug($slug)->first();

    	if(!$quiz)
    		return abort(404);

    	return view('quiz' , compact('quiz'));

    }

    public function startQuizPost(Request $request , $slug)
    {
    	$quiz = Quiz::whereSlug($slug)->first();

    	if(!$quiz)
    		return abort(404);

    	$questionsSummary = count($quiz->questions);
    	$correctAnswers = 0;
    	foreach($request->results as $result)
    	{
    		$question = Question::findOrFail($result['questionId']);
    		$answer = Answer::findOrFail($result['answerId']);

    		if($answer->correct == 1)
    			$correctAnswers++;
    	}

    	$score = ($correctAnswers / $questionsSummary) * 100;
    	$quiz->times_done++;
    	$quiz->save();
    	$score = number_format((float)$score, 2, '.', ''); //2 digits

    	
    	return response()->json(['correctAnswers' => $correctAnswers , 'score' => $score]);

    }

    public function create()
    {
    	return view('create');
    }


    public function createPost(Request $request)
    {
    	//some custom validation
    	$existedQuiz = Quiz::whereTitle($request->quizTitle)->first();
    	if($existedQuiz){
    		return response()->json(['quiz_exists'=>1]);
    	}

    	$quiz = Quiz::create(['title'=>$request->quizTitle, 'slug'=> str_slug($request->quizTitle,'-')]);

    	foreach($request->quizQuestions as $q)
    	{
    		$question = new Question();
    		$question->title = $q['title'];
    		$question->quiz_id = $quiz->id;
    		$question->save();

    		$correct = $q['questionCorrectAnswer'];

    		$num = 1;
    		foreach($q['answers'] as $ans)
    		{
    			$answer = new Answer();
    			$answer->title = $ans;
    			$answer->question_id = $question->id;

    			if($correct == $num)
    				$answer->correct = 1;
    			$num++;

    			$answer->save();
    		}
    	}

    	return response()->json(["created"=>1]);

    }
}
