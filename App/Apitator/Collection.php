<?php

namespace App\Apitator;

class Collection
{
	private $items = [];

	public function addItem(Model $item)
	{
		array_push($this->items, $item);

		return true;
	}

	public function toArray()
	{
		$items = [];
		foreach ($this->items as $item) {
			array_push($items, $item->toArray());
		}

		return $items;
	}
}