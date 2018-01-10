<?php
namespace App\Apitator;

class Model{

	/**
	 * @var Capsule
	 */
	protected $capsule;

	protected $ressource;

	public function toArray()
	{
		$object = (array) $this;
		$return = [];
		foreach ($object as $key => $value) {
			if (in_array($key, $this->fields)){
				$return[$key] = $value;
			}
		}
		return $return;
	}

	public function insert()
	{
		//update or insert
		$query = new Query('POST', $this->ressource, [
			'json' => $this->toArray()
		]);
		return $query;
	}

	public function update()
	{
		//update or insert
		$query = new Query('POST', $this->ressource . '/' . $this->id, [
			'json' => $this->toArray()
		]);
		return $query;
	}

	public function destroy()
	{
		//update or insert
		$query = new Query('DELETE', $this->ressource . '/' . $this->id);
		$query->execute($this->capsule);
		return $query;
	}

	/**
	 * @return mixed
	 */
	public function getRessource()
	{
		return $this->ressource;
	}

	/**
	 * @param Capsule $capsule
	 */
	public function setCapsule(Capsule $capsule)
	{
		$this->capsule = $capsule;
	}
}