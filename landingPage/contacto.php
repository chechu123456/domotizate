<?php require("../funciones/reutilizables.inc.php") ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?= cabeceraPaginasLanding() ?>
    <!-- Captcha -->
    <script src="https://www.google.com/recaptcha/api.js?render=6LeEccofAAAAAOjIw1PH8B845aB17AbyvAMl87Nk"></script>
</head>

<body id="pagContacto">
    <?= cabeceraLandingPage() ?>
    <div class="titPagsInternas">
        <h1>CONTACTA CON NOSOTROS</h1>
    </div>
    <div class="contenedorPagsInternas">
        <div class="contenedorIntPagsInternas">
            <div class="contIzqPagsInternas">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d34163.00077739902!2d-73.9741862468504!3d40.8145370697097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c256598d232339%3A0xda8b85263f93969e!2sBroadway%2C%20New%20York%2C%20NY%2C%20EE.%20UU.!5e0!3m2!1ses!2ses!4v1650289412908!5m2!1ses!2ses" width="550" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <p>C/ Camino Lomeli, 4, 37º B</p>
                <p>San Cobo, Malaga, España.</p>
                <p>Tel: +34 699762587</p>
                <p><a href="mailto:hola@domotizate.site">hola@domotizate.site</a></p>
                <p><span class="negrita">DOMOTIZATE<span></p>
            </div>
            <div class="contDerPagsInternas">
                <form action="contacto/mandarCorreo.php" method="POST" id="formContacto" autocomplete="off">
                    <label for="correo">Tu correo</label>
                    <input type="text" name="correo" id="correo">
                    <label for="asunto">Asunto</label>
                    <input type="text" name="asunto" id="asunto">
                    <label for="mensaje">Tu mensaje:</label>
                    <textarea name="mensaje" id="mensaje" cols="30" rows="10"></textarea>

                    <input type="submit" value="Enviar" class="btn btnContacto">
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                    <input type="hidden" name="action" id="action" value="formularioContacto">
                </form>
            </div>
        </div>
    </div>
    <?= piePagLandingPage() ?>
</body>

</html>

<script>


    $(document).ready(function() {
        if ($("#correo").val() == "" || $("#asunto").val() == "" || $("#mensaje").val() == "") {
            error();
        }
    });

    $(document).on("blur", "#correo", function() {
        textoCorreo = $("#correo").val();
        validarCorreo = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
        if (!validarCorreo.test(textoCorreo)) {
            swal("ERROR!", "Tu correo electrónico no tiene un formato correcto", "warning");
        }
    });


    $(document).on("blur", "#asunto", function() {
        textoAsunto = $("#asunto").val();
        if (textoAsunto.lenght == 0 || textoAsunto == "") {
            swal("ERROR!", "El asunto del mensaje no puede estar vacío", "warning");
        }
    });


    $(document).on("blur", "#mensaje", function() {
        textoMensaje = $("#mensaje").val();
        if (textoMensaje.lenght == 0 || textoMensaje == "") {
            swal("ERROR!", "El mensaje está vacío", "warning");
        }
    });

    $(document).on("keyup", "#correo, #asunto, #mensaje", function() {
        validarCorreo = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        if ($("#correo").val() == "" || $("#asunto").val() == "" || $("#mensaje").val() == "" || !validarCorreo.test($("#correo").val())) {
            error();
        } else {
            noError();
        }
    });


    function error() {
        $("#formContacto input[type='submit']").prop("disabled", true);
        $("#formContacto input[type='submit']").css("cursor", "not-allowed");
        $("#formContacto input[type='submit']").css("background", "grey");
        $("#formContacto input[type='submit']").css("opacity", ".65");
    }

    function noError() {
        $("#formContacto input[type='submit']").prop("disabled", false);
        $("#formContacto input[type='submit']").css("cursor", "pointer");
        $("#formContacto input[type='submit']").css("background", "#2271C8");
        $("#formContacto input[type='submit']").css("opacity", "1");
    }


    //Enviar formulario por AJAX
    $(document).on("submit", function(e) {
        e.preventDefault();
        if ($("#correo").val() == "" || $("#asunto").val() == "" || $("#mensaje").val() == "") {
            swal("ERROR!", "Hay algún parámetro vacío", "warning");
            $("#formContacto input[type='submit']").prop("disabled", true);
            $("#formContacto input[type='submit']").css("cursor", "not-allowed");
        } else {

            grecaptcha.ready(function() {
                grecaptcha.execute('6LeEccofAAAAAOjIw1PH8B845aB17AbyvAMl87Nk', {
                        action: 'formulario'
                    })
                    .then(function(token) {
                        var recaptchaResponse = document.getElementById('recaptchaResponse');
                        recaptchaResponse.value = token;
                        enviarCorreo();
                    });
            });

        }

    });

    function enviarCorreo() {
            $.ajax({
                    method: "POST",
                    url: "contacto/mandarCorreo.php",

                    data: {
                        correo: $('#correo').val(),
                        asunto: $('#asunto').val(),
                        mensaje: $('#mensaje').val(),
                        action: $('#action').val(),
                        recaptcha_response: $('#recaptchaResponse').val(),
                    },
                })
                .done(function(data) {
                    console.log(data);

                    if (data) {
                        //alert(data);
                        var mensajeEnviado = "Mensaje Enviado";
                        var mensajeError = "Mensaje no enviado";


                        if (data.includes(mensajeError)) {
                            swal("ERROR!", "El correo no se ha podido enviar. Intentelo más tarde", "warning");
                        } else if (data.includes(mensajeEnviado)) {
                            swal("Enviado!", "Su correo se ha enviado correctamente", "success");
                        } 
                    }
                })
                .fail(function() {
                    alert('Error!!');
                });
        }
</script>