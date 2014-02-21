<?php
require_once 'Logger.php';

$email = trim($_GET['email']);
$code = trim($_GET['code']);

$phoneLog = new Logger(__DIR__.'/_log/phone.txt');
$lines = $phoneLog->get();
$pass = false;

foreach ($lines as $index => $line) {
	$parts = explode('|', $line);
	error_log(print_r($parts, true));

	if ($parts[1] === $email) {
		if (trim($parts[2]) === $code && $pass === false) {
			error_log('here');
			$pass = true;
			unset($lines[$index]);
		}
	}
}
$phoneLog->write($lines);
echo ($pass === true) ? 'pass' : 'fail';