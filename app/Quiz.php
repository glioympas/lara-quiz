<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{

	protected $fillable = [
		'title','slug'
	];

    public function questions()
    {
    	return $this->hasMany('App\Question');
    }

    public function setTitleAttribute($value)
    {
    	$this->attributes['title'] = strtoupper($value);
    }

}
