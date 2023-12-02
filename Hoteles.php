<?php
session_start();
$servername = "localhost";
$username = "san";
$password = "san";
$dbname = "ofertasDB";
// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Incluir la configuración y funciones de conexión
function obtenerHotelesDisponibles($conn) {
    $hoteles = array();
    $sql = "SELECT * FROM hoteles";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $hoteles[] = $row;
        }
    }
    return $hoteles;
}
// Agregar al carrito
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregarCarrito"])) {
    $hotelId = $_POST["hotelId"];
    // Obtener detalles del hotel
    $sql_select_hotel = "SELECT * FROM hoteles WHERE id = $hotelId";
    $result_hotel = $conn->query($sql_select_hotel);
    if ($result_hotel->num_rows > 0) {
        $hotel = $result_hotel->fetch_assoc();
        // Agregar el hotel al carrito
        $_SESSION["carrito"][$hotelId] = [
            "titulo" => $hotel["titulo"], 
            "ciudad" => $hotel["ciudad"],
            "pais" => $hotel["pais"],
            "estrellas" => $hotel["estrellas"],
            "precio" => $hotel["precio"],
            "descripcion" => $hotel["descripcion"],
        ];
    }
}
// Obtener la lista de hoteles disponibles
$hotelesDisponibles = obtenerHotelesDisponibles($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoteles Disponibles</title>
    <link rel="stylesheet" href="hotel.css">
</head>

<body>
    <div class="banner">   
        <h1>Hoteles disponibles</h1>
        <div class="opc">
            <a href="verCarrito.php">Tu carro de compras</a>
            <a href="moduloCliente.php">Regresar al menú principal</a>
        </div>    
    </div>
    <br>
    <?php foreach ($hotelesDisponibles as $hotel) : ?>
    <div class="box1">
        <div class="text1"><strong><?php echo $hotel['titulo'];?></strong></div>
        <div><?php echo $hotel['ciudad']; ?></div>
        <div class="price" ><strong><?php echo $hotel['precio']; ?></strong></div>
        <div class="description" ><?php echo $hotel['descripcion']; ?></div>
        <div class="botn" >
        <form method="post">
        <input type="hidden" name="hotelId" value="<?php echo $hotel['id']; ?>">
        <input type="submit" name="agregarCarrito" value="Agregar al Carrito">
            </form>
        </div>
    </div>
<?php endforeach; ?>

    <br>
</body>

</html>

