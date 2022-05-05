<?php
    class Conexion 
    {
        private $db_host;       
        private $db_user; 
        private $db_passwd;
        private $db_name;       

    
        function __construct($db_host, $db_user, $db_passwd, $db_name)
        {
            $this->db_host = $db_host;
            $this->db_user = $db_user;
            $this->db_passwd = $db_passwd;
            $this->db_name = $db_name;

        }


        function conecta(){
            $enlace = mysqli_connect($this->db_host, $this->db_user, $this->db_passwd, $this->db_name);
            if ($enlace->connect_errno) {
                echo 'Connect Error: ' . $enlace->connect_errno;        
                die();
            }
            return $enlace;
        }

        function __getDb_host(){
            return  $this->db_host;
        }

        function __getdDb_user(){
            return  $this->db_user;
        }

        function __getDb_passwd(){
            return  $this->db_passwd;
        }

        function __getDb_name(){
            return  $this->db_name;
        }

        function __setDb_host($db_host){
            $this->db_host = $db_host;
        }

        function __setdDb_user($db_user){
            $this->db_user = $db_user;
        }

        function __setDb_passwd($db_passwd){
            $this->db_passwd = $db_passwd;
        }

        function __setDb_name($db_name){
            $this->db_name = $db_name;
        }


        function __toString()
        {
            return "host: " .  $this->db_host . " usuario: " .  $this->db_user . " contraseña: " .  $this->db_passwd . " nombre bd: " .  $this->db_name;
        }

        
    }


?>