<?php
//conexion a la base de datos
$conn = new mysqli("localhost", "root", "", "ecoconecta");
if($conn->connect_error){
    die("Error de conexion: " . $conn->connect_error);
}

session_start();
if(!isset($_SESSION["usuario"])){
    header("Location: login.php");
    exit();
}
//Insertar nuevo post si se envió el formulario
// Nuevo post
if(isset($_POST["nuevo_post"])) {
    $autor = $conn->real_escape_string($_POST['autor']);
    $contenido = $conn->real_escape_string($_POST['mensaje']);
    $conn->query("INSERT INTO posts (autor, mensaje) VALUES ('$autor', '$contenido')");
    header("Location: foro.php");
    exit();
}

//Nueva respuesta
if(isset($_POST["nueva_respuesta"])){
    $post_id=(int)$_POST['post_id'];
    $autor=$conn->real_escape_string($_POST['autor']);
    $contenido=$conn->real_escape_string($_POST['mensaje']);
    $conn->query("INSERT INTO respuestas (post_id, autor, mensaje) VALUES ($post_id, '$autor', '$contenido')");
    header("Location: foro.php");
    exit();
}
//Obtener todos los posts
$result = $conn->query("SELECT * FROM posts ORDER BY fecha DESC");


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro de Discusión - EcoConecta</title>
    <link rel="stylesheet" href="index.css">
    <style>
        .foro-container{max-width: 800px; margin: auto; text-align: left;}
        .post{border: 1px solid #ccc; padding: 10px; margin-bottom: 15px; background: #fff; border-radius: 8px;}
        form{margin-top: 20px; display: flex; flex-direction: column;}
        input, textarea{margin-bottom: 10px; padding: 10px; font-size: 16px;}
        button{background: rgb(94,129, 81); color: white; padding: 10px; border: none; cursor: ponter; border-radius: 5px;}
        button:hover{background: rgb(107, 79, 42);}
    </style>
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <main>
        <h1>Foro EcoConecta</h1>

        <button onclick="abrirModal()" style="margin:10px 0; padding:10px 15px; background:#5e8151; border: none; border-radius: 6px; cursor:pointer;">
            ➕ Nuevo mensaje
        </button>

        <!--Modal oculto por defecto-->
        <div id="modalNuevoPost" class="modal">
            <div class="model-content">
                <span class="cerrar" onclick="cerrarModal()">&times;</span>
                <h2>Publicar nuevo mensaje</h2>
                <form method="post">
                    <input type="text" name="autor" placeholder="Nombre" required>
                    <textarea name="mensaje" placeholder="Escribe tu mensaje" required></textarea>
                    <button type="submit" name="nuevo_post">Publicar</button>
                </form>
            </div>
        </div>

        <div class="foro-container">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="post">
            <!-- Post principal -->
            <p><strong><?=htmlspecialchars($row["autor"])?></strong> (<?=htmlspecialchars($row["fecha"])?>)</p>
            <p><?=nl2br(htmlspecialchars($row["mensaje"]))?></p>

            <!-- Respuestas debajo del post -->
            <div class="respuestas" style="margin-left: 20px; border-left: 2px solid #ccc; padding-left: 10px; margin-top: 10px;">
                <?php
                $post_id = $row['id'];
                $respuestas = $conn->query("SELECT * FROM respuestas WHERE post_id=$post_id ORDER BY fecha ASC");
                if($respuestas->num_rows > 0):
                    while($resp = $respuestas->fetch_assoc()):
                ?>
                        <div class="respuesta" style="margin-bottom: 10px; background:#f9f9f9; padding:8px; border-radius:6px;">
                            <p><strong><?=htmlspecialchars($resp["autor"])?></strong> (<?=htmlspecialchars($resp["fecha"])?>)</p>
                            <p><?=nl2br(htmlspecialchars($resp["mensaje"]))?></p>
                        </div>
                <?php 
                    endwhile;
                else:
                    echo "<p style='color:#666;'>No hay respuestas aún.</p>";
                endif;
                ?>
            </div>

            <!-- Formulario de respuesta -->
            <form method="post" style="margin-top: 10px;">
                <input type="hidden" name="post_id" value="<?=$row['id']?>">
                <input type="text" name="autor" placeholder="Tu nombre" required>
                <textarea name="mensaje" placeholder="Escribe una respuesta" required></textarea>
                <button type="submit" name="nueva_respuesta">Responder</button>
            </form>
        </div>
    <?php endwhile; ?>

    <!-- Formulario para nuevo post 
    <h2>Nuevo mensaje</h2>
    <form method="post">
        <input type="text" name="autor" placeholder="Nombre" required>
        <textarea name="mensaje" placeholder="Mensaje" required></textarea>
        <button type="submit" name="nuevo_post">Publicar</button>
    </form> -->
</div>
            
    </main>

    <button class="btn-flotante" onclick="abrirModal()">➕</button>

    <!--Script para que el boton de abrir modal se vea solo al scrollear-->
    <script>
        window.addEventListener("scroll", function(){
            const boton=document.querySelector(".btn-flotante");
            if(window.scrollY>200){
                boton.style.display="block";
            }else{
                boton.style.display="none";
            }
        });
    </script>

    <script src="app.js"></script>
    <script>
        function abrirModal(){
            document.getElementById("modalNuevoPost").style.display = "flex";
        }

        function cerrarModal(){
            document.getElementById("modalNuevoPost").style.display="none";
        }

        //Cierra el modal si se clica fuera
        window.onclick=function(event){
            var modal = document.getElementById("modalNuevoPost");
            if(event.target==modal){
                modal.style.display="none";
            }
        }
    </script>
</body>
</html>