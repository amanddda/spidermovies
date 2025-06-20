<?php
include('../includes/conexao.php'); // Essa linha importa o arquivo de conexão com o banco de dados. Isso é essencial para que $conn funcione mais abaixo.

if (!isset($_SESSION['usuario_id'])) {
    die('Não autorizado.');
}    /*Explicação: verifica se a sessão contém o usuario_id.
Se não tiver (ou seja, o usuário não está logado), o script termina imediatamente com a mensagem "Não autorizado."
 Segurança básica para evitar que alguém acesse a API diretamente.*/



$id_filme = $_POST['id_filme'];
$titulo = $_POST['titulo'];
$poster = $_POST['poster'];
$sinopse = $_POST['sinopse'];
$usuario_id = $_SESSION['usuario_id'];    /* Explicação:
Pega os dados que vieram via POST (geralmente de um formulário ou requisição via JS) e o usuario_id da sessão.
Esses dados serão usados para:
Inserir o filme (caso ainda não esteja na base).
Relacionar o usuário com o filme na tabela de favoritos.*/

$stmt = $conn->prepare("INSERT IGNORE INTO filmes (id_filme, titulo, poster, sinopse) VALUES (?, ?, ?, ?)");/*Explicação:
Prepara um comando SQL para inserir o filme na tabela filmes.
INSERT IGNORE é usado para não dar erro caso o id_filme já exista (ou seja, o filme já foi inserido antes).
Boa prática quando você quer evitar duplicidade sem causar erro.*/




$stmt->bind_param("isss", $id_filme, $titulo, $poster, $sinopse);
$stmt->execute();

/* Explicação:
bind_param faz a ligação segura dos valores com a query (protege contra SQL Injection).
"isss" significa:
i → inteiro (id_filme)
s → string (titulo, poster, sinopse)
Depois, ele executa a query.*/



$stmt2 = $conn->prepare("INSERT INTO favoritos (usuario_id, id_filme) VALUES (?, ?)");
$stmt2->bind_param("ii", $usuario_id, $id_filme);
$stmt2->execute();
/* Explicação:
Aqui ele grava a relação de favorito na tabela favoritos.
Exemplo de como essa tabela pode estar estruturada:
sql
Copiar código
CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    id_filme INT
);
Se você não quiser que o mesmo filme seja favoritado várias vezes, é bom colocar uma chave única composta (UNIQUE(usuario_id, id_filme)) na tabela favoritos.*/





header("Location: ../index.php");
exit;

/*Explicação:
Depois de salvar tudo, redireciona o usuário de volta para a página principal.

exit é importante para parar imediatamente a execução do script após o redirecionamento.

 Resumo geral
Essa API:

Garante que o usuário esteja logado.

Salva o filme na tabela filmes (sem duplicar).

Salva o favorito na tabela favoritos.

Redireciona para o index.php.

🔧 Sugestões de melhoria (opcional)
Proteção contra favoritar duplicado:
Coloque isso na estrutura SQL:

sql
Copiar código
UNIQUE(usuario_id, id_filme)
Assim evita que o mesmo usuário favorite o mesmo filme mais de uma vez.

Validação de entrada:
Verifique se $_POST['id_filme'], $_POST['titulo'] etc. realmente existem e têm dados válidos antes de usar.

Mensagens de erro amigáveis:
Em vez de só redirecionar, pode exibir uma mensagem de "Filme favoritado com sucesso!" ou "Esse filme já foi favoritado". */

?>



