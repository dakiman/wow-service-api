<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
	protected $fillable = ['name', 'realm'];
	protected $hidden = ['user_id', 'id'];

	public function user()
	{
		return $this->hasOne('App\User');
	}
}
