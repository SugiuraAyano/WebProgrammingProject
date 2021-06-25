<?php
require_once("../include/config.php");
if (filter_var($Recipient, FILTER_VALIDATE_EMAIL)){
    require '../PHPMailer/Exception.php';
    require '../PHPMailer/PHPMailer.php';
    require '../PHPMailer/SMTP.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
	// $mail->SMTPDebug = 2;  
    $mail->Mailer = "smtp";                                      
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->Host = "smtp.gmail.com";
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64";
    $mail->WordWrap = 500;
    $mail->Username = $mailUserName;
    $mail->Password = $mailPassword;
    $mail->SetFrom('u10706147@ms.ttu.edu.tw', '郵件系統管理員');
    $mail->Subject = $Subject;
    $mail->AddAddress($Recipient, $Recipient);
    $Notice = $Recipient . " 您好\n\n" . $Message . "\n\n此信件為系統自動發出請勿回覆，謝謝！\n";
    $mail->Body = $Notice;
    $mail->msgHTML($Message);
    if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Email sent successfully";
	}
    $mail->ClearAllRecipients();
}
?>