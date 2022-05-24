<?php 
    namespace bd;

    /**
     * PHP: listado.php
     * BASE DE DATOS: k204701_domotizate
     * @author Sergio <sergyrp98@gmail.com>
     */

    //require("\bd\Conexion.php");
    class Listado extends Conexion{

        /**
         * Constructor - Mandar parámetros a la clase padre de conexión a la bd
         * @param string db_host   nombre del host / dominio
         * @param string db_user   nombre del usuario con acceso a la bd
         * @param string db_passwd contraseña del usuario con acceso a la bd
         * @param string db_name   nombre de la base de datos
         * @return null 
         * 
        */
        function __construct(String $db_host, String $db_user, String $db_passwd, String $db_name)
        {
            parent::__construct($db_host, $db_user, $db_passwd, $db_name);
        }

        /**
         *  Buscar si existe un usuario
         * 
         *  @access public
         *  @return boolean
         */
        function buscarUsuario(String $nickname){
            $query = 'SELECT nickname FROM usuario WHERE nickname = "'.$nickname.'" LIMIT 1';
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            
            $usuarioEncontrado = false;

            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
                $usuarioEncontrado = false;
            }else{
                if($res->num_rows == 0){
                    echo "<p>\n No hay coincidencias con el usuario introducido. OK</p>";      
                    $usuarioEncontrado = false;
                }else{
                    echo "<p>\nUsuario encontrado</p>"; 
                    $usuarioEncontrado = true;
                }
            }
            echo ' nr: '. $res->num_rows.'<br>';

            mysqli_close($enlace);

            return $usuarioEncontrado;
        }

        /**
         *  Coger los datos almacenados de ese Usuario
         * 
         *  @access public
         *  @return array|boolean
         */
        function recuperarDatosUsuario(String $nickname){
            $query = 'SELECT * FROM usuario WHERE nickname = "'.$nickname.'" LIMIT 1';
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            $fila = mysqli_fetch_array($res);
            

            if($res === false) {
                echo "<div class='contenedorErrores'>SQL Error: " . mysqli_error($enlace) . "</div>";
                $fila = false;
            }else{
                if($res->num_rows == 0){
                    $fila = false;
                }
            }

            mysqli_close($enlace);

            return $fila;
        }

        /**
         *  Verificar si la contraseña coincide con la que hay almacenada de ese usuario
         * 
         *  @access public
         *  @return boolean
         */
        function comprobarUsuarioContrasena(String $nickname, String $password){
            $query = 'SELECT * FROM usuario WHERE nickname = "'.$nickname.'" AND  password = "'.$password.'" LIMIT 1';
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);

            $coinciden = false;

            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
                $coinciden = false;
            }else{
                if($res->num_rows == 0){
                    echo "\n<p>ERROR:Usuario y contraseña no coinciden</p>";   
                    $coinciden = false;
                }else{
                    echo "\n<p>Usuario encontrado</p>";      
                    $coinciden = true;
                }
            }
            echo ' nr: '. $res->num_rows.'<br>';

            mysqli_close($enlace);

            return $coinciden;
        }

        /**
         *  Crear un usuario realizando las comprobaciones como saber si existe antes el usuario
         *  pasado, saber si pertenece a una casa o si hay que asignarle un idCasa aleatorio, 
         *  crear el tema para que pueda personalizarlo, y crear los sensores que pertenecen a
         *  esta casa asignada.
         * 
         *  @access public
         *  @return null|int
         */
        function crearUsuario(String $nickname, String $password, String $localidad, String $nombCasa, int $idCasa){
            //Si el idCasa no esta vacío 
            if(!empty($idCasa) || $idCasa != ""){
                //Se comprueba si existe ese idCasa en la base de datos
                if($this->buscarIdCasa($idCasa) === true){
                    $this->crearTema();
                    $idTema = $this->obtenerUltIdTema();
                    //Si existe en la base de datos, se crea el usuario asignandole esa casa con el idCasa introducido
                    $query = 'INSERT INTO usuario VALUES ("'.$nickname.'", "'.$password.'", "'.$localidad.'",  "'.$idTema.'",  "'.$idCasa.'")';
    
                    $enlace = parent::conecta();
                    $res = $enlace ->query($query);
                    $_SESSION['idCasa'] = $idCasa;
                    if($res === false) {
                        echo "SQL Error: " . mysqli_error($enlace);                       
                    }else{
                        if($enlace->affected_rows == 0){
                            echo "<p>ERROR: No se ha realizado el alta del usuario con una casa asociada</p>";      
                        }else{
                            echo "<p>Alta realizada del usuario con casa asociada</p>";   
                        }
                    }
                    echo ' ar: '. $enlace->affected_rows.'<br>';
                    mysqli_close($enlace);
                    
                
                }else{
                    echo "El id de casa no coincide con otra casa";
                    echo "ID de casa cambiado";    
                    $this->crearCasa();
                    $idCasa = $this->buscarIdUltCasa();
                    $this->cambiarNombreCasa($nombCasa, $idCasa);
                    $this->crearTema();
                    $idTema = $this->obtenerUltIdTema();
                    $query = 'INSERT INTO usuario VALUES ("'.$nickname.'", "'.$password.'", "'.$localidad.'", "'.$idTema.'", "'.$idCasa.'")';
                    $_SESSION['idCasa'] = $idCasa;

                    $enlace = parent::conecta();
                    $res = $enlace ->query($query);
                    
                    if($res === false) {
                        echo "SQL Error: " . mysqli_error($enlace);
                    }else{
                        if($enlace->affected_rows == 0){
                            echo "<p>ERROR: No se pudo crear el usuario</p>";      
                        }else{
                            echo "<p>Alta de usuario realizada</p>";      
                        }
                    }
                    echo ' ar: '. $enlace->affected_rows.'<br>';

                    $this->crearSensorTienenRegistro($idCasa, $nickname);
                    
                    mysqli_close($enlace);
                }                    
                
            }else{
                echo "El id de casa no coincide con otra casa";
                echo "ID de casa asignado"; 
                //Si el idCasa no coincide con ninguno en la base de datos, se le asigna el siguiente id al último existente   
                $this->crearCasa();
                $idCasa = $this->buscarIdUltCasa();
                $this->cambiarNombreCasa($nombCasa, $idCasa);
                $this->crearTema();
                $idTema = $this->obtenerUltIdTema();
                $query = 'INSERT INTO usuario VALUES ("'.$nickname.'", "'.$password.'", "'.$localidad.'", "'.$idTema.'", "'.$idCasa.'")';
                $_SESSION['idCasa'] = $idCasa;

                $enlace = parent::conecta();
                $res = $enlace ->query($query);
    
                if($res === false) {
                    echo "SQL Error: " . mysqli_error($enlace);
                }else{
                    if($enlace->affected_rows == 0){
                        echo "<p>ERROR: No se pudo crear el usuario</p>";      
                    }else{
                        echo "<p>Alta de usuario realizada</p>";      
                    }
                }
                echo ' ar: '. $enlace->affected_rows.'<br>';
                    
                $this->crearSensorTienenRegistro($idCasa, $nickname);


                mysqli_close($enlace);
            }

            return $idCasa;
          
        }

        /**
         *  Para editar los valores de un usuario existente
         * 
         *  @access public
         *  @return boolean
         */
        function editarUsuario(String $nickname, String $password, String $localidad, String $nombCasa,  int $idCasa){
            $valores = array();
            ($nickname) ?  $valores['nickname']="nickname='".$nickname."'" : null;
            ($password) ?  $valores['password']="password='".$password."'" : null;
            ($localidad) ?  $valores['localidad']="localidad='".$localidad."'" : null;

            $datos=implode(',', $valores);

            $usuarioEditado = false;
            
            if(empty($nickname)){
                echo "ERROR: No se especifico el usuario para modificar los cambios";
                $usuarioEditado = false;
            }else{

                $query = "UPDATE usuario
                        SET $datos
                        WHERE nickname = '$nickname';"; 

                $res = $this->enlace->query($query);

                if($res === false) {
                    echo "SQL Error: " . mysqli_error($this->enlace);
                    $usuarioEditado = false;
                }else{
                    if( $this->enlace->affected_rows==0){
                        echo "\n<p>ERROR: No se ha editado el usuario</p>";   
                        $usuarioEditado = false;
                    }else{
                        echo "\n<p>Editado correctamente el usuario</p>";  
                        $usuarioEditado = true;
                    }
                }
            }

            if(!empty($nombCasa)){                
            
                $query = 'UPDATE casa
                            SET nombCasa = "'.$nombCasa.'"
                            WHERE nickname = "'.$idCasa.'";'; 
 
                $res = $this->enlace->query($query);

                if($res === false) {
                    echo "SQL Error: " . mysqli_error($this->enlace);
                    $usuarioEditado = false;
                }else{
                    if( $this->enlace->affected_rows==0){
                        echo "<p>\nERROR: No se ha editado el nombre de la casa</p>";   
                        $usuarioEditado = false;
                    }else{
                        echo "<p>\nEditado correctamente el nombre de la casa</p>";  
                        $usuarioEditado = true;
                    }
                }
            }
            

            mysqli_close($this->enlace);

            return $usuarioEditado;

        }

        /**
         *  Para borrar un usuario existente 
         * 
         *  @access public
         *  @return null
         */
        function borrarUsuario(String $nickname){
            $query = "DELETE FROM usuario WHERE id='$nickname'";

            $enlace = parent::conecta();
            $res = $enlace ->query($query);

            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if( $enlace->affected_rows==0){
                    echo "<p>ERROR: No se ha realizado la baja</p>";      
                }else{
                    echo "<p>Baja realizazada</p>";      
                }
            }

            mysqli_close($enlace);

        }

        /**
         *  Crear un registro en la tabla “Casa”
         * 
         *  @access public
         *  @return null
         */
        function crearCasa(){

            $query = "INSERT INTO casa (idCasa) VALUES(0)";
    
            $enlace = parent::conecta();
            $res = $enlace ->query($query);

            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    echo "<p>ERROR: No se ha creado la casa</p>";      
                }else{
                    echo "<p>Casa creada</p>";      
                }
            }
            echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);
            
           
        }

        /**
         *  Obtener nombre de la casa
         * 
         *  @access public
         *  @return array
         */
        function buscarNombreCasa(int $idCasa){
            $query = "SELECT nombCasa FROM casa WHERE idCasa = '".$idCasa."'  LIMIT 1";
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            $fila = mysqli_fetch_array($res);


            if($res === false) {
                //echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    //echo "<p>ERROR: No se ha encontrado el nombre de la casa</p>";      
                }else{
                    //echo "<p>Se ha encontrado el nombre de la casa</p>";      
                }
            }
            //echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);

            return $fila['nombCasa'];
        }

        /**
         *  Cambiar el nombre de la casa
         * 
         *  @access public
         *  @return null
         */
        function cambiarNombreCasa(String $nombCasa, int $idCasa){

            $query = "UPDATE casa SET nombCasa='".$nombCasa."' WHERE idCasa = '".$idCasa."'";
    
            $enlace = parent::conecta();
            $res = $enlace ->query($query);

            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    echo "<p>ERROR: No se ha creado la casa</p>";      
                }else{
                    echo "<p>Casa creada</p>";      
                }
            }
            echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);
            
           
        }

        /**
         * Obtener el ID de la casa de un usuario en concreto
         * 
         *  @access public
         *  @return int
         */
        function buscarIdCasaNickname(String $nickname){
            $query = "SELECT idCasa FROM usuario WHERE nickname =  '".$nickname."'  LIMIT 1";
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            $fila = mysqli_fetch_array($res);


            if($res === false) {
                //echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    //echo "<p>ERROR: No se ha encontrado el nombre de la casa</p>";      
                }else{
                    //echo "<p>Se ha encontrado el nombre de la casa</p>";      
                }
            }
            //echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);

            return $fila['idCasa'];
        }

        /**
         * Buscar si el id de la casa existe
         * 
         *  @access public
         *  @return boolean
         */
        function buscarIdCasa(int $idCasa){
            if($idCasa == "" || !is_numeric($idCasa)){
                echo "el id no es numerico o esta vacio";
                return false;
            }else{
                $query = 'SELECT * FROM casa WHERE idCasa = '.$idCasa.' LIMIT 1';
            
                $enlace = parent::conecta();
                $res = $enlace ->query($query);
    
                if($res === false) {
                    echo "SQL Error: " . mysqli_error($enlace);
                }else{
                    if($enlace->affected_rows == 0){
                        echo "<p>ERROR: No se ha encontrado el ID de la Casa</p>";      
                        return false;
                    }else{
                        echo "<p>Se ha encontrado la casa</p>";  
                        return true;
                    }
                }
                echo ' ar: '. $enlace->affected_rows.'<br>';
    
                mysqli_close($enlace);
                
                return true;
            }
            
        }

        /**
         * Obtener el último ID registrado de la tabla “Casa”
         * 
         *  @access public
         *  @return int
         */
        function buscarIdUltCasa(){
            $query = 'SELECT idCasa FROM casa ORDER BY idCasa DESC LIMIT 1';
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            $fila = mysqli_fetch_array($res);


            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    echo "<p>ERROR: No se ha encontrado ningún ID de la casa</p>";      
                }else{
                    echo "<p>Se ha encontrado el ultimo ID de la casa</p>";      
                }
            }
            echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);

            return $fila['idCasa'];
        }

        /**
         * Editar Localidad de un usuario
         * 
         *  @access public
         *  @return boolean
         */
        function editarLocalidad(String $nickname, String $localidad){
            $query = "UPDATE  usuario SET localidad = '".$localidad."' WHERE nickname = '".$nickname."'";
    
            $enlace = parent::conecta();
            $res = $enlace ->query($query);

            $localidadActualizada = false;

            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    echo "<p>ERROR: No se ha actualizado la localidad</p>";     
                    $localidadActualizada = false;
                }else{
                    echo "<p>Localidad actualizada</p>";  
                    $localidadActualizada = true;
                }
            }
            echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);

            return $localidadActualizada;
        }

        /**
         * Crear un registro con el valor del sensor y el nickname que ha realizado la petición
         * 
         *  @access public
         *  @return boolean
         */
        function crearRegistro(String $valor, String $nickname){
            ($valor == "NULL") ? $valor = 'NULL': $valor = $valor;        

            $query = "INSERT INTO registro (valor, nickname) VALUES('".$valor."', '".$nickname."')";
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);

            $registroCreado = false;

            if($res === false) {
                //echo "SQL Error: " . mysqli_error($enlace);
                $registroCreado = false;
            }else{
                if($enlace->affected_rows == 0){
                    //echo "<p>ERROR: No se ha creado el registro</p>";    
                    $registroCreado = false;
                }else{
                    //echo "<p>Registro creado</p>";    
                    $registroCreado = true;
                }
            }
            //echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);
            
            return $registroCreado;
        }

        /**
         * Recuperar el último ID de la tabla “Registro”
         * 
         *  @access public
         *  @return int
         */
        function obtenerUltRegistro(String $nickname){
           
            $query = "SELECT idRegistro FROM registro WHERE nickname = '".$nickname."' ORDER BY idRegistro DESC LIMIT 1";
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            $fila = mysqli_fetch_array($res);


            if($res === false) {
                //echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($res->num_rows == 0){
                    //echo "<p>ERROR: No se ha obtenido el último registro</p>";    
                }else{
                    //echo "<p>Registro obtenido</p>";   
                    $registro = $res->fetch_array(); 
                }
            }
            //echo ' ar: '. $res->num_rows.'<br>';



            mysqli_close($enlace);
            
            return $fila['idRegistro'];
        }

        /**
         * Modificar un valor de un registro
         * 
         *  @access public
         *  @return boolean
         */        
        function actualizarRegistro(int $idRegistro, String $valor){
            
            $query = "UPDATE registro SET valor = '".$valor."' WHERE idRegistro = '".$idRegistro."'";
    
            $enlace = parent::conecta();
            $res = $enlace ->query($query);

            $registroActualizado = false;

            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    echo "<p>ERROR: No se ha actualizado el registro</p>";     
                    $registroActualizado = false;
                }else{
                    echo "<p>Registro actualizado</p>";  
                    $registroActualizado = true;
                }
            }
            echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);

            return $registroActualizado;
        }

        /**
         * Ver todos los registros creado relacionados a una Casa
         * 
         *  @access public
         *  @return object
         */  
        function listarRegistrosCasa(int $idCasa){
            //Crear un objeto vacio
            $registrosCasa = new \stdClass;
            $datos = array();

            if(!empty($idCasa)){
                $query = "SELECT sensor.nombsensor, registro.fechaRegistro, registro.valor, registro.nickname
                            FROM registro
                            INNER JOIN tienen ON tienen.idRegistro = registro.idRegistro
                            INNER JOIN sensor ON tienen.idSensor = sensor.idSensor
                            INNER JOIN casa ON casa.idCasa = sensor.idCasa
                            WHERE casa.idCasa =$idCasa
                            ORDER BY fechaRegistro DESC";

                        
                if($res = parent::conecta()->query($query)) {   
                    while( $fila = mysqli_fetch_assoc($res) ){     
                        $datos[]  = $fila;       
                    }
                    $registrosCasa->data = $datos;   
                }else{
                    echo "No se han obtenido los registros de la casa";
                }

            }else{
                echo "ERROR: No se pudo obtener el listado de registros de la casa porque no se tiene el IdCasa";
            }

            return $registrosCasa;
            
            mysqli_close(parent::conecta());
            
        }

        /**
         * Permite añadir un sensor
         * 
         *  @access public
         *  @return boolean
         */  
        function crearSensor(String $nombSensor,int $idCasa){
           

            $query = "INSERT INTO sensor VALUES(0, '".$nombSensor."', '', '".$idCasa."')";
    
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            $sensorCreado = false;

            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
                $sensorCreado = false;
            }else{
                if($enlace->affected_rows == 0){
                    echo "<p>ERROR: No se ha creado el sensor</p>";   
                    $sensorCreado = false;   
                }else{
                    echo "<p>Sensor creado</p>";      
                    $sensorCreado = true;
                }
            }
            echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);
            return $sensorCreado;
        }

        /**
         * Buscar si el nombre del sensor de una casa coincide con con otro sensor
         * 
         *  @access public
         *  @return boolean
         */  
        function buscarExisteNombSensor(String $nombSensor, int $idCasa){
            $query = "SELECT idSensor FROM sensor WHERE nombSensor = '". $nombSensor. "' AND idCasa =  '". $idCasa. "' LIMIT 1";
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);

            $sensorExistente = true;
            if($res === false) {
                //echo "SQL Error: " . mysqli_error($enlace);
                $sensorExistente = false;
            }else{
                if($res->num_rows == 0){
                    //echo "<p>ERROR: No se ha encontrado ningún ID de la casa</p>"; 
                    $sensorExistente = false;     
                }else{
                    //echo "<p>Se ha encontrado el ultimo ID de la casa</p>";      
                    $sensorExistente = true;
                }
            }
            //echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);

            return $sensorExistente;
        }

        /**
        * Recuperar el IDa partir del nombre del sensor de una casa
        * 
        *  @access public
        *  @return int
        */  
        function buscarIdSensorNomb(String $nombSensor, int $idCasa){
            $query = "SELECT idSensor FROM sensor WHERE nombSensor = '". $nombSensor. "' AND idCasa =  '". $idCasa. "' LIMIT 1";
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            $fila = mysqli_fetch_array($res);


            if($res === false) {
                //echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    //echo "<p>ERROR: No se ha encontrado ningún ID de la casa</p>";      
                }else{
                    //echo "<p>Se ha encontrado el ultimo ID de la casa</p>";      
                }
            }
            //echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);

            return $fila['idSensor'];
        }

        /**
        *  Cambiar el nombre del sensor que aparece en la página Web
        * 
        *  @access public
        *  @return boolean
        */  
        function actualizarNombSensorWeb(String $nombSensor, String $valor, int $idCasa){
            
            $query = "UPDATE sensor SET nombSensorWeb = '".$valor."' WHERE idCasa = '".$idCasa."' AND  nombSensor = '".$nombSensor."' ";
    
            $enlace = parent::conecta();
            $res = $enlace ->query($query);

            $registroActualizado = false;

            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    echo "<p>ERROR: No se ha actualizado el nombre del sensor en la web</p>";     
                    $registroActualizado = false;
                }else{
                    echo "<p>Actualizado nombre del sensor en la web</p>";  
                    $registroActualizado = true;
                }
            }
            echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);

            return $registroActualizado;
        }

        /**
        *  Asociar el “idSensor” con el “idRegistro”
        * 
        *  @access public
        *  @return boolean
        */  
        function crearTienen(int $idSensor, int $idRegistro){        
            $query = "INSERT INTO tienen VALUES('".$idSensor."', '".$idRegistro."')";
    
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            $crearTienen = false;

            if($res === false) {
                //echo "SQL Error: " . mysqli_error($enlace);
                $crearTienen = false;
            }else{
                if($enlace->affected_rows == 0){
                    //echo "\n<p>ERROR: No se ha asignado el sensor a un registro</p>";   
                    $crearTienen = false;   
                }else{
                    //echo "\n<p>Sensor asignado al registro correctamente</p>";      
                    $crearTienen = true;
                }
            }
            //echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);
            return $crearTienen;
        }

        /**
        *  Se crean los sensores que tiene una casa por defecto, y se crea un
        * registro de la tabla “Registro” con un valor por defecto, y se asocian
        * los sensores con los registros
        * 
        *  @access public
        *  @return null
        */  
        function crearSensorTienenRegistro(int $idCasa, String $nickname){            
            //Al crear la casa se crearan y se asignaran los sensores y el registro, que permite tener un historial
            //de todos los eventos con los sensores 
            //Se inicializa, para que cuando se cree el usuario, tenga un valor inicial y no de errores al entrar 
            //a la página de inicio del panel

            $nombreSensores =  Array("arduino","ascensor", "ventilador", "puertaGaraje", "puertaPrincipal", "alarma", "luzPasilloPB", "luzPasilloPA",
            "luzCocina",  "luzSalon", "luzBanho", "luzHab1", "luzHab2", "luzInteriorGaraje", "luzVerdeGaraje", "luzRojaGaraje");

            for($i=0; $i < count($nombreSensores); $i++){
                echo "El sensor: ". $nombreSensores[$i]. " se ha creado";

                if($nombreSensores[$i] == "puertaGaraje" || $nombreSensores[$i] == "puertaPrincipal" ||  $nombreSensores[$i] == "alarma"){
                    $valorSensorDefecto = "E";
                }else{
                    $valorSensorDefecto = 0;
                }
                
                $this->crearRegistro($valorSensorDefecto, $nickname);
                $idRegistro = $this->obtenerUltRegistro($nickname);
                
                echo "\n ------------------------ \n";
                echo "\n El registro obtenido es: " .$idRegistro ."\n";
                $this->crearSensor($nombreSensores[$i], $idCasa);
                $idSensor = $this->buscarIdSensorNomb($nombreSensores[$i], $idCasa);
                echo "\n El idSensor recuperado es: " .$idSensor ."\n"; 
                $this->crearTienen($idSensor, $idRegistro);
            }
        }

        /**
        *  Crea un registro en la tabla “Registro”, y se asocia con el sensor.
        * 
        *  @access public
        *  @return null
        */  
        function crearTienenRegistro(String $nombSensor,String $valor,int $idCasa, String $nickname){            
            //Al crear la casa se crearan y se asignaran los sensores y el registro, que permite tener un historial
            //de todos los eventos con los sensores 
            //Se inicializa, para que cuando se cree el usuario, tenga un valor inicial y no de errores al entrar 
            //a la página de inicio del panel

            $this->crearRegistro($valor, $nickname);
            $idRegistro = $this->obtenerUltRegistro($nickname);
            
            //echo "\n ------------------------ \n";
            //echo "\n El registro obtenido es: " .$idRegistro ."\n";
            $idSensor = $this->buscarIdSensorNomb($nombSensor, $idCasa);
            //echo "\n El idSensor recuperado es: " .$idSensor ."\n"; 
            $this->crearTienen($idSensor, $idRegistro);
            
        }

        /**
        *  Se muestran todos los valores de los sensores de la casa
        * 
        *  @access public
        *  @return array
        */  
        function listarSensoresValoresPorCasa(int $idCasa){            
            $estadoSensores=array();

            if(!empty($idCasa)){
                $query = "SELECT idSensor
                        FROM sensor
                        WHERE idCasa = $idCasa";

                        
                if($res = parent::conecta()->query($query)) {   
                    while( $fila = mysqli_fetch_array($res) ){
                        $obtenerIdSensor = $fila['idSensor'];
                        $query2 = "SELECT * FROM sensor
                                    INNER JOIN tienen ON tienen.idSensor = sensor.idSensor 
                                    INNER JOIN registro ON registro.idRegistro = tienen.idRegistro 
                                    WHERE tienen.`idSensor` = '".$obtenerIdSensor."'
                                    ORDER BY tienen.idRegistro DESC 
                                    LIMIT 1";
                        if($res2 = parent::conecta()->query($query2)) {   
                            while( $fila2 = mysqli_fetch_array($res2) ){
                                $estadoSensores[$fila2['nombSensor']] = $fila2['valor'];
                            }         
                        }
                                        
                    }    
                }else{
                    echo "No se han obtenido los ids de los sensores de la casa";
                }

            }else{
                echo "ERROR: No se pueden listar el estado de los sensores porque no se tiene el IdCasa";
            }

            return $estadoSensores;
            
            mysqli_close(parent::conecta());
            
        }  

        /**
        *  Mostrar valores por nombre del sensor
        * 
        *  @access public
        *  @return array|null
        */  
        function listarValorSensorPorNombre(String $nombSensor, int $idCasa){
            if(!empty($idCasa)){
                $query = "SELECT valor FROM sensor
                            INNER JOIN tienen ON tienen.idSensor = sensor.idSensor 
                            INNER JOIN registro ON registro.idRegistro = tienen.idRegistro 
                            WHERE sensor.`nombSensor` = '".$nombSensor."' AND sensor.idCasa = '".$idCasa."'
                            ORDER BY tienen.idRegistro DESC 
                            LIMIT 1";

                        
                if($res = parent::conecta()->query($query)) {   
                    $fila = mysqli_fetch_array($res);
                    $valorSensor = $fila['valor'];
                    return $valorSensor;
                }else{
                    echo "No se han obtenido los valores de los sensores de la casa";
                }
            }
        }

        /**
        *  Mostrar los nombres de los sensores que aparecerán en la Página Web
        * 
        *  @access public
        *  @return array|null
        */  
        function listarNombresSensoresWeb(int $idCasa){
            $nombresSensoresWeb = Array();
            if(!empty($idCasa)){
                $query = "SELECT nombSensor, nombSensorWeb
                          FROM sensor
                          WHERE idCasa =".$idCasa."";

                        
                if($res = parent::conecta()->query($query)) {   
                    while($fila = mysqli_fetch_array($res)){
                        $nombresSensoresWeb[$fila['nombSensor']]=$fila['nombSensorWeb'];
                    }
                    return $nombresSensoresWeb;
                }else{
                    echo "No se han obtenido los valores de los nombres de los sensores de la casa";
                }
            }
        }

        /**
        *  Crear un tema para un usuario
        * 
        *  @access public
        *  @return boolean
        */  
        function crearTema(){
            $query = "INSERT INTO tema (idTema) VALUES(0)";
    
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            $crearTema = false;

            if($res === false) {
                //echo "SQL Error: " . mysqli_error($enlace);
                $crearTema = false;
            }else{
                if($enlace->affected_rows == 0){
                    //echo "\n<p>ERROR: No se ha creado el TEMA</p>";   
                    $crearTema = false;   
                }else{
                    //echo "\n<p>Tema Creado</p>";      
                    $crearTema = true;
                }
            }
            //echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);
            return $crearTema;
        }

        /**
        *  Coger el ID del último registro de la tabla “Tema”
        * 
        *  @access public
        *  @return array
        */  
        function obtenerUltIdTema(){
            $query = 'SELECT idTema FROM tema ORDER BY idTema DESC LIMIT 1';
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            $fila = mysqli_fetch_array($res);


            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    echo "<p>ERROR: No se ha encontrado ningún ID del tema</p>";      
                }else{
                    echo "<p>Se ha encontrado el ultimo ID del tema</p>";      
                }
            }
            echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);

            return $fila['idTema'];
        }

        /**
        *  Obtener el “idTema” de un usuario
        * 
        *  @access public
        *  @return int
        */  
        function obtenerIdTemaUsuario( String $nickname){
            $query = "SELECT idTema FROM usuario WHERE nickname = '".$nickname."'";
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            $fila = mysqli_fetch_array($res);


            if($res === false) {
                //echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    //echo "<p>ERROR: No se ha encontrado ningún ID del tema que pertenezca a este nickname pasado</p>";      
                }else{
                    //echo "<p>Se ha encontrado el  ID del tema  que pertenece a este nickname </p>";      
                }
            }
            //echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);

            return $fila['idTema'];
        }

        /**
        *  Cambiar los valores de un tema de un usuario
        * 
        *  @access public
        *  @return boolean
        */  
        function modificarTema(int $idTema, String $colorFondoPagPanel, String $colorFondoPanel, String $colorTitulosPanel, String $colorNombSensores, String $tamanoLetraTit, String $tamanoLetraNombSensores ){
            $valores = Array();
            ($colorFondoPagPanel) ?  $valores['colorFondoPagPanel']="colorFondoPagPanel='".$colorFondoPagPanel."'" : $valores['colorFondoPagPanel']="colorFondoPagPanel=NULL";
            ($colorFondoPanel) ?  $valores['colorFondoPanel']="colorFondoPanel='".$colorFondoPanel."'" : $valores['colorFondoPanel']="colorFondoPanel=NULL";
            ($colorTitulosPanel) ?  $valores['colorTitulosPanel']="colorTitulosPanel='".$colorTitulosPanel."'" : $valores['colorTitulosPanel']="colorTitulosPanel=NULL";
            ($colorNombSensores) ?  $valores['colorNombSensores']="colorNombSensores='".$colorNombSensores."'" : $valores['colorNombSensores']="colorNombSensores=NULL";
            ($tamanoLetraTit) ?  $valores['tamanoLetraTit']="tamanoLetraTit='".$tamanoLetraTit."'" : $valores['tamanoLetraTit']="tamanoLetraTit=NULL";
            ($tamanoLetraNombSensores) ?  $valores['tamanoLetraNombSensores']="tamanoLetraNombSensores='".$tamanoLetraNombSensores."'" : $valores['tamanoLetraNombSensores']="tamanoLetraNombSensores=NULL";
            
            $datos=implode(',', $valores);

            $query = "UPDATE tema
                        SET $datos
                        WHERE idTema = $idTema";

            $enlace = parent::conecta();
            $res = $enlace ->query($query);

            $registroActualizado = false;

            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
            }else{
                if($enlace->affected_rows == 0){
                    echo "<p>ERROR: No se ha modificado el tema del usuario</p>";     
                    $registroActualizado = false;
                }else{
                    echo "<p>Se ha modificado el tema del usuario</p>";  
                    $registroActualizado = true;
                }
            }
            echo ' ar: '. $enlace->affected_rows.'<br>';

            mysqli_close($enlace);

            return $registroActualizado;
        }

        /**
        *  Coger todos los datos de un tema
        * 
        *  @access public
        *  @return array|null
        */  
        function obtenerDatosTema(int $idTema){
            if(!empty($idTema)){
                $query = "SELECT *
                          FROM tema
                          WHERE idTema =".$idTema."";

                        
                if($res = parent::conecta()->query($query)) { 
                   $fila = mysqli_fetch_array($res);
                    return $fila;
                }else{
                    echo "No se han obtenido los valores del tema";
                }
            }
        }

        /**
        *   Coger todos los registros de la temperatura y de la humedad comprendido entre 2 fechas
        * 
        *  @access public
        *  @return array|null
        */  
        function obtenerRegistrosTempHum(int $idCasa, String $nombSensor,String $fechaInicio, String $fechaFin){
            $registros = Array();
            if(!empty($idCasa)){
                $query="SELECT * 
                    FROM `tienen` 
                    INNER JOIN sensor on sensor.idSensor = tienen.idSensor
                    INNER JOIN registro on registro.idRegistro = tienen.idRegistro
                    WHERE nombSensor = '".$nombSensor."' AND sensor.idCasa = $idCasa AND (fechaRegistro BETWEEN '".$fechaInicio."' AND '".$fechaFin."')
                    ORDER BY `registro`.`fechaRegistro` ASC";
                
                        
                if($res = parent::conecta()->query($query)) {   
                    while($fila = mysqli_fetch_array($res)){
                        $registros[] = array('idRegistro'=>$fila['idRegistro'], "fechaRegistro" => $fila['fechaRegistro'], "valor"=>$fila['valor']);
                    }
                    return $registros;
                }else{
                    echo "No se han obtenido los registros del sensor TemperaturaHumedad para las gráficas";
                }
            }
        }

        /**
        *   Obtener la temperatura o la humedad mínima entre 2 fechas
        * 
        *  @access public
        *  @return int|null
        */  
        function tempHumMinFecha(int $idCasa, String $nombSensor, String $fechaInicio,String $fechaFin){
 
            if(!empty($idCasa)){
                $query="SELECT * 
                    FROM `tienen` 
                    INNER JOIN sensor on sensor.idSensor = tienen.idSensor
                    INNER JOIN registro on registro.idRegistro = tienen.idRegistro
                    WHERE nombSensor = '".$nombSensor."' AND sensor.idCasa = $idCasa AND (fechaRegistro BETWEEN '".$fechaInicio."' AND '".$fechaFin."')
                    ORDER BY CAST(`valor` AS UNSIGNED) ASC LIMIT 1";
                
                        
                if($res = parent::conecta()->query($query)) {  
                    $fila = mysqli_fetch_array($res); 
                    return (isset($fila['valor'])) ? $fila['valor']: null;
                }else{
                    echo "No se han obtenido el valor mínimo del sensor TemperaturaHumedad para las gráficas";
                }
            }
        }

        /**
        *    Obtener la temperatura o la humedad máxima entre 2 fechas
        * 
        *  @access public
        *  @return array|null
        */  
        function tempHumMaxFecha(int $idCasa,String $nombSensor,String $fechaInicio,String $fechaFin){
 
            if(!empty($idCasa)){
                $query="SELECT * 
                    FROM `tienen` 
                    INNER JOIN sensor on sensor.idSensor = tienen.idSensor
                    INNER JOIN registro on registro.idRegistro = tienen.idRegistro
                    WHERE nombSensor = '".$nombSensor."' AND sensor.idCasa = $idCasa AND (fechaRegistro BETWEEN '".$fechaInicio."' AND '".$fechaFin."')
                    ORDER BY CAST(`valor` AS UNSIGNED) DESC LIMIT 1";
                
                        
                if($res = parent::conecta()->query($query)) {  
                    $fila = mysqli_fetch_array($res); 
                    return (isset($fila['valor'])) ? $fila['valor']: array();
                }else{
                    echo "No se han obtenido el valor máximo del sensor TemperaturaHumedad para las gráficas";
                }
            }
        }

        
    }


?>