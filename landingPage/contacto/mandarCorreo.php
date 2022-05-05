<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../../librerias/PHPMailer/src/Exception.php';
require '../../librerias/PHPMailer/src/PHPMailer.php';
require '../../librerias/PHPMailer/src/SMTP.php';


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

if(isset($_POST['correo']) && isset($_POST['asunto']) && isset($_POST['mensaje'])){
    $correoEmisor = $_POST['correo'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];

    try {
        //Ajustes del Servidor
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host       = 'mail.allnatural.site';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pruebas@correo.allnatural.site';
        $mail->Password   = 'pruebas@correo.allnatural.site';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->SMTPDebug  = 4;
    
    
        //El que lo envía
        $mail->setFrom('pruebas@correo.allnatural.site', 'Domotizate');
        //El que lo recibe
        $mail->addAddress('pruebas@correo.allnatural.site', 'Sergio');
     
        //Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = "Domotizate: ". $asunto;
        $mail->Body    = "Desde la Página de Contacto de <a href='https://domotizate.allnatural.site'>Domotizate</a>, te escribieron:<br>".$mensaje.
        "<br>Contactar con el usuario: ". $correoEmisor;
        
        $mail->CharSet = 'UTF-8';

        if($mail->send()){
            echo 'Mensaje Enviado';
        }
        
    } catch (Exception $e) {
        echo "Mensaje no enviado. Error: {$mail->ErrorInfo}";
    }

}
