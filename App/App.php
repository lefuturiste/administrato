<?php

namespace App;

//use Config\Config;
use DI\ContainerBuilder;

class App extends \Slim\App
{
	protected $bundles = [];
	protected $hasBundles = true;
	protected $twigPaths = ['../App/views'];

	public function __construct()
	{
		//on initialise le container
		$containerBuilder = new ContainerBuilder;
		$containerBuilder->addDefinitions(__DIR__ . '/config/config.php');

		if ($this->hasBundles) {
			//pour chaque bundle, on l'initialise
			foreach ($this->getBundles() as $bundle) {
				$bundle = new $bundle($this);
				array_push($this->bundles, $bundle);
				//on configure les container pour ce bundle
				$bundle->configureContainer($containerBuilder);
				//on ajoute le path twig à ceux existant
				$this->twigPaths = $bundle->getTwigPaths($this->twigPaths, $containerBuilder);
				$bundle->loadEnv();
			}
		}

		//on configure les container de l'app principale
		$this->configureContainer($containerBuilder);
		//on met les twig path dans les container
		$this->configureTwigPathArray($containerBuilder);
		$container = $containerBuilder->build();

		parent::__construct($container);

		if ($this->hasBundles) {
			// on enregistre les routes des bundles
			$this->registerBundlesRoutes();
		}
	}

	protected function configureTwigPathArray(ContainerBuilder $containerBuilder)
	{
		$containerBuilder->addDefinitions([
			'twig_paths' => $this->twigPaths
		]);
	}

	protected function registerBundlesRoutes()
	{
		foreach ($this->bundles as $bundle) {
			$bundle->routes();
		}
	}

	protected function configureContainer(ContainerBuilder $builder)
	{
		$builder->addDefinitions(__DIR__ . '/config/app.php');
		$builder->addDefinitions(__DIR__ . '/config/api.php');
		$builder->addDefinitions(__DIR__ . '/config/database.php');
		$builder->addDefinitions(__DIR__ . '/config/containers.php');
	}

	public function getBundles()
	{
		return [
			\App\AdminBundle\AdminBundle::class,
			\App\TestBundle\TestBundle::class,
		];
	}
}
