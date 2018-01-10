<?php

namespace App\AdminBundle\Controllers;
use App\AdminBundle\Dashboard;
use App\AdminBundle\User;
use App\Controllers\Controller;
use DI\Container;
use Illuminate\Database\Capsule\Manager;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SlimSession\Helper;
use STAILEUAccounts\STAILEUAccounts;

class LoginController extends Controller {
	public function getLogin(ServerRequestInterface $request, ResponseInterface $response, Container $container, STAILEUAccounts $stail)
	{
		return $this->redirect(
			$response,
			(
				$stail->loginForm($container->get('staileu')['url'])
			)
		);
	}

	public function getLoginExecute(ServerRequestInterface $request, ResponseInterface $response, STAILEUAccounts $stail, Helper $session, Manager $manager, Logger $logger)
	{
		$cSa = $request->getQueryParams()['c-sa'];
		$uuid = $stail->check($cSa);
		if (is_string($uuid)){

			$logger->info("Success login in with {$uuid} in panel");

			//create session
			//register
			$user = User::find($uuid);
			if ($user == NULL){
				return $response->write('Forbidden: you are not allowed here')->withStatus(403);
			}

			if ($user['level'] == 'admin')
			{
				$dashboards = Dashboard::all()->toArray();
			}else{
				$dashboards = $user->dashboards()->toArray();
			}

			$session->set('user', [
				'uuid' => $uuid,
				'username' => $stail->getUsername($uuid),
				'email' => $stail->getEmail($uuid),
				'avatar' => $stail->getAvatar($uuid)->getUrl(),
				'level' => $user['level'],
				'created_at' => $stail->getRegistrationDate($uuid),
				'dashboards' => $dashboards
			]);
			return $this->redirect($response, $this->pathFor('dashboard'));
		}else{
			$logger->error("Error login in panel : staileu api response '{$uuid->getMessage()} -- {$uuid->getCode()}'");

			return $response->withStatus(400)->write('Error with stail.eu api');
		}
	}

	public function getLogout(ServerRequestInterface $request, ResponseInterface $response, Helper $session)
	{
		$session->clear();
		return $this->redirect($response, $this->pathFor('login'));
	}
}