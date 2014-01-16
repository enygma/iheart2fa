<?php
require_once 'vendor/autoload.php';

$gauthCode = 'ZBZLWCMAKFFK66GI';
$g = new \GAuth\Auth($gauthCode);
?>
<html>
    <head>
        <title>iheart2fa</title>
    </head>
    <body>
<?php

$app = new \Slim\Slim();

/**
 * @route /
 * @method GET
 */
$app->get('/', function() use ($app, $g){
    $app->render('index.php');
});

/**
 * @route /gauth/generate
 * @method POST
 */
$app->post('/gauth/generate', function() use ($app, $g) {

    $emailAddress = $app->request->post('email');
    if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL) !== $emailAddress) {
        throw new \Exception('Bad email address!');
    }

    $data = 'otpauth://totp/'.$emailAddress.'?secret='.$g->getInitKey();
    $url = 'https://www.google.com/chart?chs=200x200&chld=M|0&cht=qr&chl='.urlencode($data);

    $app->render('generate.php', array('url' => $url));
});

/**
 * @route /gauth/verify
 * @method POST
 */
$app->post('/gauth/verify', function() use ($app, $g) {

    $code = $app->request->post('code');
    $app->render(
        'verify.php',
        array('verify' => $g->validateCode($code))
    );
});
$app->run();
?>
    </body>
</html>