<?php
session_start();
include('includes/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        $sucesso = "Usuário registrado com sucesso!";
    } else {
        $erro = "Erro ao registrar: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Spider Movies</title>
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
        padding: 2rem 1rem;
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

      .register-container {
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
        max-width: 450px;
        position: relative;
        z-index: 10;
        animation: slideUp 0.8s ease-out;
        margin: auto;
      }

      .register-container::before {
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
      
      form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }

      .register-container h2 {
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

      .register-btn {
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

      .register-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
      }

      .register-btn:hover::before {
        left: 100%;
      }

      .register-btn:hover {
        background: linear-gradient(135deg, #d0121a 0%, #f0141e 50%, #ff4757 100%);
        transform: translateY(-2px);
        box-shadow: 
          0 8px 25px rgba(240, 20, 30, 0.4),
          0 4px 8px rgba(0, 0, 0, 0.3);
      }

      .register-btn:active {
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

      .sucesso {
        color: #4ade80;
        background: linear-gradient(135deg, rgba(74, 222, 128, 0.1), rgba(34, 197, 94, 0.08));
        border: 1px solid rgba(74, 222, 128, 0.2);
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

      .link-login {
        text-align: center;
        margin-top: 1.5rem;
      }

      .link-login p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
      }

      .link-login a {
        color: #f0141e;
        text-decoration: none;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-block;
      }

      .link-login a:hover {
        color: #fff;
        background: rgba(240, 20, 30, 0.1);
        transform: translateY(-1px);
      }

      .password-strength {
        margin-top: 0.5rem;
        font-size: 0.85rem;
      }

      .strength-bar {
        height: 3px;
        border-radius: 2px;
        background: rgba(255, 255, 255, 0.1);
        margin: 0.5rem 0;
        overflow: hidden;
      }

      .strength-fill {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
        border-radius: 2px;
      }

      .strength-weak { background: #ef4444; width: 25%; }
      .strength-fair { background: #f59e0b; width: 50%; }
      .strength-good { background: #10b981; width: 75%; }
      .strength-strong { background: #22c55e; width: 100%; }

      .terms-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin: 1rem 0;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.05);
      }

      .terms-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #f0141e;
        margin: 0;
      }

      .terms-checkbox label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        line-height: 1.4;
        margin: 0;
      }

      .terms-checkbox a {
        color: #f0141e;
        text-decoration: underline;
        transition: color 0.3s ease;
      }

      .terms-checkbox a:hover {
        color: #ff4757;
      }

      .success-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
      }

      .success-actions a {
        flex: 1;
        padding: 0.75rem 1rem;
        text-align: center;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .btn-primary {
        background: linear-gradient(135deg, #f0141e 0%, #d0121a 100%);
        color: white;
      }

      .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.2);
      }

      .btn-primary:hover {
        background: linear-gradient(135deg, #d0121a 0%, #f0141e 100%);
        transform: translateY(-1px);
      }

      .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-1px);
      }

      @media (max-width: 480px) {
        body {
          padding: 1rem 0.5rem;
          align-items: flex-start;
          min-height: auto;
        }
        
        .register-container {
          margin: 1rem 0;
          padding: 2rem 1.5rem;
        }
        
        .register-container h2 {
          font-size: 1.8rem;
        }

        .success-actions {
          flex-direction: column;
        }
      }
    </style>
</head>
<body>
  <div class="register-container">
    <div class="logo-section">
      <div class="logo-icon">
        <i class="fas fa-film"></i>
      </div>
      <h2>Criar Conta</h2>
      <p class="subtitle">Junte-se à comunidade Spider Movies</p>
    </div>

    <?php if (isset($erro)): ?>
      <div class="message erro">
        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
        <?= htmlspecialchars($erro) ?>
      </div>
    <?php endif; ?>

    <?php if (isset($sucesso)): ?>
      <div class="message sucesso">
        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
        <?= $sucesso ?>
        <div class="success-actions">
          <a href="login.php" class="btn-primary">
            <i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i>
            Fazer Login
          </a>
          <a href="index.php" class="btn-secondary">
            <i class="fas fa-home" style="margin-right: 6px;"></i>
            Início
          </a>
        </div>
      </div>
    <?php else: ?>
      <form method="post" id="registerForm">
        <div class="form-group">
          <label for="nome">Nome Completo</label>
          <div class="input-wrapper">
            <i class="fas fa-user"></i>
            <input type="text" name="nome" id="nome" placeholder="Seu nome completo" required>
          </div>
        </div>

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
            <input type="password" name="senha" id="senha" placeholder="••••••••" required minlength="6">
          </div>
          <div class="password-strength">
            <div class="strength-bar">
              <div class="strength-fill" id="strengthFill"></div>
            </div>
            <span id="strengthText" style="color: rgba(255, 255, 255, 0.6);">Digite uma senha</span>
          </div>
        </div>

        <div class="form-group">
          <label for="confirmar_senha">Confirmar Senha</label>
          <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="••••••••" required>
          </div>
          <div id="passwordMatch" style="margin-top: 0.5rem; font-size: 0.85rem;"></div>
        </div>

        <div class="terms-checkbox">
          <input type="checkbox" id="terms" required>
          <label for="terms">
            Eu concordo com os <a href="#" onclick="alert('Termos em desenvolvimento')">Termos de Uso</a> 
            e <a href="#" onclick="alert('Política em desenvolvimento')">Política de Privacidade</a> 
            do Spider Movies
          </label>
        </div>

        <button type="submit" class="register-btn" id="submitBtn">
          <i class="fas fa-user-plus" style="margin-right: 8px;"></i>
          Criar Conta
        </button>
      </form>

      <div class="divider">
        <span>ou</span>
      </div>

      <div class="link-login">
        <p>Já tem uma conta?</p>
        <a href="login.php">
          <i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i>
          Entrar agora
        </a>
      </div>
    <?php endif; ?>
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

    // Verificador de força da senha
    const senhaInput = document.getElementById('senha');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');

    if (senhaInput) {
      senhaInput.addEventListener('input', function() {
        const senha = this.value;
        let strength = 0;
        let strengthLabel = '';

        if (senha.length >= 6) strength++;
        if (senha.match(/[a-z]/) && senha.match(/[A-Z]/)) strength++;
        if (senha.match(/[0-9]/)) strength++;
        if (senha.match(/[^a-zA-Z0-9]/)) strength++;

        strengthFill.className = 'strength-fill';
        
        switch(strength) {
          case 0:
          case 1:
            strengthFill.classList.add('strength-weak');
            strengthLabel = 'Senha fraca';
            strengthText.style.color = '#ef4444';
            break;
          case 2:
            strengthFill.classList.add('strength-fair');
            strengthLabel = 'Senha regular';
            strengthText.style.color = '#f59e0b';
            break;
          case 3:
            strengthFill.classList.add('strength-good');
            strengthLabel = 'Senha boa';
            strengthText.style.color = '#10b981';
            break;
          case 4:
            strengthFill.classList.add('strength-strong');
            strengthLabel = 'Senha forte';
            strengthText.style.color = '#22c55e';
            break;
        }

        strengthText.textContent = strengthLabel;
      });
    }

    // Verificador de confirmação de senha
    const confirmarSenhaInput = document.getElementById('confirmar_senha');
    const passwordMatch = document.getElementById('passwordMatch');

    if (confirmarSenhaInput) {
      confirmarSenhaInput.addEventListener('input', function() {
        const senha = senhaInput.value;
        const confirmSenha = this.value;

        if (confirmSenha.length > 0) {
          if (senha === confirmSenha) {
            passwordMatch.innerHTML = '<i class="fas fa-check" style="color: #22c55e; margin-right: 6px;"></i>Senhas coincidem';
            passwordMatch.style.color = '#22c55e';
          } else {
            passwordMatch.innerHTML = '<i class="fas fa-times" style="color: #ef4444; margin-right: 6px;"></i>Senhas não coincidem';
            passwordMatch.style.color = '#ef4444';
          }
        } else {
          passwordMatch.innerHTML = '';
        }
      });
    }

    // Validação do formulário
    const form = document.getElementById('registerForm');
    if (form) {
      form.addEventListener('submit', function(e) {
        const senha = senhaInput.value;
        const confirmSenha = confirmarSenhaInput.value;
        const terms = document.getElementById('terms').checked;

        if (senha !== confirmSenha) {
          e.preventDefault();
          alert('As senhas não coincidem!');
          return;
        }

        if (!terms) {
          e.preventDefault();
          alert('Você deve aceitar os termos de uso!');
          return;
        }

        if (senha.length < 6) {
          e.preventDefault();
          alert('A senha deve ter pelo menos 6 caracteres!');
          return;
        }
      });
    }

    // Animação de entrada
    window.addEventListener('load', function() {
      document.querySelector('.register-container').style.opacity = '0';
      document.querySelector('.register-container').style.transform = 'translateY(30px) scale(0.95)';
      
      setTimeout(() => {
        document.querySelector('.register-container').style.transition = 'all 0.8s ease-out';
        document.querySelector('.register-container').style.opacity = '1';
        document.querySelector('.register-container').style.transform = 'translateY(0) scale(1)';
      }, 100);
    });
  </script>
</body>
</html>