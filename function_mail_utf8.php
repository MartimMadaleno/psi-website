<?php 
  require 'vendor/autoload.php';


function mail_utf8($to, $subject , $message, $nome) {
  $email = new \SendGrid\Mail\Mail(); 
  $email->setFrom("thelastbreach@gmail.com", "TheLastBreach");
  $email->setSubject($subject);
  $email->addTo($to, $nome);
  $email->addContent("text/html", $message);
  $sendgrid = new \SendGrid("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");
  try {
      $response = $sendgrid->send($email);
  } catch (Exception $e) {
      echo 'Caught exception: '. $e->getMessage() ."\n";
  }
}
?>