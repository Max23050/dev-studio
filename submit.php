<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "form_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connection_error);
}

$film_number = $_POST['film_number'];
$email = $_POST['email'];
$negatives = $_POST['negatives'];
$address = isset($_POST['address']) ? $_POST['address'] : '';
// $price = $_POST['price'];
$price = 239;

if ($negatives === 'with_order') {
    $stmt = $conn->prepare("INSERT INTO next_order_orders (submission_id, email, film_number) VALUES ((SELECT MAX(id) FROM submissions), ?, ?)");
    $stmt->bind_param("ss", $email, $film_number);
    $stmt->execute();
    $stmt->close();
} else if ($negatives === 'mail') {
    $stmt = $conn->prepare("SELECT film_number FROM next_order_orders WHERE email = ? AND is_fulfilled = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $previousNumbers = [];
    while ($row = $result->fetch_assoc()) {
        $previousNumbers[] = $row['film_number'];
    }
    $stmt->close();

    $previousOrderWithNegativesFilmNumber = implode(", ", $previousNumbers);

    $stmt = $conn->prepare("UPDATE next_order_orders SET is_fulfilled = 1 WHERE email = ? AND is_fulfilled = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();
}

if ($negatives === 'pickup') {
    $price = 254;
} else if ($negatives === 'mail') {
    $price = 274;
}

$insertQuery = "INSERT INTO submissions (film_number, email, negatives, address, price, previous_order_with_negatives_film_number) VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("ssssis", $film_number, $email, $negatives, $address, $price, $previousOrderWithNegativesFilmNumber);

if ($stmt->execute()) {
    require_once('mailer/phpmailer/PHPMailerAutoload.php');
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

    $mail->Subject = 'Kód pro poslání filmu na vyvolání';
    $mail->Body = '
    <html>
    <head>
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

        .mail-dev-wrapper {
            height: 508px;
            border-radius: 1.33px;
            background: rgb(33, 33, 33);
            margin-top: 70px;
            padding: 55px 0 0 65px;
        }

        .mail-dev-descr {
            color: rgb(255, 255, 255);
            font-size: 18.98px;
            font-weight: 400;
            margin-top: 55px;
        }

        .mail-dev-code {
            color: rgb(255, 255, 255);
            font-size: 33.01px;
            font-weight: 700;
            margin-top: 30px;
        }

        .mail-dev-map {
            color: rgb(255, 255, 255);
            font-size: 19.15px;
            font-weight: 400;
            margin-top: 120px;
            cursor: pointer;
        }

        .mail-dev-map:hover {
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


        .mail-inst {
            margin-top: 90px;
        }

        .mail-inst-descr {
            font-size: 23.31px;
            font-weight: 400;
            color: black;
        }

        .mail-inst-descr:nth-child(2) {
            margin-top: 40px;
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
        <div class="mail-title">
            Super, teď už jen film vlož do našeho znovu-uzavíratelného <br>
            obalu, zalep samolepkou a zanes na pobočku Zásilkovny. 
        </div>
        <div class="mail-dev-wrapper">
            <img src="https://www.dropbox.com/scl/fi/bmkkr796r1q81sgj8447p/dev-studio-mail.png?rlkey=fhzjbtfrgg4byydqqn2by34yc&raw=1" alt="dev-logo" class="mail-dev-logo">
            <div class="mail-dev-descr">
                Kód, kterým k nám <br> 
                bezplatně film přes <br>
                Zásilkovnu pošleš:
            </div>
            <div class="mail-dev-code">
                345678
            </div>
            <a href="https://www.zasilkovna.cz/pobocky?gad_source=1&gclid=Cj0KCQjw_qexBhCoARIsAFgBleud7GHMoJcWoS08YZgL92FnawVGO5yqMSTl5BhDcm8P7iCYjQzg_DIaAt7wEALw_wcB" class="mail-dev-map" style="display: block; color: rgb(255, 255, 255);">
                Mapa poboček zásilkovny >
            </a>
        </div>
        <div class="mail-discount">
            <div class="mail-discount-title">
                SLEVA <br>
                <span class="discount-percent">15%</span>
            </div>
            <div class="mail-discount-descr">
                Pošli do Developing studia více Frames filmů v <br> 
                jednom obalu najednou a my ti pošleme 15% <br>
                slevu na další nákup na našem e-shopu.
            </div>
        </div>
        <div class="mail-inst">
            <div class="mail-inst-descr">
                Jestli už náš obal nemáš, zabal prosím film <br>
                pořádně do obalu svého.
            </div>
            <div class="mail-inst-descr" style="margin-top: 40px;">
                Jestli potřebuješ fakturu, napiš nám <br>
                na Instagram a my ti ji pošleme, díky.
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
    // Перенаправление на главную страницу после успешного добавления записи
    header("Location: index.html"); // Измените "index.html" на URL вашей главной страницы
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>