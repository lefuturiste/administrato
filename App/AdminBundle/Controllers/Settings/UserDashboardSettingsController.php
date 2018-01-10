<?php

namespace App\AdminBundle\Controllers\Settings;

use App\AdminBundle\Dashboard;
use App\AdminBundle\User;
use App\Controllers\Controller;
use Illuminate\Database\Capsule\Manager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Flash\Messages;
use STAILEUAccounts\STAILEUAccounts;
use Validator\Validator;

class UserDashboardSettingsController extends Controller
{
	public function createUserDashboard($uuid, ServerRequestInterface $request, ResponseInterface $response){
		return $this->render($response, 'settings.users.dashboards.create', [
			'dashboards' => Dashboard::all(),
			'_user' => User::find($uuid)->first()->toArray()
		]);
	}

	public function storeUserDashboard($uuid, ServerRequestInterface $request, ResponseInterface $response, Messages $flash){
		$validator = new Validator($request->getParsedBody());
		$validator->required("uuid");
		$validator->notEmpty("uuid");
		if ($validator->isValid()){
			$user = User::find($uuid)->first();
			$user->dashboards()->attach($validator->getValue('uuid'));
			$user->save();

			$flash->addMessage('success', 'Success adding dashboard!');
			return $this->redirect($response, $this->pathFor('settings.users.view', ['uuid' => $uuid]));
		}else{
			$flash->addManyMessages('error', $validator->getErrors());
			return $this->redirect($response, $this->pathFor('settings.users.dashboards.create', ['uuid' => $uuid]));
		}
	}

	public function destroyUserDashboard($uuid, $dashboard_id, ServerRequestInterface $request, ResponseInterface $response, Messages $flash)
	{
		$user = User::find($uuid)->first();
		$user->dashboards()->detach($dashboard_id);

		$flash->addMessage('success', 'Success detaching dashboard!');
		return $this->redirect($response, $this->pathFor('settings.users.view', ['uuid' => $uuid]));
	}
}