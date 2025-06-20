<?php
session_start();
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #0c0c0c 0%, #1a1a1a 25%, #2d1b1b 50%, #1a1a1a 75%, #0c0c0c 100%);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
        position: relative;
        overflow: hidden;
      }

      body::before {
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

      .login-container {
        background: rgba(26, 26, 26, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(240, 20, 30, 0.1);
        border-radius: 24px;
        box-shadow: 
          0 20px 40px rgba(0, 0, 0, 0.4),
          0 8px 16px rgba(240, 20, 30, 0.1),
          inset 0 1px 0 rgba(255, 255, 255, 0.05);
        padding: 3rem 2.5rem;
        width: 100%;
        max-width: 420px;
        position: relative;
        z-index: 10;
        animation: slideUp 0.8s ease-out;
        overflow: hidden;
      }

      .login-container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(240, 20, 30, 0.03), transparent);
        animation: shimmer 3s ease-in-out infinite;
        pointer-events: none;
      }

      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(30px) scale(0.95);
        }
        to {
          opacity: 1;
          transform: translateY(0) scale(1);
        }
      }

      @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
      }

      .logo-section {
        text-align: center;
        margin-bottom: 2.5rem;
        position: relative;
      }

      .logo-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #f0141e 0%, #ff4757 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 8px 24px rgba(240, 20, 30, 0.3);
        animation: pulse 2s ease-in-out infinite;
      }

      @keyframes pulse {
        0%, 100% { transform: scale(1); box-shadow: 0 8px 24px rgba(240, 20, 30, 0.3); }
        50% { transform: scale(1.05); box-shadow: 0 12px 32px rgba(240, 20, 30, 0.4); }
      }

      .logo-icon i {
        font-size: 28px;
        color: white;
      }

      .login-container h2 {
        color: #fff;
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
      }

      .subtitle {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.95rem;
        font-weight: 400;
        margin-bottom: 2rem;
      }

      .form-group {
        position: relative;
        margin-bottom: 1.5rem;
      }

      .form-group label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
        transition: color 0.3s ease;
      }

      .input-wrapper {
        position: relative;
      }

      .input-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.4);
        font-size: 1.1rem;
        transition: color 0.3s ease;
        z-index: 2;
      }

      form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }

      .form-group input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border-radius: 12px;
        border: 1.5px solid rgba(255, 255, 255, 0.1);
        background: rgba(35, 37, 38, 0.8);
        color: #fff;
        font-size: 1rem;
        font-weight: 400;
        outline: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
      }

      .form-group input:focus {
        border-color: #f0141e;
        background: rgba(35, 37, 38, 0.95);
        box-shadow: 0 0 0 3px rgba(240, 20, 30, 0.1);
        transform: translateY(-1px);
      }

      .form-group input:focus + .input-wrapper i,
      .form-group.focused .input-wrapper i {
        color: #f0141e;
      }

      .form-group input::placeholder {
        color: rgba(255, 255, 255, 0.3);
        font-weight: 300;
      }

      .login-btn {
        width: 100%;
        padding: 1.1rem 2rem;
        background: linear-gradient(135deg, #f0141e 0%, #d0121a 50%, #b01016 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 
          0 6px 20px rgba(240, 20, 30, 0.3),
          0 2px 4px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin: 1.5rem 0;
      }

      .login-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
      }

      .login-btn:hover::before {
        left: 100%;
      }

      .login-btn:hover {
        background: linear-gradient(135deg, #d0121a 0%, #f0141e 50%, #ff4757 100%);
        transform: translateY(-2px);
        box-shadow: 
          0 8px 25px rgba(240, 20, 30, 0.4),
          0 4px 8px rgba(0, 0, 0, 0.3);
      }

      .login-btn:active {
        transform: translateY(0);
        box-shadow: 
          0 4px 15px rgba(240, 20, 30, 0.3),
          0 1px 3px rgba(0, 0, 0, 0.2);
      }

      .message {
        border-radius: 10px;
        padding: 0.9rem 1.2rem;
        font-size: 0.95rem;
        text-align: center;
        margin-bottom: 1.5rem;
        font-weight: 500;
        animation: messageSlide 0.5s ease-out;
      }

      @keyframes messageSlide {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .erro {
        color: #ff6b7a;
        background: linear-gradient(135deg, rgba(240, 20, 30, 0.1), rgba(255, 107, 122, 0.08));
        border: 1px solid rgba(240, 20, 30, 0.2);
      }

      .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
        opacity: 0.5;
      }

      .divider::before,
      .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      }

      .divider span {
        padding: 0 1rem;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.85rem;
        font-weight: 500;
      }

      .link-registro {
        text-align: center;
        margin-top: 1.5rem;
      }

      .link-registro p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
      }

      .link-registro a {
        color: #f0141e;
        text-decoration: none;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-block;
      }

      .link-registro a:hover {
        color: #fff;
        background: rgba(240, 20, 30, 0.1);
        transform: translateY(-1px);
      }

      .forgot-password {
        text-align: right;
        margin-top: 0.5rem;
      }

      .forgot-password a {
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s ease;
      }

      .forgot-password a:hover {
        color: #f0141e;
      }

      @media (max-width: 480px) {
        .login-container {
          margin: 1rem;
          padding: 2rem 1.5rem;
        }
        
        .login-container h2 {
          font-size: 1.8rem;
        }
      }
    </style>
</head>
<body>
  <div class="login-container">
    <div class="logo-section">
      <div class="logo-icon">
        <i class="fas fa-film"></i>
      </div>
      <h2>Bem-vindo</h2>
      <p class="subtitle">Entre na sua conta Spider Movies</p>
    </div>

    <?php if (isset($erro)): ?>
      <div class="message erro">
        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
        <?= htmlspecialchars($erro) ?>
      </div>
    <?php endif; ?>

    <form method="post">
      <div class="form-group">
        <label for="email">Email</label>
        <div class="input-wrapper">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email" id="email" placeholder="seu@email.com" required>
        </div>
      </div>

      <div class="form-group">
        <label for="senha">Senha</label>
        <div class="input-wrapper">
          <i class="fas fa-lock"></i>
          <input type="password" name="senha" id="senha" placeholder="••••••••" required>
        </div>
        <div class="forgot-password">
          <a href="#" onclick="alert('Funcionalidade em desenvolvimento')">Esqueceu a senha?</a>
        </div>
      </div>

      <button type="submit" class="login-btn">
        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
        Entrar
      </button>
    </form>

    <div class="divider">
      <span>ou</span>
    </div>

    <div class="link-registro">
      <p>Não tem uma conta?</p>
      <a href="registrar.php">
        <i class="fas fa-user-plus" style="margin-right: 6px;"></i>
        Criar conta gratuita
      </a>
    </div>
  </div>

  <script>
    // Adiciona efeitos de foco nos inputs
    document.querySelectorAll('.form-group input').forEach(input => {
      input.addEventListener('focus', function() {
        this.closest('.form-group').classList.add('focused');
      });
      
      input.addEventListener('blur', function() {
        if (!this.value) {
          this.closest('.form-group').classList.remove('focused');
        }
      });
    });

    // Animação de entrada
    window.addEventListener('load', function() {
      document.querySelector('.login-container').style.opacity = '0';
      document.querySelector('.login-container').style.transform = 'translateY(30px) scale(0.95)';
      
      setTimeout(() => {
        document.querySelector('.login-container').style.transition = 'all 0.8s ease-out';
        document.querySelector('.login-container').style.opacity = '1';
        document.querySelector('.login-container').style.transform = 'translateY(0) scale(1)';
      }, 100);
    });
  </script>
</body>
</html>