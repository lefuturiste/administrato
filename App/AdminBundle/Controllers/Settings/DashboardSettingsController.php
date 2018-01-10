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

class DashboardSettingsController extends Controller
{
	public function getDashboards(ServerRequestInterface $request, ResponseInterface $response, Manager $manager, Helper $helper)
	{
		return $this->render($response, 'settings.dashboards.dashboards', [
			'dashboards' => Dashboard::all()->toArray()
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

	public function deleteDashboard($uuid, ServerRequestInterface $request, ResponseInterface $response)
	{
		$dashboard = Dashboard::find($uuid);
		if ($dashboard) {
			return $this->render($response, 'settings.dashboards.delete', [
				'_dashboard' => $dashboard
			]);
		} else {
			return $response->withStatus(404);
		}
	}

	public function destroyDashboard($uuid, ServerRequestInterface $request, ResponseInterface $response, Messages $flash)
	{
		$dashboard = Dashboard::destroy($uuid);
		if ($dashboard > 0) {
			$flash->addMessage('success', 'Success destroy dashboard!');

			return $this->redirect($response, $this->pathFor('settings.dashboards'));
		} else {
			return $response->withStatus(404);
		}
	}

	public function createDashboards(ServerRequestInterface $request, ResponseInterface $response)
	{
		return $this->render($response, 'settings.dashboards.create');
	}

	public function storeDashboard(ServerRequestInterface $request, ResponseInterface $response, STAILEUAccounts $staileu, Messages $flash)
	{
		$validator = new Validator($request->getParsedBody());
		$validator->required("name", "title");
		$validator->notEmpty("name", "title");
		if ($validator->isValid()) {
			$user = new Dashboard();
			$user['id'] = uniqid();
			$user['name'] = $validator->getValue('name');
			$user['title'] = $validator->getValue('title');
			$user->save();

			$flash->addMessage('success', 'Success creating dashboard!');

			return $this->redirect($response, $this->pathFor('settings.dashboards'));
		} else {
			$flash->addManyMessages('error', $validator->getErrors());

			return $this->redirect($response, $this->pathFor('settings.dashboards.create'));
		}
	}
}