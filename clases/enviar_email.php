<?php
use PHPMailer\PHPMailer\{ PHPMailer, SMTP, Exception};

require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;  //SMTP::DEBUG_OFF;                    //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       =  MAIL_HOST;                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = MAIL_USER;                     //SMTP username
    $mail->Password   = MAIL_PASS;                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = MAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('chavezfelipelouis@gmail.com', 'LA SETCHOP DE LOWIS');
    $mail->addAddress('chavezfelipelouis@outlook.com', 'Joe User');     //Add a recipient
    $mail->addAddress('210i0087@tecmartinez.edu.mx', 'El todopoderoso tecnologico');               //Name is optional

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Detalles de su compra';

    $cuerpo = '<h4> Gracias por su compra!</h4>';
    $cuerpo .= '<p> El ID de su compra es <b>'. $id_transaccion . '</b></p>';

    $mail->Body    = $cuerpo;
    $mail->AltBody = 'Le enviamos los detalles de su compra';

    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar el correo electronico de la compra: {$mail->ErrorInfo}";
    //exit;
}