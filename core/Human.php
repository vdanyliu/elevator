<?php


namespace core;


class Human
{
	public $name;
	public $wight;
	public $on_floor;
	public $to_floor;


	/**
	 * Human constructor.
	 * @param $name //Имя персонажа
	 * @param $on_floor // Этаж на котором ждет
	 * @param $to_floor // Этаж на который надо
	 */
	public function __construct($name, $on_floor, $to_floor)
	{
		$this->name = $name;
		$this->on_floor = $on_floor;
		$this->to_floor = $to_floor;
	}
}