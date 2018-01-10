<?php
namespace App\AdminBundle\Middleware;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;
use SlimSession\Helper;

class MustBeAdminMiddleware{
	/**
	 * @var Helper
	 */
	private $session;
	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
		$this->session = $container->get(Helper::class);
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{
		if ($this->session->get('user')['level'] == 'admin'){
			return $next($request, $response);
		}else{
			return $response->write('Forbidden: you are not allowed here')->withStatus(403);
		}
	}
}