<?php

namespace App\AdminBundle\Controllers\Settings;

use App\AdminBundle\Dashboard;
use App\AdminBundle\User;
use App\Controllers\Controller;
use Illuminate\Database\Capsule\Manager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Flash\Messages;
use SlimSession\Helper;
use STAILEUAccounts\STAILEUAccounts;
use Validator\Validator;

class UserSettingsController extends Controller
{
	public function getUsers(ServerRequestInterface $request, ResponseInterface $response, Manager $manager, Helper $helper)
	{
		return $this->render($response, 'settings.users.users', [
			'users' => User::all()->toArray()
		]);
	}

	public function getUser($uuid, ServerRequestInterface $request, ResponseInterface $response)
	{
		$user = User::find($uuid)->with('dashboards')->first();
		if ($user) {
			return $this->render($response, 'settings.users.user', [
				'_user' => $user->toArray()
			]);
		} else {
			return $response->withStatus(404);
		}
	}

	public function deleteUser($uuid, ServerRequestInterface $request, ResponseInterface $response)
	{
		$user = User::find($uuid);
		if ($user) {
			return $this->render($response, 'settings.users.delete', [
				'_user' => $user
			]);
		} else {
			return $response->withStatus(404);
		}
	}

	public function destroyUser($uuid, ServerRequestInterface $request, ResponseInterface $response, Messages $flash)
	{
		$user = User::destroy($uuid);
		if ($user > 0) {
			$flash->addMessage('success', 'Success destroy user!');

			return $this->redirect($response, $this->pathFor('settings.users'));
		} else {
			return $response->withStatus(404);
		}
	}

	public function createUser(ServerRequestInterface $request, ResponseInterface $response)
	{
		return $this->render($response, 'settings.users.create');
	}

	public function storeUser(ServerRequestInterface $request, ResponseInterface $response, STAILEUAccounts $staileu, Messages $flash)
	{
		$validator = new Validator($request->getParsedBody());
		$validator->required("uuid", "level");
		$validator->notEmpty("uuid", "level");
		if ($validator->isValid()) {
			$user = new User();
			$user['id'] = $validator->getValue('uuid');
			$user['level'] = $validator->getValue('level');
			if ($validator->getValue('level') == 'admin') {
				$dashboards = Dashboard::all(['id'])->toArray();
				//array flatten
				$arrayValues = [];
				foreach (new \RecursiveIteratorIterator( new \RecursiveArrayIterator($dashboards)) as $val) {
					$arrayValues[] = $val;
				}
				//end of func
				$user->dashboards()->attach($arrayValues);
			}
			$user->save();

			$flash->addMessage('success', 'Success creating user!');

			return $this->redirect($response, $this->pathFor('settings.users'));
		} else {
			$flash->addManyMessages('error', $validator->getErrors());

			return $this->redirect($response, $this->pathFor('settings.users.create'));
		}
	}
}