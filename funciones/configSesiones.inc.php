<?php
    function comprobarSesionVacia(){
        if(!isset($_SESSION['nickname'])){
?>
            <script>window.location.replace("<?=localizacion()?>landingPage/login.php"); </script>
<?php
        }
    }

    function comprobarSesionIniciada(){
        if(isset($_SESSION['nickname'])){
?>
            <script>window.location.replace("<?=localizacion()?>panel/index.php"); </script>
<?php
        }
    }


?>