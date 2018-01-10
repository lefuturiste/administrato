<?php

namespace App\TestBundle;

use App\Bundle;
use App\TestBundle\Controllers\TestController;
use DI\ContainerBuilder;
use Dotenv\Loader;
class TestBundle extends Bundle
{
	protected $viewPath = '';
	protected $envPath = '';
	protected $dashboardName = 'test';

	public function routes()
	{
		$this->dashboardRoutes(function(){
			$this->get('[/]', [TestController::class, 'getTest']);
		});
	}

	public function getTwigPath()
	{
		return dirname(__DIR__) . '/TestBundle/views';
	}

	public function configureContainer(ContainerBuilder $builder)
	{
		return $builder;
	}

	public function loadEnv()
	{
		return (new Loader($this->getEnvPath()))->load();
	}

	public function getEnvPath()
	{
		return dirname(__DIR__) . '/TestBundle/.env';
	}
}