<?php

Route::get('/', [
'as'=>'home',
'uses'=>'HomeController@home'
]);

Route::get('/create', [
'as'=>'create-quiz',
'uses' => 'HomeController@create'
]);


Route::post('/create', [
'as' => 'create-quiz-post',
'uses' => 'HomeController@createPost'
]);


Route::get('/quiz/{slug}' , [
'as' => 'start-quiz',
'uses' => 'HomeController@startQuiz'
]);

Route::post('/quiz/{slug}', [

'as' => 'start-quiz-post',
'uses' => 'HomeController@startQuizPost'

]);
