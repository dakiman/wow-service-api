<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
	protected $fillable = ['name', 'realm', 'class', 'thumbnail', 'battlegroup', 'faction', 'gender', 'race', 'level', 'totalHonorableKills', 'achievementPoints'];
	protected $hidden = ['user_id'];

	public function user()
	{
		return $this->hasOne('App\User');
	}
}
