<?php


class Registration
{

    private $db_connection = null;
    public $errors = array();
    public $messages = array();


    public function __construct()
    {
        if (isset($_POST["register"])) {
            $this->registerNewUser();
        }
    }


    private function registerNewUser()
    {
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Uusario vacio";
        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $this->errors[] = "Password vacio";
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $this->errors[] = "Los passwords no son iguales";
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $this->errors[] = "Password tiene que tener minimo 6 caracteres";
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $this->errors[] = "Nombre de usuario tiene que tener de 2 a 64 caracteres";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $this->errors[] = "Nombre de usuario no es valido";
        } elseif (empty($_POST['user_email'])) {
            $this->errors[] = "Email vacio";
        } elseif (strlen($_POST['user_email']) > 64) {
            $this->errors[] = "Email tiene que ser menor de 64 caracteres";
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "El email no tiene el formato correcto";
        } elseif (!empty($_POST['user_name'])
            && strlen($_POST['user_name']) <= 64
            && strlen($_POST['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
            && !empty($_POST['user_email'])
            && strlen($_POST['user_email']) <= 64
            && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['user_password_new'])
            && !empty($_POST['user_password_repeat'])
            && ($_POST['user_password_new'] === $_POST['user_password_repeat'])
        ) {
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            if (!$this->db_connection->connect_errno) {

                $user_name = $this->db_connection->real_escape_string(strip_tags($_POST['user_name'], ENT_QUOTES));
                $user_email = $this->db_connection->real_escape_string(strip_tags($_POST['user_email'], ENT_QUOTES));

                $user_password = $_POST['user_password_new'];

                $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

                $sql = "SELECT * FROM users WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_email . "';";
                $query_check_user_name = $this->db_connection->query($sql);

                if ($query_check_user_name->num_rows == 1) {
                    $this->errors[] = "Sorry, that username / email address is already taken.";
                } else {
                    $sql = "INSERT INTO users (user_name, user_password_hash, user_email)
                            VALUES('" . $user_name . "', '" . $user_password_hash . "', '" . $user_email . "');";
                    $query_new_user_insert = $this->db_connection->query($sql);

                    if ($query_new_user_insert) {
                        $this->messages[] = "Tu cuenta ha sido creada, ya puedes iniciar sesion";
                    } else {
                        $this->errors[] = "Lo sentimos, registro fallido";
                    }
                }
            } else {
                $this->errors[] = "Lo sentimos, fallo de conexion con la base de datos";
            }
        } else {
            $this->errors[] = "Error desconocido";
        }
    }
}
