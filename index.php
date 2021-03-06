<?php
date_default_timezone_set('America/Chicago');

require_once 'vendor/autoload.php';
require 'vendor/twilio/sdk/Services/Twilio.php';
require_once 'Logger.php';

$sid = $_SERVER['TWILIO_SID'];
$token = $_SERVER['TWILIO_TOKEN'];
$twilioClient = new Services_Twilio($sid, $token);

$gauthCode = $_SERVER['GAUTH_CODE'];
$g = new \GAuth\Auth($gauthCode);

$emailLog = new Logger(__DIR__.'/_log/email.txt');
$phoneLog = new Logger(__DIR__.'/_log/phone.txt');
$allPhoneLog = new Logger(__DIR__.'/_log/all-phone.txt');

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>iheart2fa</title>
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
        <script type="text/javascript" src="/assets/js/jquery.js"></script>
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
              <a class="navbar-brand" href="/">I &lt;heart> 2FA</a>
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
$app->post('/gauth/generate', function() use ($app, $g, $emailLog) {

    $emailAddress = $app->request->post('email');
    if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL) !== $emailAddress) {
        throw new \Exception('Bad email address!');
    }
    $emailLog->log($emailAddress);

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

$app->get('/sms', function() use ($app) {
    $app->render('sms.php');
});

$app->post('/sms/send', function() use ($app, $twilioClient, $emailLog, $phoneLog, $allPhoneLog) {

    $phone = str_replace(
        array('-', '.'), '',
        $app->request->post('phone')
    );
    $email = $app->request->post('email');

    /* Some validation! */

    if (preg_match('/[\+0-9]+/', $phone) == false) {
        return $app->render('sms-sent.php', array(
            'phone' => '', 
            'error' => "Invalid phone number!"
        ));
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL) !== $email) {
        return $app->render('sms-sent.php', array(
            'phone' => $phone, 
            'error' => "Invalid email address!"
        ));
    }

    if (in_array($phone, $allPhoneLog->get()) === false) {
        return $app->render('sms-sent.php', array(
            'phone' => $phone, 
            'error' => "A code has already been sent to that number! Don't be a spammer!"
        ));
    }

    foreach ($phoneLog->get() as $line) {
        $parts = explode('|', $line);
        if ($parts[0] === $phone) {
            return $app->render('sms-sent.php', array(
                'phone' => $phone, 
                'error' => 'That phone number already has a pending code! Cannot resend.'
            ));
        }
    }

    $emailLog->log($email);

    // Generate the code
    $length = 6;
    $code = openssl_random_pseudo_bytes($length);
    $userCode = '';
    $i = 0;
    while (strlen($userCode) < $length) {
        $userCode .= hexdec(bin2hex($code{$i}));
        $i++;
    }
    $userCode = substr($userCode, 0, 6);

    // Log the data
    $phoneLog->log(array($phone, $email, $userCode));
    $allPhoneLog->log($phone);

    // Send the SMS message
    $message = $twilioClient->account->messages->sendMessage(
        $_SERVER['TWILIO_NUMBER'],
        $phone,
        'Your code is '.$userCode
    );
    $app->render('sms-sent.php', array('phone' => $phone));
});

// Execute!
$app->run();
?>
        </div>
    </body>
</html>