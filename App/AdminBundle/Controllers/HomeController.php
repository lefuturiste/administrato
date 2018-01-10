<?php
namespace App\AdminBundle\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use SlimSession\Helper;

class HomeController extends Controller {
	public function getHome(ResponseInterface $response, Helper $session){
		//redirect to login or dashboard
		if($session->exists('user')){
			//redir to dash
			return $this->redirect($response, $this->pathFor('dashboard'));
		}else{
			return $this->redirect($response, $this->pathFor('login'));
			//redir to login
		}
	}


}