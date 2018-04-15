<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
	use SoftDeletes;

	protected $fillable = ['user_id', 'title', 'slug', 'content', 'image'];

	protected $dates = ['deleted_at'];

	public function user()
	{
		return $this->belongsTo('App\user', 'user_id');
	}

	 // public function comment()
  //   {
  //       return $this->hasMany('App\Comment');
  //   }

}
