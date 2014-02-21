<?php
require_once 'vendor/autoload.php';

$g = new \GAuth\Auth(getenv('GAUTH_CODE'));
$code = $_GET['code'];

try {
	$result = $g->validateCode($code);
	echo ($result === true) ? 'pass' : 'fail';
} catch (\Exception $e) {
	echo 'fail';
}

?>