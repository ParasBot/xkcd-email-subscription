<?php
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $code = $_POST['verification_code'] ?? '';
    $codes = json_decode(file_get_contents("verification_codes.json"), true) ?? [];

    if ($email && !$code) {
        $code = generateVerificationCode();
        $codes[$email] = $code;
        file_put_contents("verification_codes.json", json_encode($codes));
        sendVerificationEmail($email, $code);
        sendWelcomeEmail($email); // Optional
        echo "Verification code sent to $email.";
    } elseif ($email && $code) {
        if (verifyCode($email, $code)) {
            registerEmail($email);
            echo "Email $email verified and registered!";
        } else {
            echo "Invalid verification code!";
        }
    }
}
?>

<form method="POST">
    Email: <input type="email" name="email" required>
    <button id="submit-email">Submit</button>
</form>

<form method="POST">
    Email: <input type="email" name="email" required>
    Verification Code: <input type="text" name="verification_code" maxlength="6" required>
    <button id="submit-verification">Verify</button>
</form>
