<?php
session_start();

//require
use Laminas\Validator\EmailAddress;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

//constants
$name = $_POST['name'];
$email = $_POST['email'];
$complaint = $_POST['complaint'];

// Check if name, email and message are given
if (!empty($name) && !empty($email) && !empty($complaint)) {
    $validator = new EmailAddress();
    if($validator->isValid($email)){

        $mail = new PHPMailer(true);

        try {
            // //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = '29554eb89ed790';
            $mail->Password = '0e539d0ac07ca7';                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            // $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress($email);               //Name is optional
            $mail->addCC('40187960@roctilburg.nl');
        
            //Content
            $mail->isHTML(false);
            $mail->Subject = 'Klachtverwerking';
            $mail->Body = $complaint;
        
            $mail->send();
            $_SESSION["success"] = "Uw klacht is in behandeling.";
        } catch (Exception $e) {
            $_SESSION["warning"] = "Het E-mail-bericht kon niet verstuurd worden.";
            echo $e->getMessage();
        }
    } else
        $_SESSION["warning"] = "Het E-mail-adres is invalide.";
} else
    $_SESSION["warning"] = "Alle velden moeten ingevuld zijn.";

echo "<br>" . $_SESSION["warning"] . $_SESSION["success"];

header("Location: /");
