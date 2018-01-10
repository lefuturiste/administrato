<?php

namespace App\AdminBundle;

use App\AdminBundle\Controllers\Settings\DashboardSettingsController;
use App\AdminBundle\Controllers\Settings\UserDashboardSettingsController;
use App\AdminBundle\Controllers\Settings\UserSettingsController;
use App\AdminBundle\Controllers\SettingsController;
use App\AdminBundle\Middleware\MustBeAdminMiddleware;
use App\AdminBundle\Middleware\MustBeAuthenticatedMiddleware;
use App\Bundle;
use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Loader;
use SlimSession\Helper;

class AdminBundle extends Bundle
{
	protected $viewPath = '../App/AdminBundle/views';
	protected $envPath = '';

	public function routes()
	{
		$container = $this->app->getContainer();
		$this->app->get('/', [Controllers\HomeController::class, 'getHome'])->setName('home');
		$this->app->get('/login', [Controllers\LoginController::class, 'getLogin'])->setName('login');
		$this->app->get('/login/execute', [Controllers\LoginController::class, 'getLoginExecute'])->setName('login.execute');
		$this->app->get('/dashboard', [Controllers\DashboardController::class, 'getDashboard'])->setName('dashboard')
			->add(new MustBeAuthenticatedMiddleware($container));
		$this->app->group('/settings', function (){
			$this->get('[/]', [SettingsController::class, 'getSettings']);
			$this->group('/users', function (){
				$this->get('[/]', [UserSettingsController::class, 'getUsers'])->setName('settings.users');
				$this->get('/create', [UserSettingsController::class, 'createUser'])->setName('settings.users.create');
				$this->get('/{uuid}', [UserSettingsController::class, 'getUser'])->setName('settings.users.view');
				$this->post('[/]', [UserSettingsController::class, 'storeUser'])->setName('settings.users.store');
				$this->get('/{uuid}/delete', [UserSettingsController::class, 'deleteUser'])->setName('settings.users.delete');
				$this->get('/{uuid}/destroy', [UserSettingsController::class, 'destroyUser'])->setName('settings.users.destroy');
				$this->group('/{uuid}/dashboards', function (){
					$this->get('/create', [UserDashboardSettingsController::class, 'createUserDashboard'])->setName('settings.users.dashboards.create');
					$this->post('[/]', [UserDashboardSettingsController::class, 'storeUserDashboard'])->setName('settings.users.dashboards.store');
					$this->get('/{dashboard_id}/destroy', [UserDashboardSettingsController::class, 'destroyUserDashboard'])->setName('settings.users.dashboards.destroy');
				});
			});
			$this->group('/dashboards', function (){
				$this->get('[/]', [DashboardSettingsController::class, 'getDashboards'])->setName('settings.dashboards');
				$this->get('/create', [DashboardSettingsController::class, 'createDashboards'])->setName('settings.dashboards.create');
				$this->get('/{uuid}', [DashboardSettingsController::class, 'getDashboard'])->setName('settings.dashboards.view');
				$this->post('[/]', [DashboardSettingsController::class, 'storeDashboard'])->setName('settings.dashboards.store');
				$this->get('/{uuid}/delete', [DashboardSettingsController::class, 'deleteDashboard'])->setName('settings.dashboards.delete');
				$this->get('/{uuid}/destroy', [DashboardSettingsController::class, 'destroyDashboard'])->setName('settings.dashboards.destroy');
			});
		})->add(new MustBeAuthenticatedMiddleware($container))
		  ->add(new MustBeAdminMiddleware($container));
		$this->app->get('/logout', [Controllers\LoginController::class, 'getLogout'])->setName('logout');
	}

	public function getTwigPath()
	{
		return $this->viewPath;
	}

	public function configureContainer(ContainerBuilder $builder)
	{
		$builder->addDefinitions(__DIR__ . '/config/containers.php');
		$builder->addDefinitions(__DIR__ . '/config/staileu.php');
		return $builder;
	}

	public function loadEnv()
	{
		return (new Loader($this->getEnvPath()))->load();
	}

	public function getEnvPath()
	{
		return dirname(__DIR__) . '/AdminBundle/.env';
	}
}