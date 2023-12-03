<?php
// Iniciar la sesión
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "SecureTravel1ng_Base";

    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para verificar las credenciales del usuario
    $sql = "SELECT idusuario, correoelectronico, contraseña FROM Usuarios WHERE correoelectronico = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['contraseña'])) {
            // Inicio de sesión exitoso, almacenar datos en la sesión
            $_SESSION['idusuario'] = $row['idusuario'];
            $_SESSION['email'] = $row['correoelectronico'];
            header("Location: pagina_principal.php"); // Redireccionar a la página principal
            exit();
        } else {
            echo "Credenciales inválidas. Por favor, inténtalo de nuevo.";
        }
    } else {
        echo "El usuario no existe. Por favor, regístrate.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555555;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #ffffff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form action="login.php" method="post">
        <h2>Iniciar sesión</h2>
 
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Iniciar sesión">
    </form>
</body>
</html>
