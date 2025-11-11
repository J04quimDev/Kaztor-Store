<?php
include "PHP/protect.php";
include "PHP/Login.php";

if (isset($_SESSION['LoginClienteID'])) {
    $sql = "SELECT * FROM logincliente WHERE LoginClienteID = " . $_SESSION['LoginClienteID'];
    $resultado = mysqli_query($conn, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) { 
        $tipoUser = mysqli_fetch_assoc($resultado);

        if ($tipoUser['tipoID'] != 2) {
            header("Location: index.php");
            exit;
        }
    } else {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
