<?php
// Variables para controlar el resultado del formulario
$mensaje = "";
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $id = $_POST['id'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    if (filter_var($address, FILTER_VALIDATE_EMAIL) && strlen($password) >= 8) {
        $conn = new mysqli('localhost', 'root', 'root', 'midblogin', '3306');
        if ($conn->connect_error) {
            $error = true;
            $mensaje = "Error de conexión a la base de datos: " . $conn->connect_error;
        } else {
            $stmt = $conn->prepare("INSERT INTO usuario (ID, username, contraseña, email, personid) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $id, $username, $password, $address, $phone);
            if ($stmt->execute()) {
                $mensaje = "Registro exitoso";
            } else {
                $error = true;
                $mensaje = "Error al ejecutar la consulta: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    } else {
        $error = true;
        $mensaje = "Datos inválidos";
    }
}
?>

<html>
<head>
    <title>My first PHP Website</title>
</head>

<body>
    <h2>Página de Autenticación</h2>

    <?php if ($error) : ?>
        <p style="color: red;"><?php echo $mensaje; ?></p>
    <?php elseif ($mensaje !== "") : ?>
        <p style="color: green;"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="POST">
        Ingrese cedula: <input type="number" name="id" required="required" /> <br />
        Ingrese Usuario: <input type="text" name="username" required="required" /> <br />
        Ingrese Contraseña: <input type="password" name="password" required="required" /> <br />
        Ingrese Email: <input type="email" name="address" required="required" /> <br />
        Ingrese Teléfono: <input type="number" name="phone" required="required" /> <br />
        <input type="submit" value="Autenticarse" />
    </form>
</body>

</html>