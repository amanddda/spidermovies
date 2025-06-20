<?php
session_start();
include('includes/conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT f.id_filme, f.titulo, f.poster, f.sinopse
        FROM favoritos fav
        JOIN filmes f ON fav.id_filme = f.id_filme
        WHERE fav.usuario_id = ?
        ORDER BY fav.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$favoritos = $resultado->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Favoritos - Spider Movies</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            height: 40vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-top: 80px;
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a1a 25%, #2d1b1b 50%, #1a1a1a 75%, #0c0c0c 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="g" cx="50%" cy="50%"><stop offset="0%" stop-color="%23f0141e" stop-opacity="0.05"/><stop offset="100%" stop-color="%23000" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23g)"/><circle cx="800" cy="300" r="150" fill="url(%23g)"/><circle cx="400" cy="700" r="120" fill="url(%23g)"/><circle cx="700" cy="800" r="80" fill="url(%23g)"/></svg>');
            opacity: 0.3;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(2deg); }
            66% { transform: translateY(10px) rotate(-1deg); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            padding: 0 2rem;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #f0141e 0%, #ff4757 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-content p {
            font-size: 1.2rem;
            color: var(--text-gray);
            margin-bottom: 2rem;
        }

        .favorites-count {
            display: inline-block;
            background: rgba(240, 20, 30, 0.2);
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            border: 1px solid var(--primary-red);
            font-weight: 600;
            color: var(--primary-red);
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
            color: var(--text-light);
        }

        .favorites-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .favorite-card {
            background: var(--bg-card);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .favorite-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .favorite-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-red) 0%, #ff4757 100%);
        }

        .favorite-poster {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .favorite-card:hover .favorite-poster {
            transform: scale(1.05);
        }

        .favorite-info {
            padding: 1.5rem;
        }

        .favorite-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-light);
            line-height: 1.3;
        }

        .favorite-synopsis {
            color: var(--text-gray);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .favorite-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            justify-content: center;
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

        .btn-danger {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--bg-card);
            border-radius: 15px;
            margin: 2rem 0;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--text-light);
        }

        .empty-state p {
            color: var(--text-gray);
            margin-bottom: 2rem;
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
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .content-section {
                padding: 2rem 1rem;
            }

            .favorites-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .favorite-actions {
                flex-direction: column;
            }

            .back-button {
                left: 1rem;
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .hero-content h1 {
                font-size: 2rem;
            }

            .favorites-grid {
                grid-template-columns: 1fr;
            }

            .header-content {
                padding: 0 1rem;
            }

            .nav-links {
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-button">
        <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
        Voltar
    </a>

    <header class="header">
        <div class="header-content">
            <img src="assets/img/marvel.svg" alt="Spider Movies" class="logo"
            style="width: 100px; height: 40px;">
            <nav class="nav-links">
                <a href="favoritos.php" style="color: var(--primary-red);">Meus Favoritos</a>
                <a href="logout.php">Sair</a>
            </nav>
        </div>
    </header>

    <section class="hero-section">
        <div class="hero-content">
            <h1>Meus Favoritos</h1>
            <p>Suas histórias favoritas do universo Marvel em um só lugar</p>
            <div class="favorites-count">
                <i class="fas fa-heart" style="margin-right: 8px;"></i>
                <?= count($favoritos) ?> filme<?= count($favoritos) !== 1 ? 's' : '' ?> favoritado<?= count($favoritos) !== 1 ? 's' : '' ?>
            </div>
        </div>
    </section>

    <div class="content-section">
        <?php if (empty($favoritos)): ?>
            <div class="empty-state">
                <i class="fas fa-heart-broken"></i>
                <h3>Nenhum filme favoritado ainda</h3>
                <p>Explore o universo Marvel e adicione seus filmes favoritos à sua coleção!</p>
                <a href="index.php" class="btn btn-primary">
                    Explorar Filmes
                </a>
            </div>
        <?php else: ?>
            <div class="favorites-grid">
                <?php foreach ($favoritos as $filme): ?>
                    <div class="favorite-card">
                        <img src="https://image.tmdb.org/t/p/w500<?= htmlspecialchars($filme['poster']) ?>" 
                             alt="<?= htmlspecialchars($filme['titulo']) ?>" 
                             class="favorite-poster">
                        <div class="favorite-info">
                            <h3 class="favorite-title"><?= htmlspecialchars($filme['titulo']) ?></h3>
                            <p class="favorite-synopsis"><?= htmlspecialchars($filme['sinopse']) ?></p>
                            <div class="favorite-actions">
                                <a href="detalhes.php?id=<?= $filme['id_filme'] ?>" class="btn btn-primary">
                                    <i class="fas fa-eye"></i>
                                    Ver Detalhes
                                </a>
                                <form action="api/remover_favorito.php" method="post" style="display: inline; flex: 1;">
                                    <input type="hidden" name="id_filme" value="<?= $filme['id_filme'] ?>">
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Tem certeza que deseja remover este filme dos favoritos?')">
                                        <i class="fas fa-trash"></i>
                                        Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
