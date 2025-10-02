<?php
session_start();

//Redirigir si no hay sesión activa
if(!isset($_SESSION["usuario"])){
    header("Location: login.php");
    exit();
}

//Guardar cambios de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['avatar']['name'])){
        $ruta="uploads/".basename($_FILES['avatar']['name']);
        move_uploaded_file($_FILES['avatar']['tmp_name'], $ruta);
        $_SESSION['avatar']=$ruta;
    }
    if (!empty($_POST['descripcion'])){
        $_SESSION['descripcion']=$_POST['descripcion'];
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de <?=htmlspecialchars($_SESSION['usuario'])?></title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h2>Perfil de <?=htmlspecialchars($_SESSION['usuario'])?></h2>
        <img src="<?=$_SESSION['avatar'] ?? 'imagenes/avatar.png'?>" alt="Avatar" class="avatar-large">

        <form method="post" enctype="multipart/form-data">
            <label for="avatar">Cambiar avatar:</label>
            <input type="file" name="avatar" id="avatar" accept="image/*">

            <label for="descripcion">Descripción: </label>
            <textarea name="descripcion" id="descripcion" rows="4"><?=$_SESSION['descripcion'] ?? ''?></textarea>

            <button type="submit">Guardar cambios</button>
        </form>
    </main>
</body>
</html>