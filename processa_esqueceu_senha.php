<?php
include 'database.php';
require_once('src/PHPMailer.php');
require_once('src/Exception.php');
require_once('src/SMTP.php');
require 'PHPMailer-master/PHPMailerAutoload.php';


$email = addslashes($_POST['email']);
$mail = new PHPMailer(true); 
$erros = '';

if(isset($_POST['email']) && !empty($_POST['email'])){
    $query = "SELECT * FROM dados_login WHERE email = '$email'";
    $result = pg_query($connection, $query);

    if(pg_num_rows($result) == 1){


        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 2;
        
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
        
        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6
        
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;
        
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';
        
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "brunnoanndre21@gmail.com";
        
        //Password to use for SMTP authentication
        $mail->Password = "kaug6a38";
        
        //Set who the message is to be sent from
        $mail->setFrom('brunnoanndre21@gmail.com', 'Defesa Civil - Balneario Camboriu');
        
        //Set an alternative reply-to address
        //$mail->addReplyTo('replyto@example.com', 'First Last');
        
        //Set who the message is to be sent to
        $mail->addAddress($email);
        $link = 'http://google.com.br';
        //Set the subject line
        $mail->Subject = 'Criar nova senha';
        $mail->isHTML(true); 
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        $mail->Body = "Clique <a href='{$link}'>aqui</a> para criar uma senha nova";
        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';
        
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        
        //send the message, check for errors
        if (!$mail->send()) {
            var_dump($email);
            die;    
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }
    }else{
        $erros = "&naoAchou";
    }
}
//echo pg_last_error();
header('location:index.php?pagina=esqueceuSenha&erro'.$erros);
