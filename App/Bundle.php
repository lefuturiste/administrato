<?php

namespace App;

use App\AdminBundle\Middleware\DashboardAuthenticationMiddleware;
use App\AdminBundle\Middleware\MustBeAuthenticatedMiddleware;
use Illuminate\Database\Capsule\Manager;
use SlimSession\Helper;

class Bundle
{
	protected $app;
	protected $viewPath;
	protected $dashboardName;

	public function __construct(App $app)
	{
		$this->app = $app;
	}

	public function dashboardRoutes($callable)
	{
		$container = $this->app->getContainer();
		$this->app->group('/' . $this->dashboardName, $callable)
			->add(new MustBeAuthenticatedMiddleware($container))
			->add(new DashboardAuthenticationMiddleware($this->dashboardName, $container));
	}

	public function crudRoutes($name, $controller)
	{
		$baseName = $this->dashboardName;
		$this->app->group('/' . $name, function () use ($name, $controller, $baseName) {
			$this->get("[/]", [$controller, 'index'])->setName("{$baseName}.{$name}.index");
			$this->get("/create", [$controller, 'create'])->setName("{$baseName}.{$name}.create");
			$this->post("[/]", [$controller, 'store'])->setName("{$baseName}.{$name}.store");
			$this->get("/{id}", [$controller, 'view'])->setName("{$baseName}.{$name}.view");
			$this->get("/{id}/edit", [$controller, 'edit'])->setName("{$baseName}.{$name}.edit");
			$this->post("/{id}", [$controller, 'update'])->setName("{$baseName}.{$name}.update");
			$this->get("/{id}/delete", [$controller, 'delete'])->setName("{$baseName}.{$name}.delete");
			$this->get("/{id}/destroy", [$controller, 'destroy'])->setName("{$baseName}.{$name}.destroy");
		});
	}

	public function getTwigPaths($twigPath)
	{
		return array_merge(
			$twigPath,
			[$this->getTwigPath()]
		);
	}

	public function getTwigPath()
	{
		return $this->viewPath;
	}
}