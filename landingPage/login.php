<?php 
    require("../funciones/reutilizables.inc.php");
    session_start();
    comprobarSesionIniciada();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?= cabeceraPaginasLanding() ?>
</head>

<body id="pagLogin">
    <?= cabeceraLandingPage() ?>
    <div class="titPagsInternas">
        <h1>INICIAR SESIÓN</h1>
    </div>
    <div class="contenedorPagsInternas">
        <div class="contenedorIntPagsInternas">
            <div class="contIzqPagsInternas">
                <form action="sesion/procesar.php" method="post" class="formularioLogin" autocomplete="off">
                    <div class="titFormLogin">
                        <h2>Bienvenido</h2>
                    </div>
                    <div class="cajaParametrosLogin">
                        <label for="nickname">Usuario</label>
                        <input type="text" name="nickname" id="nickname" data-bs-placement="bottom" autocomplete="off" autocorrect="off" spellcheck="false">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" data-bs-placement="bottom" >
                    </div>
                    <div class="btnForm">
                        <input class="btn" type="submit" value="Entrar">
                    </div>
                    <div class="textoRegistrar">
                        <p>¿No tienes cuenta? <span class="textoRojo" id="loguearse">Registrarse</span></p>
                    </div>
                </form>
                <form action="sesion/procesar.php" method="post" class="formularioRegistro" autocomplete="off">
                    <div class="titFormLogin">
                        <h2>Únete a nosotros</h2>
                    </div>
                    <div class="cajaParametrosRegistro">
                        <div class="col1Registro">
                            <label for="nick">Usuario</label>
                            <input type="text" name="nickname" id="nick"  data-placement="bottom" >
                            <label for="pass">Contraseña</label>
                            <input type="password" name="password" id="pass">
                            
                        </div>
                        <div class="col2Registro">
                            <label for="idCasa">Asociarte a una casa</label>
                            <input type="text" name="idCasa" id="idCasa" placeholder="Inserta ID de la casa">
                            <label for="nombCasa">Nombre de la casa</label>
                            <input type="text" name="nombCasa" id="nombCasa">
                        </div>
                    </div>
                    <div class="cajaLocalidad">
                        <label for="localidad">Localidad</label>
                        <select name="localidad" id="localidad">
                            <option value="" disabled selected="selected">-- Seleccione una --</option>
                            <option value="ponferrada">Ponferrada</option>
                            <option value="barco">O Barco de Valdeorras</option>
                            <option value="coruna">A Coruña</option>
                            <option value="madrid">Madrid</option>
                            <option value="barcelona">Barcelona</option>
                        </select>
                    </div>
                    <div class="btnForm">
                        <input class="btn" type="submit" value="Entrar">
                    </div>
                    <div class="textoRegistrar">
                        <p>¿Ya tienes cuenta? <span class="textoRojo" id="registrarse">Loguearse</span></p>
                    </div>
                </form>
            </div>
            <div class="contDerPagsInternas">
                <img src="<?= localizacion() ?>imagenes/imagenesSitio/login.webp" alt="Imagen Login">
            </div>
        </div>
    </div>

    <?= piePagLandingPage() ?>


    <script>

        function mostrar_login() {
            $(".formularioRegistro").fadeOut('slow', function() {
                $(".formularioLogin").fadeIn();
            });

            $(".contDerPagsInternas img").fadeOut('slow', function() {
                $(".contDerPagsInternas img").attr("src", "<?= localizacion() ?>imagenes/imagenesSitio/login.webp");
                $(".contDerPagsInternas img").css("width", "50%");
                $(".contDerPagsInternas img").fadeIn();
            });

            $(".titPagsInternas h1").fadeOut('slow', function() {
                $(".titPagsInternas h1").html("INICIAR SESIÓN");
                $(".titPagsInternas h1").fadeIn();
            });
        }
        
        function mostrar_registro() {
            $(".formularioLogin").fadeOut('slow', function() {
                $(".formularioRegistro").fadeIn();
            });

            $(".contDerPagsInternas img").fadeOut('slow', function() {
                $(".contDerPagsInternas img").attr("src", "<?= localizacion() ?>imagenes/imagenesSitio/registro.webp");
                $(".contDerPagsInternas img").css("width", "60%");
                $(".contDerPagsInternas img").fadeIn();
            });

            $(".titPagsInternas h1").fadeOut('slow', function() {
                $(".titPagsInternas h1").html("REGISTRARSE");
                $(".titPagsInternas h1").fadeIn();
            });
        }

        //Si los elementos cambian, no se pueden modificar sobre el dom que se cargo al inicio de la pagina
        $(document).ready(function() {
        });


        //Si hay elementos que han cambiado en la página, se deben hacer buscando de nuevo en el DOM
        $(document).on("click", "#registrarse", function(){
            mostrar_login();
        });

        $(document).on("click", "#loguearse", function(){
            mostrar_registro();
        });

        
        $(document).on("submit", ".formularioLogin", function(e){
            e.preventDefault();

            if($("#nickname").val()==""){
                swal("ERROR!", "El usuario está en blanco", "warning");
            }else if($("#password").val()==""){
                swal("ERROR!", "La contraseña está en blanco", "warning");
            }else{
                var contentBytes = CryptoJS.enc.Utf8.parse($('#password').val());
                var contentHash = CryptoJS.SHA256(contentBytes);
                var contentBase64String = CryptoJS.enc.Base64.stringify(contentHash);
                var contIzq = $('.contIzqPagsInternas').html();
                $.ajax({
                    method: "POST",
                    url: "sesion/procesar.php",
                    //Los datos q envio:
                    // - Primer valor, es el valor del POST del  fichero "procesador"
                    // - Segundo valor es lo que almacena el input del formulario
                    data: {
                        nickname: $('#nickname').val(),
                        password: contentBase64String,
                    },
                    beforeSend: function() {
                        $(".contIzqPagsInternas").html();
                        $(".contIzqPagsInternas").html('<div class="contenedorCarga"><img class="imgLoading" src="https://www.iecm.mx/www/sites/ciudadanosuni2esdeley/plugins/event-calendar-wd/assets/loading.gif"></div>');
                    }
                })
                .done(function(data) {
                    usuarioOK = "Usuario y contraseña OK";
                    if(data.includes(usuarioOK)){
                        //usuario y contraseña coinciden
                        window.location.href = "../panel/index.php";
                    }else{
                        //usuario y contraseña no coinciden
                        $(".contIzqPagsInternas").html();
                        $('.contIzqPagsInternas').html(contIzq);
                        swal("ERROR!", "El usuario o la contraseña no coinciden", "warning");
                    }
                    //alert(data);
                })
                .fail(function() {
                    alert('Error!!');
                });
            }
        });

        $(document).on("submit", ".formularioRegistro", function(e){
            e.preventDefault();

            if($("#nick").val()==""){
                swal("ERROR!", "El usuario está en blanco", "warning");
            }else if($("#pass").val()==""){
                swal("ERROR!", "La contraseña está en blanco", "warning");
            }else if($("#localidad option:selected").val()==""){
                swal("ERROR!", "No se ha seleccionado ninguna localidad", "warning");
            }else{
                var contIzq = $('.contIzqPagsInternas').html();
                var contentBytes = CryptoJS.enc.Utf8.parse($('#pass').val());
                var contentHash = CryptoJS.SHA256(contentBytes);
                var contentBase64String = CryptoJS.enc.Base64.stringify(contentHash);
                $.ajax({
                        method: "POST",
                        url: "sesion/procesar.php",
                        //Los datos q envio:
                        // - Primer valor, es el valor del POST del  fichero "procesador"
                        // - Segundo valor es lo que almacena el input del formulario
                        data: {
                            nickname: $('#nick').val(),
                            password: contentBase64String,
                            idCasa: $('#idCasa').val(),
                            nombCasa: $('#nombCasa').val(),
                            localidad: $('#localidad').val(),
                        },
                        beforeSend: function() {
                            $(".contIzqPagsInternas").html();
                            $(".contIzqPagsInternas").html('<div class="contenedorCarga"><img class="imgLoading" src="https://www.iecm.mx/www/sites/ciudadanosuni2esdeley/plugins/event-calendar-wd/assets/loading.gif"></div>');
                        }
                    })
                    .done(function(data) {
                        //console.log(data);
                        
                        if(data){
                            //alert(data);
                            var usuarioEncontrado = "Usuario encontrado";
                            var idCasaCambiado = "ID de casa cambiado";

                            var usuarioOK = "Usuario creado correctamente";

                            if(data.includes(usuarioEncontrado)){
                                swal("ERROR!", "El usuario ya existe", "warning");
                            }else if(data.includes(idCasaCambiado)){
                                swal("AVISO!", "Se le ha asignado un ID diferente", "info");
                                //Al hacer click en aceptar en el aviso, redireccionar al panel
                                $(document).on("click", ".swal-button", function(e){
                                    if(data.includes(usuarioOK)){
                                        //Todo OK, redireccionar al panel
                                        window.location.href = "../panel/index.php";
                                        //console.log(data);                                      
                                    }
                                });
                            }else{
                                //Todo OK, redireccionar al panel
                                window.location.href = "../panel/index.php";
                            }
                           
                        }
                        

                        $(".contIzqPagsInternas").html();
                        $('.contIzqPagsInternas').html(contIzq);
                           
                        
                        //alert(data);
                    })
                    .fail(function() {
                        alert('Error!!');
                    });
            }
            
        });

   

    </script>
</body>

</html>