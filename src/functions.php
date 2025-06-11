<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function generateVerificationCode() {
    return strval(rand(100000, 999999));
}

function registerEmail($email) {
    file_put_contents(__DIR__ . '/registered_emails.txt', $email . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function unsubscribeEmail($email) {
    $emails = file(__DIR__ . '/registered_emails.txt', FILE_IGNORE_NEW_LINES);
    $emails = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents(__DIR__ . '/registered_emails.txt', implode(PHP_EOL, $emails) . PHP_EOL);
}

function sendVerificationEmail($email, $code) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'parasjagdale2004@gmail.com'; // replace with your Gmail
        $mail->Password = 'oqza bqqc xewq ijam';    // replace with your App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('parasjagdale2004@gmail.com', 'XKCD App');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body = "<p>Your verification code is: <strong>$code</strong></p>";
        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}

function sendUnsubscribeVerificationEmail($email, $code) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'parasjagdale2004@gmail.com'; // replace with your Gmail
        $mail->Password = 'oqza bqqc xewq ijam';    // replace with your App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('parasjagdale2004@gmail.com', 'XKCD App');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Confirm Unsubscription';
        $mail->Body = "<p>To confirm, use this code: <strong>$code</strong></p>";
        $mail->send();
    } catch (Exception $e) {
        echo "Unsubscribe email error: {$mail->ErrorInfo}";
    }
}

function sendWelcomeEmail($email) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'parasjagdale2004@gmail.com'; // replace with your Gmail
        $mail->Password = 'oqza bqqc xewq ijam';    // replace with your App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('parasjagdale2004@gmail.com', 'XKCD App');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to XKCD Comics!';
        $mail->Body = "<p>🎉 You're now subscribed to daily XKCD comics!</p>";
        $mail->send();
    } catch (Exception $e) {
        echo "Welcome email error: {$mail->ErrorInfo}";
    }
}

function verifyCode($email, $code) {
    $codes = json_decode(file_get_contents(__DIR__ . '/verification_codes.json'), true) ?? [];
    return isset($codes[$email]) && $codes[$email] === $code;
}

function fetchAndFormatXKCDData() {
    $random = rand(1, 2800);
    $data = json_decode(file_get_contents("https://xkcd.com/$random/info.0.json"), true);
    return "<h2>XKCD Comic</h2>
            <img src=\"{$data['img']}\" alt=\"XKCD Comic\">
            <p><a href='http://localhost:8000/src/unsubscribe.php'>Unsubscribe</a></p>";
}

function sendXKCDUpdatesToSubscribers() {
    $emails = file(__DIR__ . '/registered_emails.txt', FILE_IGNORE_NEW_LINES);
    $body = fetchAndFormatXKCDData();

    foreach ($emails as $email) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'parasjagdale2004@gmail.com'; // replace with your Gmail
            $mail->Password = 'oqza bqqc xewq ijam';    // replace with your App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('parasjagdale2004@gmail.com', 'XKCD App');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your XKCD Comic';
            $mail->Body = $body;
            $mail->send();
        } catch (Exception $e) {
            echo "Failed to send to $email: {$mail->ErrorInfo}";
        }
    }
}
