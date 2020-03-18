<?php
use core\App;

spl_autoload_register(function ($class) {
	require_once str_replace('\\', '/', $class) . '.php';
});

//printf("%s\n", "Hello world");
//fscanf(STDIN, "Введите Строку\n%s", $number);
//echo $number;
//for ($i = 0; $i < 10000; ++$i)
//{
//	usleep(1);
//	echo $i.PHP_EOL;
//
$app = new App();
$app->run();
