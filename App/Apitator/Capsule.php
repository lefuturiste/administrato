<?php
namespace App\Apitator;

class Capsule
{
	public $scheme;

	public $endpoint;
	/**
	 * @var array
	 */
	public $params;

	public function __construct($endpoint, $params = [], $scheme = 'https')
	{
		$this->endpoint = $endpoint;
		$this->scheme = $scheme;
		$this->params = $params;
	}

	/**
	 * Get a specific model by id
	 *
	 * @param $model
	 * @param $id
	 * @return bool|Model
	 */
	public function find($model, $id)
	{
		$item = new $model();
		$item->setCapsule($this);

		//update or insert
		$query = new Query('GET', $item->getRessource() . '/' . $id);
		$query->execute($this);

		//if query is success
		if ($query->isValid()){
			//build a project from data
			foreach ($query->getData() AS $key => $value)
			{
				$item->$key = $value;
			}
			return $item;
		}
		return false;
	}

	/**
	 * Get all items of model
	 *
	 * @param $model
	 * @return bool|Collection
	 */
	public function all($model)
	{
		//update or insert
		$modelInstance = new $model();
		$query = new Query('GET', $modelInstance->getRessource() . '/');
		$query->execute($this);

		//if query is success
		if ($query->isValid()){
			//build a project from data
			$collection = new Collection();
			foreach ($query->getData() as $itemValues) {

				$item = new $model;

				foreach ($itemValues AS $key => $value)
				{
					$item->$key = $value;
				}

				$collection->addItem($item);
			}
			return $collection;
		}
		return false;
	}
}