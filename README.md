/               <- raiz do site (htdocs no InfinityFree)
├── app/        <- código PHP de "lógica" (models, controllers, helpers)
│   ├── config/
│   │   └── config.php        <- configurações globais (DB, paths, etc.)
│   │
│   ├── database/
│   │   └── connection.php    <- conexão PDO/MySQL usando dados do InfinityFree
│   │
│   ├── models/               <- classes que representam tabelas do banco
│   │   ├── User.php
│   │   ├── Post.php
│   │   ├── Tag.php
│   │   ├── Language.php
│   │   ├── Comment.php
│   │   ├── Like.php
│   │   ├── Favorite.php
│   │   ├── Conversation.php
│   │   └── Message.php
│   │
│   ├── controllers/          <- arquivos que processam requisições
│   │   ├── AuthController.php        <- login, registro, logout
│   │   ├── PostController.php        <- CRUD de posts (criar, editar, listar)
│   │   ├── ProfileController.php     <- ver/editar perfil
│   │   ├── MessageController.php     <- mensagens e conversas
│   │   ├── FavoriteController.php    <- favoritar/desfavoritar posts
│   │   └── SettingsController.php    <- configurações de conta/tema
│   │
│   └── helpers/              <- funções auxiliares em PHP
│       ├── auth.php          <- sessão do usuário, checar login, etc.
│       ├── csrf.php          <- gerar/validar tokens CSRF
│       ├── validation.php    <- validação de formulários
│       ├── upload.php        <- upload de imagens (avatar, foto do post)
│       └── utils.php         <- helpers gerais (formatar data, etc.)
│
├── views/                    <- HTML + PHP (templates das telas)
│   ├── layouts/
│   │   └── main.php          <- layout base (inclui <head>, header, footer)
│   │
│   ├── partials/
│   │   ├── head.php          <- <head> com CSS/JS
│   │   ├── header.php        <- topo do site (logo, busca)
│   │   ├── nav.php           <- menu principal (feed, mensagens, perfil, etc.)
│   │   ├── footer.php
│   │   └── flash.php         <- mensagens de sucesso/erro
│   │
│   ├── landing/              <- página inicial (não logado)
│   │   └── index.php
│   │
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   │
│   ├── feed/
│   │   └── index.php         <- lista de posts estilo LinkedIn/GitHub
│   │
│   ├── post/
│   │   ├── create.php        <- formulário criar post
│   │   ├── edit.php          <- formulário editar post
│   │   └── show.php          <- ver post individual (comentários, etc.)
│   │
│   ├── profile/
│   │   ├── show.php          <- perfil público
│   │   └── edit.php          <- editar perfil (bio, links, avatar)
│   │
│   ├── messages/
│   │   ├── index.php         <- lista de conversas
│   │   └── conversation.php  <- chat entre dois usuários
│   │
│   ├── favorites/
│   │   └── index.php         <- posts favoritados
│   │
│   └── settings/
│       └── index.php         <- configurações (tema dark/light, senha, etc.)
│
├── assets/                   <- arquivos estáticos (não-PHP)
│   ├── css/
│   │   ├── main.css          <- estilos gerais + modo dark/light (variáveis CSS)
│   │   └── auth.css          <- (opcional) telas de login/registro
│   │
│   ├── js/
│   │   ├── main.js
│   │   └── theme-toggle.js   <- script que alterna dark/light (altera data-theme)
│   │
│   └── img/
│       ├── logo.svg
│       ├── default-avatar.png
│       └── (outros ícones/imagens)
│
├── uploads/                  <- uploads feitos pelos usuários
│   ├── avatars/              <- fotos de perfil
│   └── posts/                <- imagens dos posts/projetos
│
├── sql/                      <- scripts SQL para criar/popular o banco
│   ├── schema.sql            <- todas as CREATE TABLE (users, posts, etc.)
│   ├── seed_languages.sql    <- linguagens iniciais (PHP, JS, Python, etc.)
│   └── seed_tags.sql         <- hashtags iniciais (startup, backend, frontend...)
│
├── index.php                 <- landing page (não logado) / redireciona para feed se logado
├── login.php                 <- chama AuthController (exibe form ou processa POST)
├── register.php              <- idem, para registro
├── logout.php                <- encerra sessão e redireciona
│
├── feed.php                  <- chama PostController::index (lista posts)
├── post_create.php           <- exibe formulário (PostController::create)
├── post_store.php            <- recebe POST e cria registro (PostController::store)
├── post_show.php             <- ver post (PostController::show)
├── post_edit.php             <- formulário edição (PostController::edit)
├── post_update.php           <- processa edição (PostController::update)
├── post_delete.php           <- apaga post (PostController::delete)
│
├── profile.php               <- ver perfil (ProfileController::show)
├── profile_edit.php          <- editar perfil (ProfileController::edit/update)
│
├── messages.php              <- lista conversas (MessageController::index)
├── conversation.php          <- conversa específica (MessageController::show/send)
│
├── favorites.php             <- listar favoritos (FavoriteController::index)
│
├── settings.php              <- tela de configurações (SettingsController)
├── search.php                <- busca por posts / usuários / tags
│
├── .htaccess                 <- (opcional) regras de URL amigável, redirecionos, etc.
└── README.md                 <- instruções básicas do projeto