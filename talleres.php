<?php
session_start();
$conn=new mysqli("localhost", "root", "", "ecoconecta");
if($conn->connect_error){die("Error: ".$conn->connect_error);}

if(!isset($_SESSION["usuario"])){
    header("Location: login.php");
    exit();
}

//Asegurarse de que el usuario_id está disponible
if (!isset($_SESSION["usuario_id"])){
    header("Location: login.php");
    exit();
}

$usuario_id=$_SESSION["usuario_id"];

//obtener lista de talleres
$talleres=$conn->query("SELECT*FROM talleres ORDER BY fecha ASC");

//hacer reserva
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $taller_id = (int)$_POST["taller_id"];

    //Comprobar si el usuario ya tiene una reserva para este taller
    $check_reserva=$conn->prepare("SELECT id FROM reservas WHERE usuario_id=? AND taller_id=?");
    $check_reserva->bind_param("ii", $usuario_id, $taller_id);
    $check_reserva->execute();
    $result_reserva=$check_reserva->get_result();

    if($result_reserva->num_rows > 0){
        $mensaje = "Ya tienes una reserva para este taller.";
    } else {
            //comprobar si quedan plazas
    $check=$conn->prepare("SELECT plazas FROM talleres WHERE id=?");
    $check->bind_param("i", $taller_id);
    $check->execute();
    $result=$check->get_result();
    $row=$result->fetch_assoc();
    }



    if($row && $row["plazas"]>0){
        //insertar reserva
        $stmt=$conn->prepare("INSERT INTO reservas (usuario_id, taller_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $usuario_id, $taller_id);

        if($stmt->execute()){
        //restar una plaza
        $conn->query("UPDATE talleres SET plazas=plazas-1 WHERE id=$taller_id");
        $mensaje = "Reserva realizada con éxito.";
        header("Location: talleres.php");
        exit();
    }else{
            $mensaje = "Error al realizar la reserva.";
        }
    }else{
        $mensaje = "Lo sentimos, no quedan plazas disponibles.";
    }
}

$result=$conn->query("SELECT*FROM talleres");

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Talleres - EcoConecta</title>
        <link rel="stylesheet" href="index.css">
        <style>
            .clima-box{font-size: 14px; margin-top: 8px; color: #333;}
            </style>
    </head>
    <body>
        <header>
           <?php include 'header.php'; ?>
           <a href="crear_taller.php"><button>Crear Nuevo Taller</button></a>
        </header>
        <main>
            <h1>Talleres disponibles</h1>
            <?php if(isset($mensaje)) echo "<p style='color:red'>$mensaje</p>"; ?>
            <div class="talleres-container">
    <?php while($row=$talleres->fetch_assoc()): ?>
    <div class="taller">
        <h3><?= htmlspecialchars($row["titulo"]) ?></h3>

        <?php if(!empty($row['imagen'])): ?> <!-- CAMBIO: $row -->
            <img src="<?= htmlspecialchars($row['imagen']) ?>"
                 alt="Imagen del taller"
                 class="taller-imagen">
        <?php else: ?>
            <div class="taller-imagen-placeholder">Sin Imagen</div>
        <?php endif; ?>

        <p><?= htmlspecialchars($row["descripcion"]) ?></p>
        <p>Plazas disponibles: <?= htmlspecialchars($row["plazas"]) ?></p>
        <p>Fecha: <?= htmlspecialchars(date("d/m/Y H:i", strtotime($row["fecha"]))) ?></p>

        <div class="clima-box" 
        id="clima-<?=$row["id"]?>"
        data-fecha="<?=htmlspecialchars($row["fecha"]) ?>">
        Cargando clima...</div>

        <?php if($row["plazas"]>0): ?> <!-- CAMBIO: $row -->
            <p class="plazas">Plazas disponibles: <?= $row['plazas'] ?></p>
            <form method="post">
                <input type="hidden" name="taller_id" value="<?= $row["id"] ?>">
                <button type="submit">Reservar plaza</button>
            </form>
        <?php else: ?>
            <p style="color:red">No quedan plazas disponibles</p>
        <?php endif; ?>
    </div>
<?php endwhile; ?>
            </div>

        </main>

        <script>
            const apiKey = "d582d3a9f124b9fb55013581764f82df";
            const ciudad = "Jerez de la Frontera,ES"; //Añadir ciudad a la tabla talleres para hacerlo dinámico

            

            //buscar los divs con clase clima-box
            document.querySelectorAll("[id^='clima-']").forEach(div=>{
                const fechaTaller = new Date(div.dataset.fecha);

                fetch(`https://api.openweathermap.org/data/2.5/forecast?q=${ciudad}&units=metric&lang=es&appid=${apiKey}`)
                .then(res=>{
                    if(!res.ok)throw new Error("Error HTTP" + res.status);
                    return res.json();
                })
                .then(data=>{
                    //Encontrar pronostico más cercano a la fecha del taller
                    let mejorPronostico = data.list.reduce((prev,curr)=>{
                        const fechaPrev = new Date(prev.dt_txt);
                        const fechaCurr = new Date(curr.dt_txt);
                        const diffPrev = Math.abs(fechaPrev - fechaTaller);
                        const diffCurr = Math.abs(fechaCurr - fechaTaller);
                        return diffCurr < diffPrev ? curr : prev;
                    });

                    const descripcion = mejorPronostico.weather[0].description;
                    const temp = mejorPronostico.main.temp;
                    const icon = mejorPronostico.weather[0].icon;
                    const iconUrl = `https://openweathermap.org/img/wn/${icon}@2x.png`;
                    /*const descripcion = data.weather[0].description;
                    const temp = data.main.temp;
                    const icon = data.weather[0].icon;
                    const iconUrl=`https://openweathermap.org/img/wn/${icon}@2x.png`;
*/
                div.innerHTML=`
                <img src="${iconUrl}" alt="${descripcion}" style="vertical-align:middle;">
                🌡${temp}ºC - ${descripcion}
                `;
            })
            .catch(err=>{
                console.error("Error cargando clima:", err);
                div.innerHTML= "No se pudo cargar el clima.";
            });
        });
        </script>
    </body>
    </html>