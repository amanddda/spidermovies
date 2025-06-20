<?php
include('../includes/conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    die('Não autorizado.');
}

$id_filme = $_POST['id_filme'];
$usuario_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("DELETE FROM favoritos WHERE usuario_id = ? AND id_filme = ?");
$stmt->bind_param("ii", $usuario_id, $id_filme);
$stmt->execute();

header("Location: ../favoritos.php");
exit;
?>
