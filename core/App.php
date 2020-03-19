<?php


namespace core;


class App
{
	public $elevator;
	public $is_waite_user_input = true;

	public function __construct()
	{
		$this->elevator = new Elevator();
	}

	public function run()
	{
		while (true) {
			$this->get_user_input($this->is_waite_user_input);
			if (!$this->is_waite_user_input)
				time_nanosleep(0, 500000000);
			$this->elevator_turn();
		}
	}

	private function elevator_turn() {
		if (!$this->elevator->people_on_floors && !$this->elevator->people_in_elevator){
			$this->is_waite_user_input = true;
			printf("Лифт ждет людей\n");
		}
		else {
			$this->elevator->work();
		}
	}

	private function get_user_input($is_do) {
		while (true) {
			if (!$is_do)
				break;
			$user_input = NULL;
			$user_input = trim(fgets(STDIN));
			if (!$user_input)
				break;
			$user_input_arr = explode(' ', $user_input);
			$method = current($user_input_arr);
			unset($user_input_arr[0]);
			$user_input = implode(' ', $user_input_arr);
			if (method_exists($this, $method) && $this->is_legal_user_method($method)) {
				if ($this->$method($user_input))
					break;
			}
			else {
				printf("Вы ввели некоректные данные %s\n", $user_input);
				printf("Такой команды не существует %s\n", current($user_input_arr));
			}
		}
	}

	private function do_all() {
		$this->is_waite_user_input = false;
		return true;
	}

	private function add_humans($user_input) {
		$user_input_arr = explode(',', $user_input);
		foreach ($user_input_arr as $user_human) {
			$user_human = trim($user_human);
			if (!$this->add_human($user_human))
				return false;
		}
		return true;
	}

	private function add_human($user_input) {
		$user_input_arr = explode(' ', $user_input);
		if (count($user_input_arr) < 3) {
			printf("Некоректное количество параметров\n");
			return (0);
		}
		var_dump($user_input_arr);
		$name = $user_input_arr[0];
		$on_flor = intval($user_input_arr[1]);
		$to_flor = intval($user_input_arr[2]);

		if ((!$this->isFloorCorrect($on_flor) || !$this->isFloorCorrect($to_flor)) && $on_flor != $to_flor) {
			printf("Некоректные параметры этажа\n");
			return false;
		}

		$this->elevator->people_on_floors[] = new Human($name, $on_flor, $to_flor);
		return 1;
	}

	private function isFloorCorrect($floor) {
		if ($floor < 1 || $floor > $this->elevator->floors)
			return false;
		return true;
	}

	private function is_legal_user_method($method) {
		$available_methods = array(
			'add_human',
			'do_all',
			'add_humans',
		);
		return in_array($method, $available_methods);
	}

	public function test()
	{
		printf("%s\n", "Hello world");
//		echo $number;
//		for ($i = 0; $i < 10000; ++$i) {
//			usleep(1);
//			echo $i.PHP_EOL;
//			}
	}
}