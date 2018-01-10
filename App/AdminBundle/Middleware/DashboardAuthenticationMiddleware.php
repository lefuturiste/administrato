<?php

namespace App\AdminBundle\Middleware;

use App\AdminBundle\User;
use DI\Container;
use Illuminate\Database\Capsule\Manager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SlimSession\Helper;

class DashboardAuthenticationMiddleware
{

	private $domainName;
	/**
	 * @var Manager
	 */
	private $manager;
	/**
	 * @var Helper
	 */
	private $session;
	private $container;

	public function __construct($domainName, Container $container)
	{
		$this->domainName = $domainName;
		$this->container = $container;
		$this->session = $container->get(Helper::class);
		$this->manager = $container->get(Manager::class);
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{
		if ($this->session->get('user')['level'] == 'admin') {
			return $next($request, $response);
		} else {
			$dashboards = User::find($this->session->get('user')['uuid'])->dashboards()->get()->toArray();
			$found = false;
			foreach ($dashboards as $dashboard) {
				if ($dashboard['name'] == $this->domainName) {
					$found = true;
					break;
				}
			}
			if ($found) {
				return $next($request, $response);
			} else {
				return $response->write('Forbidden: you are not allowed here')->withStatus(403);
			}
		}
	}
}