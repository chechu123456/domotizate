<?php 
    require("conexion.php");
    class Listado extends Conexion{

        private $idCasa;
        private $nickname;

        function __construct($db_host, $db_user, $db_passwd, $db_name)
        {
            parent::__construct($db_host, $db_user, $db_passwd, $db_name);
        }

        function __setIdCasa($idCasa){
            $this->idCasa = $idCasa;
        }

        function __getIdCasa(){
            return $this->idCasa;
        }

        function __setNickname($nickname){
            $this->nickname = $nickname;
        }

        function __getNickname(){
            return $this->nickname;
        }

        function buscarUsuario($nickname){
            $query = 'SELECT nickname FROM usuario WHERE nickname = "'.$nickname.'" LIMIT 1';
            
            $enlace = parent::conecta();
            $res = $enlace ->query($query);
            
            $usuarioEncontrado = false;

            if($res === false) {
                echo "SQL Error: " . mysqli_error($enlace);
                $usuarioEncontrado = false;
            }else{
                if($res->num_rows == 0){
                    echo "<p>\n No se ha encontrado el usuario</p>";      
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

        function recuperarDatosUsuario($nickname){
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


        function comprobarUsuarioContrasena($nickname, $password){
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

        function crearUsuario($nickname, $password, $localidad, $nombCasa, $idCasa){
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
        
                    if($res === false) {
                        echo "SQL Error: " . mysqli_error($enlace);
                        $this->buscarIdUltCasa();
                        
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

        function editarUsuario($nickname, $password, $localidad, $nombCasa,  $idCasa){
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

        function borrarUsuario($nickname){
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

        function buscarNombreCasa($idCasa){
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

        
        function cambiarNombreCasa($nombCasa, $idCasa){

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

        function buscarIdCasaNickname($nickname){
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


        function buscarIdCasa($idCasa){
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

        function editarLocalidad($nickname, $localidad){
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

        function crearRegistro($valor, $nickname){
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

        function obtenerUltRegistro($nickname){
           
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

        function actualizarRegistro($idRegistro, $valor){
            
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

        function listarRegistrosCasa($idCasa){
            $registrosCasa = new stdClass();
            $datos = array();
            $array = array();

            if(!empty($idCasa)){
                $query = "SELECT registro.idRegistro, registro.fechaRegistro, registro.valor, registro.nickname
                            FROM registro
                            INNER JOIN tienen ON tienen.idRegistro = registro.idRegistro
                            INNER JOIN sensor ON tienen.idSensor = sensor.idSensor
                            INNER JOIN casa ON sensor.idCasa = sensor.idCasa
                            WHERE casa.idCasa =$idCasa";

                        
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


        function crearSensor($nombSensor, $idCasa){
           

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

        function buscarExisteNombSensor($nombSensor, $idCasa){
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

        function buscarIdSensorNomb($nombSensor, $idCasa){
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

        function actualizarNombSensorWeb($nombSensor, $valor, $idCasa){
            
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


        function crearTienen($idSensor, $idRegistro){        
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

        function crearSensorTienenRegistro($idCasa, $nickname){            
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

        function crearTienenRegistro($nombSensor, $valor, $idCasa, $nickname){            
            //Al crear la casa se crearan y se asignaran los sensores y el registro, que permite tener un historial
            //de todos los eventos con los sensores 
            //Se inicializa, para que cuando se cree el usuario, tenga un valor inicial y no de errores al entrar 
            //a la página de inicio del panel

            $this->crearRegistro($valor, $nickname);
            $idRegistro = $this->obtenerUltRegistro($nickname);
            
            //echo "\n ------------------------ \n";
            ///echo "\n El registro obtenido es: " .$idRegistro ."\n";
            $idSensor = $this->buscarIdSensorNomb($nombSensor, $idCasa);
            //echo "\n El idSensor recuperado es: " .$idSensor ."\n"; 
            $this->crearTienen($idSensor, $idRegistro);
            
        }


        function listarSensoresValoresPorCasa($idCasa){            
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

        function listarValorSensorPorNombre($nombSensor, $idCasa){
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

        function listarNombresSensoresWeb($idCasa){
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

        function obtenerIdTemaUsuario($nickname){
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

        function modificarTema($idTema, $colorFondoPagPanel, $colorFondoPanel, $colorTitulosPanel, $colorNombSensores,  $tamanoLetraTit, $tamanoLetraNombSensores ){
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

        function obtenerDatosTema($idTema){
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

        function obtenerRegistrosTempHum($idCasa, $nombSensor, $fechaInicio, $fechaFin){
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

        function tempHumMinFecha($idCasa, $nombSensor, $fechaInicio, $fechaFin){
 
            if(!empty($idCasa)){
                $query="SELECT * 
                    FROM `tienen` 
                    INNER JOIN sensor on sensor.idSensor = tienen.idSensor
                    INNER JOIN registro on registro.idRegistro = tienen.idRegistro
                    WHERE nombSensor = '".$nombSensor."' AND sensor.idCasa = $idCasa AND (fechaRegistro BETWEEN '".$fechaInicio."' AND '".$fechaFin."')
                    ORDER BY `registro`.`valor` ASC LIMIT 1";
                
                        
                if($res = parent::conecta()->query($query)) {  
                    $fila = mysqli_fetch_array($res); 
                    return (isset($fila['valor'])) ? $fila['valor']: array();
                }else{
                    echo "No se han obtenido el valor mínimo del sensor TemperaturaHumedad para las gráficas";
                }
            }
        }

        function tempHumMaxFecha($idCasa, $nombSensor, $fechaInicio, $fechaFin){
 
            if(!empty($idCasa)){
                $query="SELECT * 
                    FROM `tienen` 
                    INNER JOIN sensor on sensor.idSensor = tienen.idSensor
                    INNER JOIN registro on registro.idRegistro = tienen.idRegistro
                    WHERE nombSensor = '".$nombSensor."' AND sensor.idCasa = $idCasa AND (fechaRegistro BETWEEN '".$fechaInicio."' AND '".$fechaFin."')
                    ORDER BY `registro`.`valor` DESC LIMIT 1";
                
                        
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