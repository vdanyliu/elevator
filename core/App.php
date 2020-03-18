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
				sleep(1);
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
			if (method_exists($this, $method)) {
				if ($this->$method($user_input_arr))
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

	private function add_human($user_input_arr) {
		if (count($user_input_arr) < 4) {
			printf("Некоректное количество параметров\n");
			return (0);
		}
		$name = $user_input_arr[1];
		$on_flor = intval($user_input_arr[2]);
		$to_flor = intval($user_input_arr[3]);

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