<?php
namespace App\TestBundle\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TestController extends Controller {
	public function getTest(ServerRequestInterface $request, ResponseInterface $response)
	{
		di('hell');
	}
}