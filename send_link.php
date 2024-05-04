<?php

require_once('mailer/phpmailer/PHPMailerAutoload.php');

$link = $_POST['link-send'];
$email = $_POST['email-send'];
$number = $_POST['film-number-send'];
$type = $_POST['negatives-type'];

$negativesMessage = '';
switch ($type) {
    case 'none':
        $negativesMessage = 'Tvoje negativy jsme ekologicky zlikvidovali';
        break;
    case 'with_order':
        $negativesMessage = 'Tvoje negativy ti pošleme v ekologickém <br> balení s tvojí další objednávkou FRAMES';
        break;
    case 'mail':
        $negativesMessage = 'Tvoje negativy ti pošleme samostatně <br> do tvojí poštovní schránky';
        break;
    default:
        $negativesMessage = 'Error';
        break;
}

$mail = new PHPMailer;
$mail->CharSet = 'utf-8';

// $mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'maxtaran708@gmail.com';                 // Наш логин
$mail->Password = '';                           // Наш пароль от ящика
$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to

$mail->setFrom('maxtaran708@gmail.com', 'Developing studio');   // От кого письмо 
$mail->addAddress($email);     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');
//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

$mail->isHTML(true);                                  // Set email format to HTML


$mail->Subject = 'Tvůj film je vyvolanej a naskenovanej!';
$mail->Body    = '
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Odkaz</title>
  <style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: Helvetica;
        margin: 0;
        padding: 0;
        color: black;
    }
    .container {
        padding-top: 70px;
        width: 740px;
        margin: 0 auto;
    }
    .mail-title {
        font-size: 23.31px;
        font-weight: 400;
        margin-top: 70px;
        color: black;
    }

    .mail-link-wrapper {
        margin-top: 70px;
        position: relative;
        border-radius: 1.33px;
    }

    .mail-link-wrapper:hover .mail-link-descr {
        text-decoration: underline;
    }

    .mail-link img {
        display: block;
    }

    .mail-link-descr {
        position: absolute;
        color: rgb(255, 255, 255);
        font-size: 24.98px;
        font-weight: 700;
        top: 250px;
        left: 80px;
    }

    .mail-link-descr:hover {
        text-decoration: underline;
    }

    .mail-discount {
        height: 534px;
        border-radius: 1.33px;
        background: rgb(242, 242, 242);
        margin-top: 25px;
        padding: 60px 0 0 65px;
        position: relative;
    }

    .mail-discount-title {
        font-size: 40.96px;
        font-weight: 400;
        color: black;
    }

    .discount-percent {
        font-size: 68.94px;
        color: black;
    }

    .mail-discount-descr {
        font-size: 23.31px;
        font-weight: 400;
        margin-top: 170px;
        color: black;
    }

    .mail-negatives {
        border-radius: 1.33px;
        background: rgb(235, 243, 233);
        height: 216.81px;
        margin-top: 40px;
        padding: 78px 0 0 98px;
    }

    .mail-negatives-descr {
        color: rgb(61, 137, 62);
        font-size: 23.31px;
        font-weight: 400;
    }

    .mail-inst {
        margin-top: 90px;
    }

    .mail-inst-descr {
        font-size: 23.31px;
        font-weight: 400;
        color: black;
    }

    .mail-inst-wrapper {
        margin-top: 40px;
    }

    .mail-inst-wrapper a {
        display: flex;
        text-decoration: none;
    }

    .mail-inst-link {
        font-size: 17px;
        font-weight: 400;
        color: rgb(0, 0, 0);
        margin-left: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="https://www.dropbox.com/scl/fi/ux6vstfuk3qr06h7qlcc8/frames-logo.png?rlkey=g6xzet7i816dbun4p29ogm7x8&raw=1" alt="Logo">
    <div class="mail-title" style="color: black;">
        Ahoj! <br>
        Fotky z filmu č.' . $number . ' jsme parádně <br>
        naskenovali a posíláme ti je přes WeTransfer. 
    </div>
    <div class="mail-link-wrapper">
        <a href="' . $link . '" class="mail-link" style="display: block; overflow: hidden;">
            <img src="https://www.dropbox.com/scl/fi/4msywendx48lnvrfoj1n8/frames-mail2.jpeg?rlkey=acaysff41j8e57lo99yrz5p1a&raw=1" alt="mail-link">
        </a>
    </div>
    <div class="mail-discount">
        <div class="mail-discount-title">
            SLEVA <br>
            <span class="discount-percent">10%</span>
        </div>
        <div class="mail-discount-descr" style="font-size: 23.31px; font-weight: 400; position: absolute; bottom: 47px;">
            Označ u svých fotek na Instagramu <br>
            @shoot_frames a my ti zprávou pošleme 10% slevu <br>
            na další nákup na našem e-shopu.
        </div>
    </div>
    <div class="mail-negatives">
        <div class="mail-negatives-descr">' . $negativesMessage . '</div>
    </div>
    <div class="mail-inst">
        <div class="mail-inst-descr">
            Kdyby cokoliv, piš na náš Instagram! <br>
            Za celej tým děkujem, že s náma fotíš < 3
        </div>
        <div class="mail-inst-wrapper">
            <a href="https://www.instagram.com/shoot_frames/">
                <img src="https://www.dropbox.com/scl/fi/tqmot0j7dwmpwmpc33h1r/inst-icon.png?rlkey=mzmrj09rer38gh6v9ng0rx0j2&raw=1" alt="inst" class="mail-inst-icon" style="width: 20px; height: 20px; margin-top: 4px;">
                <div class="mail-inst-link">
                    @shoot_frames
                </div>
            </a>
        </div>
    </div>
  </div>
</body>
</html>';




if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

header("Location: send_photo.php");



?>
