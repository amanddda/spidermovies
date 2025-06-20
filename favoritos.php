<?php
include('includes/conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    die('Acesso não autorizado. <a href="login.php">Faça login</a>');
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT f.id_filme, f.titulo, f.poster, f.sinopse
        FROM favoritos fav
        JOIN filmes f ON fav.id_filme = f.id_filme
        WHERE fav.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Meus Favoritos</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h1>Filmes Favoritos</h1>
  <p><a href="index.php">Voltar para os filmes</a> | <a href="logout.php">Sair</a></p>
  <div class="container">
    <?php while ($filme = $resultado->fetch_assoc()): ?>
      <div class="card">
        <img src="https://image.tmdb.org/t/p/w500<?= $filme['poster'] ?>" alt="<?= $filme['titulo'] ?>">
        <h3><?= $filme['titulo'] ?></h3>
        <p><?= $filme['sinopse'] ?></p>
        <form action="api/remover_favorito.php" method="post">
          <input type="hidden" name="id_filme" value="<?= $filme['id_filme'] ?>">
          <button type="submit">Remover</button>
        </form>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>
