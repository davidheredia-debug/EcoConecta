<?php
$conn=new mysqli("localhost","root","","ecoconecta");
if($conn->connect_error){
    die("Error de conexion: " . $conn->connect_error);
}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $nombre=$conn->real_escape_string($_POST["nombre"]);
    $email=$conn->real_escape_string($_POST["email"]);
    $password=password_hash($_POST["password"],PASSWORD_DEFAULT);

    $sql="INSERT INTO usuarios (nombre, email, password) VALUES('$nombre', '$email', '$password')";
    if($conn->query($sql)){
        header("Location: login.php");
        exit;
    } else{
        echo "Error: ". $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <metacharset="UTF-8">
    <title>Registro-Ecoconecta</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <main>
        <h1>Registro de usuario</h1>
        <form method="post">
      <input type="text" name="nombre" placeholder="Nombre" required>
      <input type="email" name="email" placeholder="Correo" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <button type="submit">Registrarse</button>
  </form>
    </main>
</body>
</html>