# XKCD Email Subscription System

A PHP-based email verification and subscription system that delivers random [XKCD](https://xkcd.com) comics to subscribed users every 24 hours.

## 📌 Features

- ✅ Email verification using a 6-digit code
- ✅ Subscription confirmation with welcome email
- ✅ Daily random XKCD comic email (via CRON job)
- ✅ Unsubscribe feature with code confirmation
- ✅ Pure PHP (with optional PHPMailer support for real email delivery)
- ✅ Local testing support with Mailpit

---

## 🛠️ Technologies Used

- PHP (no framework)
- PHPMailer (SMTP email support)
- Mailpit (for local email testing)
- Bash (for CRON setup)

---

## 🚀 How It Works

1. **User Registration**:
   - User enters email → receives verification code.
   - Upon successful verification, email is stored in `registered_emails.txt`.

2. **Comic Delivery**:
   - `cron.php` runs every 24 hours (setup via `setup_cron.sh`)
   - Sends a random XKCD comic to all verified users.

3. **Unsubscribe**:
   - User enters their email.
   - Confirmation code is emailed.
   - Upon code verification, the user is removed from the subscription list.

---

## 📂 Folder Structure

