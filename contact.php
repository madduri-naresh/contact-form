<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting (for development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate reCAPTCHA
    $recaptchaSecret = '6LcZvocrAAAAAEdx1WXL622Aw8TQn1-R5nBmWxIH'; // ← Replace with your actual secret key
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if (!$recaptchaResponse) {
        echo "captcha_failed";
        exit;
    }

    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $captchaSuccess = json_decode($verify);

    if (!$captchaSuccess->success) {
        echo "captcha_failed";
        exit;
    }

    // Get form fields
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $user_type = $_POST['user_type'] ?? '';
    $help_with = $_POST['help_with'] ?? '';
    $looking_for = $_POST['looking_for'] ?? '';
    $meeting_time = $_POST['meeting_time'] ?? '';
    $meeting_mode = $_POST['meeting_mode'] ?? '';
    $additional_info = $_POST['additional_info'] ?? '';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'maddurinaresh3@gmail.com';  // your Gmail
        $mail->Password = 'uuhv svdj rfvq hamw';        // your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('maddurinaresh3@gmail.com', 'Contact Form');

        $mail->addAddress($email, $name); // Send to form user
        $mail->addCC('nareshmaddur27@gmail.com', 'Naresh Maddur'); 

        $mail->isHTML(true);
        $mail->Subject = 'New Appointment Request';
        $mail->Body = "
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>User type:</strong> $user_type</p>
            <p><strong>What do you need help with:</strong> $help_with</p>
            <p><strong>Looking for:</strong> $looking_for</p>
            <p><strong>Meeting time:</strong> $meeting_time</p>
            <p><strong>Meeting mode:</strong> $meeting_mode</p>
            <p><strong>Additional info:</strong> $additional_info</p>
        ";

        $mail->send();
        echo "success";
    } catch (Exception $e) {
        echo "error";
    }
}
?>
