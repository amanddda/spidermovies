<?php
include('includes/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($usuario = $resultado->fetch_assoc()) {
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            header("Location: index.php");
            exit;
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Spider Movies</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
      body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--gradient-dark);
      }
      .login-container {
        background: linear-gradient(145deg, #1a1a1a 0%, #2d2d2d 100%);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        padding: 2.5rem 2rem 2rem 2rem;
        width: 100%;
        max-width: 370px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.2rem;
      }
      .login-container h2 {
        color: #fff;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1.2rem;
        letter-spacing: 1px;
      }
      .login-container form {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 1.1rem;
      }
      .login-container input {
        padding: 0.9rem 1.1rem;
        border-radius: 8px;
        border: 1.5px solid #333;
        background: #232526;
        color: #fff;
        font-size: 1rem;
        outline: none;
        transition: border 0.3s;
      }
      .login-container input:focus {
        border: 1.5px solid #f0141e;
      }
      .login-container label {
        color: #fff;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 0.3rem;
      }
      .login-container .login-btn {
        padding: 0.9rem 1.1rem;
        background: linear-gradient(135deg, #f0141e 0%, #d0121a 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 12px rgba(240, 20, 30, 0.3);
        transition: background 0.3s, transform 0.2s;
      }
      .login-container .login-btn:hover {
        background: linear-gradient(135deg, #d0121a 0%, #f0141e 100%);
        transform: translateY(-2px);
      }
      .login-container .erro {
        color: #f0141e;
        background: rgba(240, 20, 30, 0.08);
        border-radius: 6px;
        padding: 0.7rem 1rem;
        font-size: 0.98rem;
        text-align: center;
        margin-bottom: 0.5rem;
      }
      .login-container .link-registro {
        color: #fff;
        font-size: 0.98rem;
        margin-top: 0.7rem;
        text-align: center;
      }
      .login-container .link-registro a {
        color: #f0141e;
        text-decoration: underline;
        transition: color 0.2s;
      }
      .login-container .link-registro a:hover {
        color: #fff;
      }
    </style>
</head>
<body>
  <div class="login-container">
    <h2>Entrar</h2>
    <?php if (isset($erro)): ?>
      <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <form method="post">
      <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Digite seu email" required>
      </div>
      <div>
        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha" placeholder="Digite sua senha" required>
      </div>
      <button type="submit" class="login-btn">Entrar</button>
    </form>
    <div class="link-registro">
      Não tem conta? <a href="registrar.php">Registrar</a>
    </div>
  </div>
</body>
</html>
