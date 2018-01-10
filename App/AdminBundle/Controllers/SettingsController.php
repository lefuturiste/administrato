<?php

namespace App\AdminBundle\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SlimSession\Helper;

class SettingsController extends Controller {
	public function getSettings(ServerRequestInterface $request, ResponseInterface $response, Helper $helper){
		return $this->render($response, "settings.home");
	}
}