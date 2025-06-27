<?php
// Inicialização da sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusão de dependências
require_once 'includes/conexao.php';
require_once 'includes/funcoesAPI.php';

// Configurações
const API_KEY = 'b224536c0803f50498a7118ee808a91e';

// Busca de dados
$filmes = buscarFilmes(API_KEY);
$logado = isset($_SESSION['usuario_id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spider Movies - Filmes Marvel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <img src="assets/img/marvel.svg" alt="Marvel Logo" class="logo">
            <nav class="nav-links">
                <?php if ($logado): ?>
                    <a href="favoritos.php">Meus Favoritos</a>
                    <a href="logout.php">Sair</a>
                <?php else: ?>
                    <a href="login.php" class="hero-btn primary-btn">Entrar</a>
                    <a href="registrar.php" class="hero-btn primary-btn">Registrar</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Descubra o Universo Marvel</h1>
            <p>Explore uma coleção incrível de filmes do Universo Cinematográfico Marvel. 
               Encontre seus heróis favoritos, assista trailers e mantenha seus filmes preferidos sempre à mão.</p>
            <div class="hero-buttons">
                <?php if (!$logado): ?>
                    <a href="registrar.php" class="hero-btn primary-btn">Começar Agora</a>
                    <a href="login.php" class="hero-btn secondary-btn">Já tenho conta</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="multiverso-destaque">
        <div class="multiverso-container">
            <div class="multiverso-titulo">
                <h2>Destaques <br/>do Multiverso</h2>
            </div>
            <div class="multiverso-descricao">
                <p>
                    Explore os filmes que expandem o universo Marvel! Descubra histórias épicas, personagens icônicos e conexões entre realidades alternativas.
                </p>
            </div>
        </div>
        <div class="multiverso-cards">
          <div class="swiper">
            <div class="swiper-wrapper">
              <?php foreach ($filmes as $filme): ?>
                <div class="swiper-slide">
                  <article class="card-simple">
                    <a href="detalhes.php?id=<?= htmlspecialchars($filme['id']) ?>" class="card-link">
                      <img src="https://image.tmdb.org/t/p/w500<?= htmlspecialchars($filme['poster_path']) ?>" 
                           alt="<?= htmlspecialchars($filme['title']) ?>">
                      <div class="card-overlay">
                        <h3><?= htmlspecialchars($filme['title']) ?></h3>
                      </div>
                    </a>
                  </article>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <div class="noticias">
            <div class="noticias-container">
          <div class="noticias-titulo">
            <h2>Ultimas Notícias</h2>
            <p>
              Fique por dentro das novidades do MCU em tempo real! Novos filmes, segredos de bastidores e teorias incríveis te esperam.
            </p>
          </div>
          <div class="noticias-container">
            <div class="noticia-card">
              <img src="assets/img/noticia1.png" alt="Notícia 1">
              <div class="noticia-overlay">
                <span class="noticia-tag">Novidade!</span>
                <div class="noticia-desc">Exclusivo: Sadie Sink Pode Ser a Próxima Gwen Stacy em Homem-Aranha 4!</div>
              </div>
            </div>
            <div class="noticia-card">
              <img src="assets/img/noticia2.png" alt="Notícia 2">
              <div class="noticia-overlay">
                <span class="noticia-tag">Spoilerzinho</span>
                <div class="noticia-desc">Os 10 maiores plot twists do Universo Cinematográfico Marvel</div>
              </div>
            </div>
            <div class="noticia-card">
              <img src="assets/img/noticia3.png" alt="Notícia 3">
              <div class="noticia-overlay">
                <span class="noticia-tag">Notícia</span>
                <div class="noticia-desc">Marvel adia Vingadores: Doomsday e Guerras Secretas - Veja as datas novas</div>
              </div>
            </div>
          </div>
          </div>
        </div>

        <div class="team-spoilers">
            <div class="team-spoilers-container">
                <div class="team-spoilers-content">
                    <div class="team-spoilers-texto">
                        <h2>Você é do time spoiler ou<br>anti-spoiler?</h2>
                        <p>Seja qual for o seu lado, aqui você encontra sua galera.<br>
                        Escolha seu time e mergulhe nessa experiência feita pra você.</p>
                    </div>
                    <div class="team-spoilers-botoes">
                        <button class="btn-spoiler">Spoiler</button>
                        <button class="btn-antispoiler">Anti-<br>Spoiler</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
      var swiper = new Swiper('.swiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        breakpoints: {
          600: { slidesPerView: 2 },
          900: { slidesPerView: 3 },
          1200: { slidesPerView: 4 }
        }
      });
    </script>
</body>
</html>
