<?php
session_start();
?>
<nav>
                <div class="nav-logo">
                    <a href="index.php"><img src="imagenes\logo.png" alt="logo EcoConecta" class="logo"></a>
                </div>

                <ul class="nav-links">
                    <li><a href=index.php>Inicio</a></li>
                    <li><a href=foro.php>Foro</a></li>
                    <li><a href=talleres.php>Talleres</a></li>
                    <li><a href=contacto.php>Contacto</a></li>
                    <li><a href=faq.php>Preguntas frecuentes</a></li>
                </ul>

                <div class="nav-user">
                    <?php if(isset($_SESSION['usuario'])):?>
                        <span class="user-info">
                            <img src="<?= $_SESSION['avatar'] ?? 'imagenes/*.png' ?>"
                            alt="Usuario avatar" class="avatar">
                            <span class="username"><?=htmlspecialchars($_SESSION['usuario'])?></span>
                            <a href="perfil.php">Perfil</a>
                            <a href=logout.php class="logout">Cerrar sesión</a>
                        </span>
                        <?php else: ?>
                            <a href="login.php" class="login">Iniciar sesión</a>
                            <?php endif; ?>
                </div>
            </nav>