<?php
session_start();



$conn=new mysqli("localhost","root","","ecoconecta");
if($conn->connect_error){die("Error de conexion: ".$conn->connect_error);}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $email=$conn->real_escape_string($_POST["email"]);
    $password=$_POST["password"];

    $sql="SELECT * FROM usuarios WHERE email='$email'";
    $result=$conn->query($sql);

    if($result->num_rows > 0){
        $usuario=$result->fetch_assoc();
        if(password_verify($password, $usuario["password"])){
            $_SESSION["usuario"]=$usuario["nombre"];
            $_SESSION["usuario_id"]=$usuario["id"];

        //Volver a la página guardada antes de iniciar sesión
            header("Location: index.php");
            exit();
        } else{
            echo "Contraseña incorrecta.";
        }
    }
}
    ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Login - EcoConecta</title>
        <link rel="stylesheet" href="index.css">
    </head>
    <body>
    <main>
        <h1>Iniciar Sesión</h1>
        <form method="post">
            <input type="email" name="email" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" requirded>
            <button type="submit">Entrar</button>
            <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </form>
    </main>
    </body>