<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function isCorrect()
    {
    	return $this->correct == 1;
    }
}
