<?php
require_once 'vendor/autoload.php';

$gauthCode = 'ZBZLWCMAKFFK66GI';
$g = new \GAuth\Auth($gauthCode);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>iheart2fa</title>
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
    </head>
    <body>
        <div class="container">
          <!-- Static navbar -->
          <div class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">I &lt;heart> 2FA</a>
            </div>
            <div class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li><a href="/gauth">Google Authenticator</a></li>
                <li><a href="/sms">SMS (via Twilio)</a></li>
              </ul>
            </div><!--/.nav-collapse -->
          </div>
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
 * @route /gauth
 * @method GET
 */
$app->get('/gauth', function() use ($app) {

    $app->render('gauth.php');
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
        </div>
    </body>
</html>