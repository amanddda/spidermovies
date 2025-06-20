<?php
include('includes/conexao.php');

if (!isset($_GET['id'])) {
    die('Filme não encontrado.');
}

$id = intval($_GET['id']);
$apiKey = 'b224536c0803f50498a7118ee808a91e'; // Substitua pela sua chave da TMDB


// teste de comit

// Buscar dados do filme
$filme_json = file_get_contents("https://api.themoviedb.org/3/movie/$id?api_key=$apiKey&language=pt-BR");
$filme = json_decode($filme_json, true);

// Buscar trailer
$trailer_json = file_get_contents("https://api.themoviedb.org/3/movie/$id/videos?api_key=$apiKey&language=pt-BR");
$trailers = json_decode($trailer_json, true);
$video = '';
foreach ($trailers['results'] as $t) {
    if ($t['type'] === 'Trailer' && $t['site'] === 'YouTube') {
        $video = $t['key'];
        break;
    }
}

$logado = isset($_SESSION['usuario_id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?= $filme['title'] ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h1><?= $filme['title'] ?></h1>
  <p><a href="index.php">Voltar</a></p>
  <div class="container">
    <div class="card" style="width: 100%; max-width: 700px; margin: 0 auto;">
      <img src="https://image.tmdb.org/t/p/w500<?= $filme['poster_path'] ?>" alt="<?= $filme['title'] ?>">
      <h3><?= $filme['title'] ?></h3>
      <p><strong>Data de Lançamento:</strong> <?= $filme['release_date'] ?></p>
      <p><?= $filme['overview'] ?></p>

      <?php if ($video): ?>
        <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?= $video ?>?autoplay=0" frameborder="0" allowfullscreen></iframe>
      <?php endif; ?>

      <?php if ($logado): ?>
        <form action="api/favoritar.php" method="post">
          <input type="hidden" name="id_filme" value="<?= $filme['id'] ?>">
          <input type="hidden" name="titulo" value="<?= $filme['title'] ?>">
          <input type="hidden" name="poster" value="<?= $filme['poster_path'] ?>">
          <input type="hidden" name="sinopse" value="<?= $filme['overview'] ?>">
          <button type="submit">Favoritar</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
