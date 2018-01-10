<?php
namespace App\AdminBundle\Middleware;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Router;
use SlimSession\Helper;

class MustBeAuthenticatedMiddleware{

	/**
	 * @var Helper
	 */
	private $helper;
	/**
	 * @var Container
	 */
	private $container;

	public function __construct($container)
	{
		$this->container = $container;
		$this->router = $container->get(Router::class);
		$this->helper = $container->get(Helper::class);
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{
		if ($this->helper->exists('user')){
			return $next($request, $response);
		}else{
			return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('login'));
		}
	}
}