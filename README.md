# 🕷️ Spider Movies - Plataforma de Filmes Marvel

## 📋 Descrição do Projeto

O **Spider Movies** é uma plataforma web moderna e interativa dedicada ao universo cinematográfico da Marvel. O projeto oferece uma experiência completa para fãs de filmes Marvel, permitindo explorar, favoritar e acompanhar detalhes dos filmes do MCU (Marvel Cinematic Universe).

## ✨ Funcionalidades Principais

### 🎬 Catálogo de Filmes
- **Busca Automática**: Integração com a API do The Movie Database (TMDB) para obter filmes Marvel atualizados
- **Filtros Inteligentes**: Exibição apenas de filmes da Marvel (com filtro por empresa ID 420)
- **Ordenação por Popularidade**: Filmes organizados por relevância e popularidade

### 👤 Sistema de Usuários
- **Registro de Conta**: Cadastro completo com validação de dados
- **Login Seguro**: Autenticação com hash de senha (password_hash)
- **Sessões Persistentes**: Manutenção do estado de login
- **Logout**: Encerramento seguro de sessões

### ❤️ Sistema de Favoritos
- **Adicionar Favoritos**: Botão para salvar filmes preferidos
- **Lista Pessoal**: Página dedicada aos filmes favoritados
- **Remoção**: Funcionalidade para remover filmes da lista
- **Persistência**: Dados salvos no banco de dados MySQL

### 🎥 Detalhes dos Filmes
- **Informações Completas**: Título, sinopse, data de lançamento, duração
- **Trailers**: Integração com YouTube para exibição de trailers
- **Elenco**: Lista dos principais atores do filme
- **Posters**: Imagens de alta qualidade dos filmes
- **Avaliações**: Sistema de pontuação e reviews

### 🎨 Interface Moderna
- **Design Responsivo**: Adaptação para desktop, tablet e mobile
- **Tema Marvel**: Cores e estética inspiradas no universo Marvel
- **Animações**: Efeitos visuais e transições suaves
- **Carrossel Interativo**: Navegação por filmes com Swiper.js

## 🛠️ Tecnologias Utilizadas

### Backend
- **PHP 7.4+**: Linguagem principal do servidor
- **MySQL**: Banco de dados relacional
- **Sessões PHP**: Gerenciamento de estado de usuário
- **API Externa**: Integração com The Movie Database (TMDB)

### Frontend
- **HTML5**: Estrutura semântica
- **CSS3**: Estilização moderna com variáveis CSS
- **JavaScript**: Interatividade e funcionalidades dinâmicas
- **Swiper.js**: Biblioteca para carrosséis responsivos
- **Font Awesome**: Ícones vetoriais
- **Google Fonts**: Tipografia Roboto

### Segurança
- **Prepared Statements**: Proteção contra SQL Injection
- **Password Hashing**: Criptografia de senhas com password_hash()
- **Validação de Sessão**: Verificação de autenticação
- **Sanitização de Dados**: Limpeza de inputs do usuário

## 📁 Estrutura do Projeto

```
spidermovies/
├── 📁 api/                    # Endpoints da API
│   ├── favoritar.php         # Adicionar filme aos favoritos
│   └── remover_favorito.php  # Remover filme dos favoritos
├── 📁 assets/                # Recursos estáticos
│   ├── 📁 img/              # Imagens do projeto
│   │   ├── bg.jpg           # Imagem de fundo
│   │   ├── marvel.svg       # Logo Marvel
│   │   ├── noticia1.png     # Imagens de notícias
│   │   ├── noticia2.png
│   │   └── noticia3.png
│   └── style.css            # Estilos principais
├── 📁 includes/             # Arquivos de configuração
│   ├── conexao.php         # Conexão com banco de dados
│   └── funcoesAPI.php      # Funções para API externa
├── index.php               # Página principal
├── login.php              # Página de login
├── registrar.php          # Página de registro
├── logout.php             # Script de logout
├── detalhes.php           # Página de detalhes do filme
├── favoritos.php          # Página de filmes favoritos
└── filme.php              # Página individual do filme
```

## 🗄️ Estrutura do Banco de Dados

### Tabela: `usuarios`
```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabela: `filmes`
```sql
CREATE TABLE filmes (
    id_filme INT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    poster VARCHAR(500),
    sinopse TEXT,
    data_insercao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabela: `favoritos`
```sql
CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    id_filme INT,
    data_favorito TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (id_filme) REFERENCES filmes(id_filme),
    UNIQUE(usuario_id, id_filme)
);
```

## 🚀 Como Executar o Projeto

### Pré-requisitos
- **Servidor Web**: Apache/Nginx com suporte a PHP
- **PHP**: Versão 7.4 ou superior
- **MySQL**: Versão 5.7 ou superior
- **Extensões PHP**: mysqli, json, session

### Instalação

1. **Clone o repositório**
   ```bash
   git clone [url-do-repositorio]
   cd spidermovies
   ```

2. **Configure o banco de dados**
   - Crie um banco MySQL chamado `spidermovies`
   - Execute os scripts SQL para criar as tabelas
   - Configure as credenciais em `includes/conexao.php`

3. **Configure a API**
   - Obtenha uma chave gratuita em [The Movie Database](https://www.themoviedb.org/settings/api)
   - Atualize a constante `API_KEY` no arquivo `index.php`

4. **Configure o servidor web**
   - Aponte o document root para a pasta do projeto
   - Certifique-se que o mod_rewrite está habilitado (se necessário)

5. **Acesse o projeto**
   - Abra o navegador e acesse `http://localhost/spidermovies`

## 🔧 Configurações Importantes

### API Key do TMDB
```php
// Em index.php e detalhes.php
const API_KEY = 'sua_chave_api_aqui';
```

### Configuração do Banco
```php
// Em includes/conexao.php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'spidermovies';
```

## 🎯 Funcionalidades Detalhadas

### Página Principal (`index.php`)
- **Hero Section**: Apresentação impactante com call-to-action
- **Carrossel de Filmes**: Exibição dinâmica dos filmes Marvel
- **Seção de Notícias**: Área para conteúdo editorial
- **Navegação Intuitiva**: Menu responsivo com estados de login

### Sistema de Autenticação
- **Validação Completa**: Verificação de email e senha
- **Mensagens de Erro**: Feedback claro para o usuário
- **Redirecionamento Seguro**: Navegação após login/logout

### Página de Detalhes (`detalhes.php`)
- **Informações Completas**: Todos os dados do filme
- **Trailer Integrado**: Player do YouTube
- **Elenco**: Lista dos principais atores
- **Botão de Favorito**: Adicionar/remover da lista pessoal

### Gestão de Favoritos
- **API RESTful**: Endpoints para adicionar/remover
- **Validação de Sessão**: Proteção contra acesso não autorizado
- **Feedback Visual**: Confirmação de ações do usuário

## 🎨 Design e UX

### Paleta de Cores
- **Vermelho Marvel**: `#f0141e` (cor principal)
- **Preto**: `#0a0a0a` (fundo)
- **Cinza Escuro**: `#1a1a1a` (cards)
- **Branco**: `#ffffff` (texto)

### Responsividade
- **Mobile First**: Design otimizado para dispositivos móveis
- **Breakpoints**: 480px, 768px, 1024px, 1200px
- **Flexbox/Grid**: Layout moderno e flexível

### Animações
- **Transições Suaves**: Hover effects e mudanças de estado
- **Loading States**: Feedback visual durante carregamentos
- **Micro-interações**: Detalhes que melhoram a experiência


---

**Desenvolvido com ❤️ para os fãs do Universo Marvel** 