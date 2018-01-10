<?php
namespace App\AdminBundle;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
	protected $table = 'users';

	protected $keyType = 'string';

	public $incrementing = false;

	public $timestamps = true;

	public function dashboards(){
		return $this->belongsToMany('App\AdminBundle\Dashboard', 'dashboard_user');
	}
}