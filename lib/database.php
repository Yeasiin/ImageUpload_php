<?php
    //database
    class database{
        public $host = DB_HOST;
        public $db = DB_NAME;
        public $user = DB_USER;
        public $pass = DB_PASS;

        public $link;
        public $error;
        
        function __construct(){
            $this->connectDB();
        }

        private function connectDB(){
            $this->link = new mysqli($this->host, $this->user , $this->pass, $this->db);
        
            if(!$this->link){
                $this->error = "Connecton fail".$this->link->connect_error;
            }
        }

        // Insert Data

        public function insert($data){
            $query = $this->link->query($data) or die($this->link->error.__LINE__);
            
            if($query){
                return $query;
            }else{
                return false;
            }

        }

        // Delete Data
        public function select($data){
            $result = $this->link->query($data) or die($this->link->error.__LINE__);
            
            if($result->num_rows > 0){
                return $result;
            }else{
                return false;
            }

        }

        // Delete Data
        public function delete($data){
            $result = $this->link->query($data) or die($this->link->error.__LINE__);

            if($result){
                return $result;
            }else{
                return false;
            }

        }



    }

?>