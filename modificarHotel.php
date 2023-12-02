<?php
// Iniciar sesión y verificar si el usuario es administradoraa
session_start();
if (!isset($_SESSION['usuario_id']) || !($_SESSION['es_admin'] || $_SESSION['idAdmin'] != null)) {
    header("Location: login.php");
    exit();
}
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
// Funciones para manejar la lógica de agregar, eliminar y modificar hoteles
function agregarHotel($titulo, $ciudad, $pais, $estrellas, $precio, $descripcion) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO hoteles (titulo, ciudad, pais, estrellas, precio, descripcion) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssds", $titulo, $ciudad, $pais, $estrellas, $precio, $descripcion);
    $stmt->execute();
    $stmt->close();
}
function eliminarHotel($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM hoteles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
function modificarHotel($id, $titulo, $ciudad, $pais, $estrellas, $precio, $descripcion) {
    global $conn;
    $stmt = $conn->prepare("UPDATE hoteles SET titulo=?, ciudad=?, pais=?, estrellas=?, precio=?, descripcion=? WHERE id=?");
    $stmt->bind_param("ssssdsi", $titulo, $ciudad, $pais, $estrellas, $precio, $descripcion, $id);
    $stmt->execute();
    $stmt->close();
}
// Manejar solicitudes de agregar, eliminar o modificar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["agregar"])) {
        // Verificar si los índices están definidos antes de usarlos
        $titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : "";
        $ciudad = isset($_POST["ciudad"]) ? $_POST["ciudad"] : "";
        $pais = isset($_POST["pais"]) ? $_POST["pais"] : "";
        $estrellas = isset($_POST["estrellas"]) ? $_POST["estrellas"] : "";
        $precio = isset($_POST["precio"]) ? $_POST["precio"] : "";
        $descripcion = isset($_POST["descripcion"]) ? $_POST["descripcion"] : "";
        agregarHotel($titulo, $ciudad, $pais, $estrellas, $precio, $descripcion);
    } elseif (isset($_POST["eliminar"])) {
        eliminarHotel($_POST["eliminar"]);
    } elseif (isset($_POST["modificar"])) {
        // Verificar si los índices están definidos antes de usarlos
        $id = isset($_POST["modificar"]) ? $_POST["modificar"] : "";
        $titulo = isset($_POST["titulo_modificar"]) ? $_POST["titulo_modificar"] : "";
        $ciudad = isset($_POST["ciudad_modificar"]) ? $_POST["ciudad_modificar"] : "";
        $pais = isset($_POST["pais_modificar"]) ? $_POST["pais_modificar"] : "";
        $estrellas = isset($_POST["estrellas_modificar"]) ? $_POST["estrellas_modificar"] : "";
        $precio = isset($_POST["precio_modificar"]) ? $_POST["precio_modificar"] : "";
        $descripcion = isset($_POST["descripcion_modificar"]) ? $_POST["descripcion_modificar"] : "";
        modificarHotel($id, $titulo, $ciudad, $pais, $estrellas, $precio, $descripcion);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Hoteles</title>
</head>
<body>
    <div class="banner">
        <table width="100%">
            <tr>
                <td width="50%" align="left">
                    <h1>Administrar hoteles</h1>
                </td>
                <td width="50%" align="right">
                    <a href="moduloAdmin.php">Pagina principal</a>
                </td>
            </tr>
        </table>
    </div>
    <!-- Formulario para agregar un nuevo hotel -->
    <form method="post">
        <h2>Agregar Hotel</h2>
        <input type="text" name="titulo" placeholder="Nombre" required><br>
        <input type="text" name="ciudad" placeholder="Ciudad" required><br>
        <input type="text" name="pais" placeholder="País" required><br>
        <input type="number" name="estrellas" min="1" max="5" placeholder="Estrellas" required><br>
        <input type="number" name="precio" min="0" placeholder="Precio" required><br>
        <textarea name="descripcion" placeholder="Descripción" required></textarea><br>
        <input type="submit" name="agregar" value="Agregar Hotel">
    </form>
    <hr>
    <!-- Lista de hoteles existentes -->
    <h2>Hoteles Existentes</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Ciudad</th>
            <th>País</th>
            <th>Estrellas</th>
            <th>Precio</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        <?php
        // Consultar hoteles existentes
        $sql = "SELECT * FROM hoteles";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['titulo']}</td>";
                echo "<td>{$row['ciudad']}</td>";
                echo "<td>{$row['pais']}</td>";
                echo "<td>{$row['estrellas']}</td>";
                echo "<td>{$row['precio']}</td>";
                echo "<td>{$row['descripcion']}</td>";
                echo "<td>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='eliminar' value='{$row['id']}'>";
                echo "<input type='submit' value='Eliminar'>";
                echo "</form>";
                echo "<form method='post'>";
                // Agregar campos existentes al formulario de modificación
                echo "<input type='hidden' name='modificar' value='{$row['id']}'>";
                echo "<input type='text' name='titulo_modificar' value='{$row['titulo']}' placeholder='Nombre' required>";
                echo "<input type='text' name='ciudad_modificar' value='{$row['ciudad']}' placeholder='Ciudad' required>";
                echo "<input type='text' name='pais_modificar' value='{$row['pais']}' placeholder='País' required>";
                echo "<input type='number' name='estrellas_modificar' value='{$row['estrellas']}' min='1' max='5' placeholder='Estrellas' required>";
                echo "<input type='number' name='precio_modificar' value='{$row['precio']}' min='0' placeholder='Precio' required>";
                echo "<textarea name='descripcion_modificar' placeholder='Descripción' required>{$row['descripcion']}</textarea>";
                echo "<input type='submit' value='Modificar'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No hay hoteles registrados.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
