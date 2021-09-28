<?php
    require_once __DIR__.'/../vendor/autoload.php';
    use Gregwar\Captcha\CaptchaBuilder;

    $captcha = new CaptchaBuilder;
    $_SESSION['phrase'] = $captcha->getPhrase();

    header('Content-Type: image/jpeg');
    $captcha
        ->build()
        ->save("out.jpg");