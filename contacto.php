<!DOCTYPE html>
<html dir="ltr" lang="es">
    <head>
        <meta charset="utf-8">
        <title>Contacto - EcoConecta</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="index.css">
    </head>
    <body>
        <header>
          <?php include 'header.php'; ?>
        </header>
    <main>
        <h2>Contacto</h2>
        <p>Si tienes alguna pregunta, sugerencia o deseas ponerte en contacto con nosotros, no dudes en contactarnos</p>

        <div class="contact-container">
            <form action="#" method="post" class="contact-form">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="nombre" required>

                <label for="email">Correo:</label>
                <input type="email" id="email" name="correo" required>

                <label for="message">Mensaje:</label>
                <textarea id="message" name="mensaje" rows="5" required></textarea>

                <button type="submit">Enviar</button>
            </form>

            <!-- Caja con datos contacto -->
            <div class="contact-info">
                <h3>Información de Contacto</h3>
                <p><strong>Dirección:</strong> Calle Verde 123, Ciudad Sostenible</p>
                <p><strong>Teléfono:</strong> +34 123 456 789</p>
                <p><strong>Email:ecoconecta@arbolverde.com</strong>
                </div>
                </div>
    </main>
    <script src="app.js"></script>
    </body>
</html>