<?php
// Inicialização da sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('includes/conexao.php');

if (!isset($_GET['id'])) {
    die('Filme não encontrado.');
}

$id = intval($_GET['id']);
$apiKey = 'b224536c0803f50498a7118ee808a91e';

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

// Buscar elenco
$elenco_json = file_get_contents("https://api.themoviedb.org/3/movie/$id/credits?api_key=$apiKey&language=pt-BR");
$elenco_data = json_decode($elenco_json, true);
$elenco = array_slice($elenco_data['cast'], 0, 6); // Primeiros 6 atores

// Verificar se o filme já está nos favoritos
$jaFavoritado = false;
$logado = isset($_SESSION['usuario_id']);

if ($logado) {
    $usuario_id = $_SESSION['usuario_id'];
    $stmt = $conn->prepare("SELECT 1 FROM favoritos WHERE usuario_id = ? AND id_filme = ?");
    $stmt->bind_param("ii", $usuario_id, $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $jaFavoritado = $resultado->num_rows > 0;
}

// Formatar data
$dataLancamento = '';
if ($filme['release_date']) {
    $data = DateTime::createFromFormat('Y-m-d', $filme['release_date']);
    $dataLancamento = $data->format('d/m/Y');
}

// Formatar duração
$duracao = '';
if ($filme['runtime']) {
    $horas = intval($filme['runtime'] / 60);
    $minutos = $filme['runtime'] % 60;
    $duracao = $horas . 'h ' . $minutos . 'min';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($filme['title']) ?> - Spider Movies</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-red: #f0141e;
            --dark-red: #d0121a;
            --bg-dark: #0a0a0a;
            --bg-card: #1a1a1a;
            --text-light: #ffffff;
            --text-gray: #cccccc;
            --text-muted: #888888;
            --gradient-dark: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: var(--bg-dark);
            color: var(--text-light);
            line-height: 1.6;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            color: var(--primary-red);
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary-red);
        }

        .hero-section {
            position: relative;
            height: 70vh;
            display: flex;
            align-items: center;
            overflow: hidden;
            margin-top: 80px;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('https://image.tmdb.org/t/p/w1280<?= htmlspecialchars($filme['backdrop_path']) ?>');
            background-size: cover;
            background-position: center;
            filter: brightness(0.3);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 3rem;
            align-items: center;
        }

        .poster-container {
            position: relative;
        }

        .poster {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s;
        }

        .poster:hover {
            transform: scale(1.05);
        }

        .movie-info h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .movie-meta {
            display: flex;
            gap: 2rem;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            color: var(--text-gray);
        }

        .rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(240, 20, 30, 0.2);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            border: 1px solid var(--primary-red);
        }

        .star {
            color: #ffd700;
            font-size: 1.2rem;
        }

        .overview {
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 2rem;
            color: var(--text-gray);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .btn {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(240, 20, 30, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(240, 20, 30, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .btn-favorited {
            background: linear-gradient(135deg, #1ed760 0%, #1db954 100%);
            box-shadow: 0 4px 15px rgba(30, 215, 96, 0.3);
        }

        .content-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }

        .video-container {
            margin-bottom: 4rem;
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .cast-section {
            margin-bottom: 4rem;
        }

        .cast-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
        }

        .cast-card {
            background: var(--bg-card);
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s;
        }

        .cast-card:hover {
            transform: translateY(-5px);
        }

        .cast-photo {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .cast-info {
            padding: 1rem;
            text-align: center;
        }

        .cast-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .cast-character {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .back-button {
            position: fixed;
            top: 100px;
            left: 2rem;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .back-button:hover {
            background: rgba(240, 20, 30, 0.8);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 2rem;
            }

            .movie-info h1 {
                font-size: 2rem;
            }

            .movie-meta {
                justify-content: center;
                flex-wrap: wrap;
            }

            .content-section {
                padding: 2rem 1rem;
            }

            .cast-grid {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-button">← Voltar</a>

    <header class="header">
        <div class="header-content">
            <img src="assets/img/marvel.svg" alt="Spider Movies" class="logo"
            style="width: 100px; height: 40px;">
            <nav class="nav-links">
                <?php if ($logado): ?>
                    <a href="favoritos.php">Meus Favoritos</a>
                    <a href="logout.php">Sair</a>
                <?php else: ?>
                    <a href="login.php">Entrar</a>
                    <a href="registrar.php">Registrar</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <section class="hero-section">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <div class="poster-container">
                <img src="https://image.tmdb.org/t/p/w500<?= htmlspecialchars($filme['poster_path']) ?>" 
                     alt="<?= htmlspecialchars($filme['title']) ?>" class="poster">
            </div>
            <div class="movie-info">
                <h1><?= htmlspecialchars($filme['title']) ?></h1>
                <div class="movie-meta">
                    <?php if ($dataLancamento): ?>
                        <span><?= $dataLancamento ?></span>
                    <?php endif; ?>
                    <?php if ($duracao): ?>
                        <span><?= $duracao ?></span>
                    <?php endif; ?>
                    <?php if ($filme['vote_average']): ?>
                        <div class="rating">
                            <span class="star">★</span>
                            <span><?= number_format($filme['vote_average'], 1) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <p class="overview"><?= htmlspecialchars($filme['overview']) ?></p>
                <div class="action-buttons">
                    <?php if ($logado): ?>
                        <?php if ($jaFavoritado): ?>
                            <form action="api/remover_favorito.php" method="post" style="display: inline;">
                                <input type="hidden" name="id_filme" value="<?= $filme['id'] ?>">
                                <button type="submit" class="btn btn-favorited">
                                    ♥ Favoritado
                                </button>
                            </form>
                        <?php else: ?>
                            <form action="api/favoritar.php" method="post" style="display: inline;">
                                <input type="hidden" name="id_filme" value="<?= $filme['id'] ?>">
                                <input type="hidden" name="titulo" value="<?= htmlspecialchars($filme['title']) ?>">
                                <input type="hidden" name="poster" value="<?= htmlspecialchars($filme['poster_path']) ?>">
                                <input type="hidden" name="sinopse" value="<?= htmlspecialchars($filme['overview']) ?>">
                                <button type="submit" class="btn btn-primary">
                                    ♡ Favoritar
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Entre para Favoritar</a>
                    <?php endif; ?>
                    <?php if ($video): ?>
                        <a href="#trailer" class="btn btn-secondary">▶ Assistir Trailer</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <div class="content-section">
        <?php if ($video): ?>
            <div class="video-container" id="trailer">
                <h2 class="section-title">Trailer</h2>
                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/<?= $video ?>?autoplay=0" 
                            allowfullscreen></iframe>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($elenco)): ?>
            <div class="cast-section">
                <h2 class="section-title">Elenco Principal</h2>
                <div class="cast-grid">
                    <?php foreach ($elenco as $ator): ?>
                        <div class="cast-card">
                            <?php if ($ator['profile_path']): ?>
                                <img src="https://image.tmdb.org/t/p/w200<?= htmlspecialchars($ator['profile_path']) ?>" 
                                     alt="<?= htmlspecialchars($ator['name']) ?>" class="cast-photo">
                            <?php else: ?>
                                <div class="cast-photo" style="background: #333; display: flex; align-items: center; justify-content: center; color: #666;">
                                    Sem Foto
                                </div>
                            <?php endif; ?>
                            <div class="cast-info">
                                <div class="cast-name"><?= htmlspecialchars($ator['name']) ?></div>
                                <div class="cast-character"><?= htmlspecialchars($ator['character']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>