<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ecoconecta");
if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}
if(!isset($_SESSION["usuario_id"])){
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $conn->real_escape_string($_POST["titulo"]);
    $descripcion = $conn->real_escape_string($_POST["descripcion"]);
    $fecha = $_POST["fecha"];
    $plazas = (int)$_POST["plazas"];
    $usuario_id = $_SESSION["usuario_id"];

    $imagenRuta = null;
    if(isset($_FILES["imagen"]) && $_FILES["imagen"]["error"]===UPLOAD_ERR_OK){
        $nombreArchivo = time()."_".basename($_FILES["imagen"]["name"]);
        $rutaDestino = "uploads/".$nombreArchivo;

        if(move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)){
            $imagenRuta=$rutaDestino;
        }
    }

    $stmt = $conn->prepare("INSERT INTO talleres (titulo, descripcion, fecha, plazas, creador_id, imagen) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $titulo, $descripcion, $fecha, $plazas, $usuario_id, $imagenRuta);
    $stmt->execute();
    $stmt->close();

    header("Location: talleres.php");
    exit();

    if($stmt->execute()){
        $mensaje = "Taller creado con exito.";
    } else {
        $mensaje = "Error al crear el taller.";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <title>Crear Taller - EcoConecta</title>
    <link rel="stylesheet" href="index.css">
    <style>
        .imagen-preview{
            max-width:300px;
            max-height:200px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <main>
        <h1>Crear Nuevo Taller</h1>
        <?php if(isset($mensaje)):?>
            <p style="color: green"><?htmlspecialchars($mensaje)?></p>
        <?php endif; ?>
        <form action="crear_taller.php" method="post" enctype="multipart/form-data">
            <label for="titulo">Titulo:</label>
            <input type="text" name="titulo" id="titulo" required>

            <label for="descripcion">Descripcion:</label>
            <textarea name="descripcion" id="descripcion" required></textarea>

            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" required>

            <label for="plazas">Número de plazas:</label>
            <input type="number" name="plazas" min="1" required>

            <label for="imagen">Imagen: </label>
            <input type="file" name="imagen" accept="image/*">
            <img id="imagen-preview" class="imagen-preview" alt="Vista previa">

            <button type="submit">Crear Taller</button>
        </form>
    </main>

    <script>
        document.getElementById('imagen-input').addEventListener('change', function(e){
            const[file] = e.target.files;
            const preview = document.getElementById('imagen-preview');
            if(file){
                preview.src=URL.createObjectURL(file);
                preview.style.display='block';
            } else{
                preview.style.display = 'none';
            }
        })
        </script>
</body>
