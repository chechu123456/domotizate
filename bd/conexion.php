<?php
    namespace bd;

    /**
    *
    * PHP: conexion.php
    * BASE DE DATOS: k204701_domotizate
    * @author Sergio <sergyrp98@gmail.com>
    */

    class Conexion 
    {
        /**
         * Nombre del host / Dominio
         * @access private
         * @var string
         */
        private $db_host; 
        
        /**
         * Nombre del usuario con acceso a la bd
         * @access private
         * @var string
         */
        private $db_user; 

        /**
         * Contraseña del usuario con acceso a la bd
         * @access private
         * @var string
         */
        private $db_passwd;

        /**
         * Nombre de la bd
         * @access private
         * @var string
         */
        private $db_name;       

        /**
         * Constructor - Mandar parámetros de conexión a la bd
         * @param string db_host   nombre del host / dominio
         * @param string db_user   nombre del usuario con acceso a la bd
         * @param string db_passwd contraseña del usuario con acceso a la bd
         * @param string db_name   nombre de la base de datos
         * @return null 
         * 
        */

        function __construct(String $db_host, String $db_user, String $db_passwd, String $db_name)
        {
            $this->db_host = $db_host;
            $this->db_user = $db_user;
            $this->db_passwd = $db_passwd;
            $this->db_name = $db_name;

        }

        /**
         * Crear conexion a la base de datos
         * 
         * @access public
         * @return object conexion a la base de datos
         * 
         */
        function conecta(){
            $enlace = mysqli_connect($this->db_host, $this->db_user, $this->db_passwd, $this->db_name);
            if ($enlace->connect_errno) {
                echo 'Connect Error: ' . $enlace->connect_errno;        
                die();
            }
            return $enlace;
        }
        
        /**
         * Obtener nombre  del host / dominio
         * @return string
         * 
        */
        function __getDb_host(){
            return  $this->db_host;
        }

        /**
         * Obtener nombre de usuario
         * @return string
         * 
        */
        function __getdDb_user(){
            return  $this->db_user;
        }

        /**
         * Obtener contraseña
         * @return string
         * 
        */
        function __getDb_passwd(){
            return  $this->db_passwd;
        }

        /**
         * Obtener nombre de la base de datos
         * @return string
         * 
        */
        function __getDb_name(){
            return  $this->db_name;
        }

        /**
         * Establecer nombre del host / dominio
         * @param db_host
         * @return null
         * 
        */
        function __setDb_host($db_host){
            $this->db_host = $db_host;
        }

        /**
         * Establecer nombre de usuario
         * @param db_user
         * @return null
         * 
        */
        function __setdDb_user($db_user){
            $this->db_user = $db_user;
        }

        /**
         * Establecer contraseña del usuario de la base de datos
         * @param db_passwd
         * @return null
         * 
        */
        function __setDb_passwd($db_passwd){
            $this->db_passwd = $db_passwd;
        }

        /**
         * Establecer nombre  de la base de datos
         * @param db_name
         * @return null
         * 
        */
        function __setDb_name($db_name){
            $this->db_name = $db_name;
        }

        /**
         * Mostrar todos los datos de la conexión
         * @return string
         * 
        */
        function __toString()
        {
            return "host: " .  $this->db_host . " usuario: " .  $this->db_user . " contraseña: " .  $this->db_passwd . " nombre bd: " .  $this->db_name;
        }

        
    }


?>