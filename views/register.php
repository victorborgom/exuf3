<?php
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo $error;
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo $message;
        }
    }
}
?>

<form method="post" action="register.php" name="registerform">

    <h1>Formulario de Registro</h1>
    <br><br>
    <label for="login_input_username">Nombre Usuario</label>
    <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
    <label for="login_input_email">Email</label>
    <input id="login_input_email" class="login_input" type="email" name="user_email" required />
    <label for="login_input_password_new">Password</label>
    <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
    <label for="login_input_password_repeat">Repetir Password</label>
    <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
    <input type="submit"  name="register" value="Registrar" />

</form>

<a href="index.php">Volver</a>
