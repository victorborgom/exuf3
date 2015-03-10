<?php


class Listar_usuarios
{

    private $db_connection = null;
    public $errors = array();
    public $messages = array();


    public function __construct()
    {
        if (isset($_POST["listar"])) {
            $this->listar();
        }
    }


    private function listar()
    {
 
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            if (!$this->db_connection->connect_errno) {



                $sql = "SELECT * FROM users";
                $query_check_user_name = $this->db_connection->query($sql);
                
                $this->messages[] = $sql;


            
        } 
    }
}
