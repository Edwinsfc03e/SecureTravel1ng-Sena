<?php
session_start(); // Iniciar sesión
// Verificar si el usuario ya está autenticado
if (isset($_SESSION['usuario_id'])) {
    // Redirigir a la página de bienvenida o la específica para administradores
    if ($_SESSION['es_admin']) {
        header("Location: moduloAdmin.php");
    } else {
        header("Location: moduloCliente.php");
    }
    exit();
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// Tu código de conexión a la base de datos
$servername = "localhost";
$username = "san";
$password = "san";
$dbname = "ofertasDB";
// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, 3307);
// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Verificar si se envió el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correoOcedula = $_POST["correoOcedula"];
    $contrasena = $_POST["contrasena"];
    // Validar y verificar las credenciales utilizando consultas preparadas
    $stmt = $conn->prepare("SELECT id, nombre, es_admin FROM usuarios WHERE (correo = ? OR cedula = ?) AND contrasena = ?");
    $stmt->bind_param("sss", $correoOcedula, $correoOcedula, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Almacenar la información del usuario en la sesión
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['nombre_usuario'] = $row['nombre'];
        $_SESSION['es_admin'] = $row['es_admin'];
        // Redirigir a la página correspondiente
        if ($_SESSION['es_admin']) {
            header("Location: moduloAdmin.php");
        } else {
            header("Location: moduloCliente.php");
        }
        exit();
    } else {
        $mensaje_error = "Credenciales incorrectas. Inténtalo de nuevo.";
    }
    // Cerrar la consulta preparada
    $stmt->close();
}
// Cierra la conexión a la base de datos
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - URjourney</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Registrate | URJourney</title>
    <link rel="icon" href="ADMIN/Logo URJounger morado.png">
    <link rel="stylesheet" href="css/registro.css">
</head>
<body>
    <?php
    if (isset($mensaje_error)) {
        echo "<p style='color: red;'>$mensaje_error</p>";
    }
    ?>
    <div class="container" id="container">
    <div class="social-icons">
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
    <center><h1>Iniciar Sesión</h1></center><br>
    <center><a href="#" class="icon">
    <i class="fa-brands fa-google-plus-g" style="color: #5500ff;"></i></a>
    <a href="#" class="icon"><i class="fa-brands fa-facebook" style="color: #5500ff;"></i></a>
    <a href="#" class="icon"><i class="fa-brands fa-apple" style="color: #5500ff;"></i></a></center>
    </div>
    <center><span>Tambien puedes puede usar tu email para Registrate</span><br></center><br>
    <center><span>Puedes registrarte <a href="registro.php">dando click aqui!</a></span><br></center><br>
        <center><input type="text" name="correoOcedula" placeholder="Correo o cedula" required><br><br>
        <input type="password" name="contrasena" placeholder="Contraseña" required><br><br>
        <input type="submit" value="Iniciar Sesión"></center>
    <center><span><a href="recuperarClave.php">Olvidaste tu contraseña?</a></span><br></center><br>
    </div>
    </div>
    </form>
<script src="js/registro.js"></script>
</body>
</html>
