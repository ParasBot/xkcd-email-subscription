<?php
include 'functions.php';

$message = '';

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
        $message = "Verification code sent to <strong>$email</strong>.";
    } elseif ($email && $code) {
        if (verifyCode($email, $code)) {
            registerEmail($email);
            $message = "Email <strong>$email</strong> verified and registered!";
        } else {
            $message = "❌ Invalid verification code!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>XKCD Email Verification</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        form { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        h2 { color: #333; }
        input { margin: 10px 0; padding: 8px; width: 100%; }
        button { padding: 8px 15px; background: #4285f4; color: white; border: none; cursor: pointer; }
        .message { padding: 10px; background: #f2f2f2; margin: 10px 0; border-left: 4px solid #4285f4; }
    </style>
</head>
<body>
    <h2>XKCD Daily Comic Subscription</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <h3>Step 1: Enter your email</h3>
    <form method="POST">
        <input type="email" name="email" required placeholder="Your email address">
        <button type="submit" id="submit-email">Submit</button>
    </form>

    <h3>Step 2: Verify your email</h3>
    <form method="POST">
        <input type="email" name="email" required placeholder="Enter your email again">
        <input type="text" name="verification_code" maxlength="6" required placeholder="Enter 6-digit code">
        <button type="submit" id="submit-verification">Verify</button>
    </form>
</body>
</html>
