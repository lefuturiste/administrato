<?php

namespace App\AdminBundle\Controllers;
use App\AdminBundle\Dashboard;
use App\AdminBundle\User;
use App\Controllers\Controller;
use Illuminate\Database\Capsule\Manager;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use SlimSession\Helper;

class DashboardController extends Controller {
	public function getDashboard(ResponseInterface $response){
		$this->render($response, 'dashboard');
	}
}