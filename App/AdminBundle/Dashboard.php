<?php
namespace App\AdminBundle;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model {
	protected $table = 'dashboards';

	protected $keyType = 'string';

	public $incrementing = false;

	public $timestamps = false;

	public function users()
	{
		return $this->belongsToMany('App\AdminBundle\User', 'dashboard_user');
	}
}