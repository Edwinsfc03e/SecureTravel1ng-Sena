<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nombre']) && isset($_POST['apellido'])) {
        // Asignar los valores a variables
        $idRolPorDefecto = 2;
        $nombres = $_POST['nombre'];
        $apellidos = $_POST['apellido'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $numdocumento = $_POST['numdocumento'];
        $fechaexpedicion = $_POST['fechaexpedicion'];
        $fechanacimiento = $_POST['fechanacimiento'];
        $direccion = $_POST['direccion'];
        $numtelefono = $_POST['numtelefono'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "SecureTravel1ng_Base";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "INSERT INTO Usuarios (idrol, nombres, apellidos, correoelectronico, contraseña, numerodocumento, fechaexpediciondocumento, fechanacimiento, direccionresidencia, numerotelefono)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("isssssssss", $idRolPorDefecto, $nombres, $apellidos, $email, $password, $numdocumento, $fechaexpedicion, $fechanacimiento, $direccion, $numtelefono);

        if ($stmt->execute()) {
            echo "Usuario registrado correctamente.";
        } else {
            echo "Error al crear el registro: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Los campos de nombres y apellidos no están definidos en el formulario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="nombres">Nombres:</label>
        <input type="text" id="nombres" name="nombre" required><br><br>

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellido" required><br><br>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="numdocumento">Número de Documento:</label>
        <input type="text" id="numdocumento" name="numdocumento" required><br><br>

        <label for="fechaexpedicion">Fecha de Expedición del Documento:</label>
        <input type="date" id="fechaexpedicion" name="fechaexpedicion" required><br><br>

        <label for="fechanacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fechanacimiento" name="fechanacimiento" required><br><br>

        <label for="direccion">Dirección de Residencia:</label>
        <input type="text" id="direccion" name="direccion" required><br><br>

        <label for="numtelefono">Número de Teléfono:</label>
        <input type="tel" id="numtelefono" name="numtelefono" required><br><br>

        <input type="submit" value="Registrar">
    </form>
</body>
</html>
