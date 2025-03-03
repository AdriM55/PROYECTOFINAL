<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso Legal</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header>
        <div class="nav">
            <a href="index.php">Inicio</a>
        </div>
    </header>

    <main>
        <h1>Aviso Legal</h1>

        <h2>1. Información General</h2>
        <p>En cumplimiento con la normativa vigente, informamos que el sitio web <strong>Cine Kursaal</strong> es gestionado por:</p>
        <ul>
            <li><strong>Nombre de la empresa:</strong> Cine Kursaal S.L.</li>
            <li><strong>Dirección:</strong> Av. Virgen de Europa, 4, 11202 Algeciras, Cádiz</li>
            <li><strong>Correo electrónico:</strong> contacto@cinekursaal.com</li>
        </ul>

        <h2>2. Propiedad Intelectual</h2>
        <p>Todos los contenidos de este sitio web (textos, imágenes, logotipos, etc.) están protegidos por derechos de autor y no pueden ser utilizados sin autorización previa.</p>

        <h2>3. Responsabilidad</h2>
        <p>No nos hacemos responsables de posibles daños derivados del uso de la información contenida en este sitio web.</p>

        <h2>4. Enlaces Externos</h2>
        <p>Este sitio web puede incluir enlaces a páginas de terceros. No nos responsabilizamos del contenido de dichas páginas.</p>

        <h2>5. Legislación Aplicable</h2>
        <p>Este aviso legal se rige por la legislación vigente en España. Cualquier disputa será sometida a los tribunales de la ciudad de [Tu Ciudad].</p>
    </main>

    <!-- 🔹 PIE DE PÁGINA -->
    <footer class="piepagina">
    <p>&copy; <?php echo date("Y"); ?> Cine Kursaal. Todos los derechos reservados.</p>
    <p>
        <a href="politica_privacidad.php">Política de Privacidad</a> |
        <a href="aviso_legal.php">Aviso Legal</a>
    </p>
</footer>

<style>
    .piepagina {
    background-color: #333;
    color: #fff;
    padding: 20px 0;
    text-align: center;
    width: 100%;
    position: fixed;
    bottom: 0;
    left: 0;
}

ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}


</style>
</body>
</html>
