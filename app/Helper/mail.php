<?php
require '../../vendor/autoload.php';

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
//Create an instance; passing `true` enables exceptions
$developmentMode = true;
$mail            = new PHPMailer ( $developmentMode );

try
{
  //Server settings
  $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
  $mail->isSMTP (); //Send using SMTP
  if ( $developmentMode )
  {
    $mail->SMTPOptions = [ 
      'ssl' => [ 
        'verify_peer'       => false,
        'verify_peer_name'  => false,
        'allow_self_signed' => true
      ]
    ];
  }
  $mail->Host       = 'smtp.gmail.com'; //Set the SMTP server to send through
  $mail->SMTPAuth   = true; //Enable SMTP authentication
  $mail->Username   = 'yokotechteam@gmail.com'; //SMTP username
  $mail->Password   = 'caveoiefqhxnsxzq'; //SMTP password
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
  $mail->Port       = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

  //Recipients
  $mail->setFrom ( 'yokotechteam@gmail.com', 'Task Manager' );
  $mail->addAddress ( 'www.waihlanphyo528@gmail.com', 'Joe User' ); //Add a recipient
  // $mail->addAddress ( 'ellen@example.com' ); //Name is optional
  // $mail->addReplyTo ( 'info@example.com', 'Information' );
  // $mail->addCC ( 'cc@example.com' );
  // $mail->addBCC ( 'bcc@example.com' );

  //Attachments
  // $mail->addAttachment ( '/var/tmp/file.tar.gz' ); //Add attachments
  // $mail->addAttachment ( '/tmp/image.jpg', 'new.jpg' ); //Optional name

  //Content
  $mail->isHTML ( true ); //Set email format to HTML
  $mail->Subject = 'This is Testing';
  $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  $mail->send ();
  echo 'Message has been sent';
}
catch ( Exception $e )
{
  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}