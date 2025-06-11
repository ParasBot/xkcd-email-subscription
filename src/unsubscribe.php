<?php
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['unsubscribe_email'] ?? '';
    $code = $_POST['verification_code'] ?? '';
    $codes = json_decode(file_get_contents("unsubscribe_codes.json"), true) ?? [];

    if ($email && !$code) {
        $code = generateVerificationCode();
        $codes[$email] = $code;
        file_put_contents("unsubscribe_codes.json", json_encode($codes));
        sendUnsubscribeVerificationEmail($email, $code);
        echo "Confirmation code sent to $email.";
    } elseif ($email && $code) {
        if (isset($codes[$email]) && $codes[$email] === $code) {
            unsubscribeEmail($email);
            echo "Email $email has been unsubscribed.";
        } else {
            echo "Invalid code.";
        }
    }
}
?>

<form method="POST">
    Email: <input type="email" name="unsubscribe_email" required>
    <button id="submit-unsubscribe">Unsubscribe</button>
</form>

<form method="POST">
    Email: <input type="email" name="unsubscribe_email" required>
    Verification Code: <input type="text" name="verification_code" maxlength="6" required>
    <button id="submit-verification">Verify</button>
</form>
