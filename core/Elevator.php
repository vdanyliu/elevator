<?php
namespace core;

class Elevator
{
	public $floors = 0;
	public $elevator_floor;
	public $elevator_direction = 0; // -1 - down;  +1 - up;
	public $people_in_elevator = array();
	public $people_on_floors = array();
	public $max_weight = 2000;
	public function __construct()
	{
		$this->init_elevator();
	}

	private function init_elevator()
	{
		while (1) {
			printf("Введите количество этажей в доме\n");
			fscanf(STDIN, "%d", $this->floors);
			$f = intval($this->floors);
			if ($f <= 0 || $f > 100) {
				printf("Некоректный ввод\nЭтажей должно быть больше 0 и меньше 100\n");
			}
			else {
				printf("Количество этажей = %d\n", $this->floors);
				$this->elevator_floor = rand(1, $this->floors);
				break;
			}
		}
	}

	public function work() {
		if ($this->is_need_stop()) {
			if ($this->elevator_direction)
				printf("Лифт остановился на %i этаже\n", $this->elevator_floor);
			if ($this->is_people_need_drop())
				$this->drop_people();
			if ($this->is_people_waiting_on_floor())
				$this->take_people();
		}
		$change = $this->chose_direction();
//		var_dump($change);
//		var_dump($this->elevator_direction);
		if (!$change) {
			$this->elevator_floor += $this->elevator_direction;
			printf("Лифт сместился на %d этаж\n", $this->elevator_floor);
		}
	}

	private function chose_direction() {
		$current = $this->elevator_direction;
		if ($this->people_in_elevator && $this->elevator_direction)
			return false;
		if ($this->people_in_elevator) {
			$people = current($this->people_in_elevator);
			if (($people->to_floor - $this->elevator_floor) > 0)
				$this->elevator_direction = 1;
			else
				$this->elevator_direction = -1;
			return true;
		}
		if ($this->is_people_waiting_on_the_direction($this->elevator_direction))
			return false;
		$this->elevator_direction = $this->get_waiting_direction();
		if ($current != $this->elevator_direction)
			return true;
		return false;
	}

	private function get_waiting_direction() {
		foreach ($this->people_on_floors as $people) {
			$result = ($people->on_floor - $this->elevator_floor);
			if ($result > 0)
				return 1;
			elseif ($result < 0)
				return -1;
		}
		return 0;
	}

	private function is_people_waiting_on_the_direction($direction) {
		foreach ($this->people_on_floors as $people) {
			$result = ($people->on_floor - $this->elevator_floor) * $direction;
			if ($result > 0)
				return true;
		}
		return false;
	}

	private function drop_people() {
		foreach ($this->people_in_elevator as $key => $people) {
			if ($people->to_floor == $this->elevator_floor) {
				printf("Персонаж %s выходит из лифта\n", $people->name);
				unset($this->people_in_elevator[$key]);
			}
		}
	}

	private function take_people() {
		foreach ($this->people_on_floors as $key => $people) {
			if (($people->on_floor == $this->elevator_floor) && $this->is_same_direction($people)) {
				printf("Персонаж %s входит в лифт\n", $people->name);
				$this->people_in_elevator[] = $people;
				unset($this->people_on_floors[$key]);
			}
		}
	}

	private function is_need_stop() {
//		var_dump($this->people_in_elevator);
//		var_dump($this->is_people_waiting_on_floor());
//		var_dump($this->is_people_need_drop());
		if (!$this->is_people_waiting_on_floor() && !$this->is_people_need_drop())
			return false;
		return true;
	}

	private function is_people_waiting_on_floor() {
		foreach ($this->people_on_floors as $people) {
			if (($people->on_floor == $this->elevator_floor) && $this->is_same_direction($people)) {
				$people_direction = $people->to_floor - $people->on_floor;
				if (($people_direction > 0 && $this->elevator_direction > 0) || ($people_direction < 0 && $this->elevator_direction < 0) || $this->elevator_direction == 0)
					return true;
			}
		}
		return false;
	}

	private function is_same_direction($people) {
		$people_direction = $people->to_floor - $people->on_floor;
		if (($people_direction > 0 && $this->elevator_direction > 0) || ($people_direction < 0 && $this->elevator_direction < 0) || $this->elevator_direction == 0)
			return true;
		return false;
	}

	private function is_people_need_drop() {
		foreach ($this->people_in_elevator as $people) {
			if ($people->to_floor == $this->elevator_floor)
				return true;
		}
		return false;
	}
}