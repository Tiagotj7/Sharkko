# DevNetwork – Rede para Devs, Empresas e Projetos

DevNetwork é uma aplicação web em **PHP puro + MySQL** que funciona como uma mistura de **LinkedIn, GitHub, Mercado Livre e Shark Tank**:

- Devs e empresas criam perfis
- Publicam projetos com tecnologias usadas, hashtags e formas de contato
- Podem curtir, comentar, favoritar, buscar projetos e pessoas
- Enviar **mensagens privadas (DM)** entre usuários
- Escolher entre **tema Dark** ou **Light**

Pensado para rodar em hospedagem gratuita como **InfinityFree**.

---

## ✨ Funcionalidades

### Autenticação & Perfil

- Registro de usuário
- Login / Logout
- Perfil de usuário com:
  - Nome, bio, localização
  - Links (GitHub, LinkedIn, Website)
  - Foto de avatar (upload)
  - Tema dark/light
- Edição de perfil
- Alterar senha (em `settings.php`)

### Projetos (Posts)

- Criar post/projeto com:
  - Título
  - Descrição
  - Imagem do projeto (upload)
  - Linguagens / tecnologias (texto separado por vírgula)
  - Hashtags (texto separado por vírgula)
  - Contato (email e/ou link externo)
- Ver post detalhado
- Editar e excluir posts (apenas o autor)
- Feed com lista de posts mais recentes

### Interação

- Curtir / Descurtir posts
- Comentar posts
- Favoritar / desfavoritar posts
- Página com **“Meus favoritos”**

### Mensagens privadas (DM)

- Lista de conversas do usuário
- Criar conversa a partir do perfil de outro usuário (“Enviar mensagem”)
- Chat simples (mensagens em ordem cronológica)

### Busca

- Página de busca (`search.php`), permitindo procurar:
  - Usuários por nome/bio
  - Projetos por título, descrição, tags, linguagens

### Tema Dark / Light

- Tema Dark padrão (modo “hacker/dev”)
- Tema Light opcional
- Alternar tema com botão de “Tema” no topo
- Tema também pode ser salvo nas configurações do usuário

---

## 🛠️ Tecnologias utilizadas

- **PHP** (7.4+ recomendado; funciona em PHP 8+)
- **MySQL** (InnoDB, utf8mb4)
- HTML5 / CSS3 / JavaScript puro
- Sem frameworks (nem Laravel, nem React/Vue)
- Hospedagem alvo: **InfinityFree** (ou qualquer host com PHP+MySQL)

---

## 📁 Estrutura de pastas

Na raiz do site (em geral a pasta `htdocs` no InfinityFree):

```bash
/               # raiz do site (htdocs)
├── app/
│   ├── config/
│   │   └── config.php
│   ├── database/
│   │   └── connection.php
│   ├── helpers/
│   │   ├── auth.php
│   │   ├── csrf.php
│   │   └── utils.php
│   └── models/
│       ├── User.php
│       ├── Post.php
│       ├── Comment.php
│       ├── Like.php
│       ├── Favorite.php
│       ├── Conversation.php
│       ├── Message.php
│       ├── Tag.php         # opcional, usado para listar tags agregadas
│       └── Language.php    # opcional, usado para listar linguagens agregadas
│
├── views/
│   └── partials/
│       ├── head.php
│       ├── header.php
│       ├── flash.php
│       └── footer.php
│
├── assets/
│   ├── css/
│   │   └── main.css
│   └── js/
│       └── theme-toggle.js
│
├── uploads/
│   ├── avatars/            # avatars de usuários (upload)
│   └── posts/              # imagens dos posts (upload)
│
├── sql/
│   └── schema.sql          # criação das tabelas do MySQL
│
├── index.php               # landing page + feed resumido
├── login.php               # login
├── register.php            # registro
├── logout.php              # logout
├── feed.php                # feed de posts (para usuários logados)
├── post_create.php         # criar novo post
├── post_show.php           # ver post (detalhe + comentários)
├── post_like.php           # curtir / descurtir post (POST)
├── post_favorite.php       # favoritar / desfavoritar post (POST)
├── post_edit.php           # editar post
├── post_delete.php         # apagar post
├── profile.php             # ver perfil (público)
├── profile_edit.php        # editar perfil (logado)
├── favorites.php           # listar posts favoritados
├── messages.php            # lista de conversas (DMs)
├── conversation.php        # tela de chat de uma conversa
├── search.php              # busca de posts/usuários
└── settings.php            # configurações (tema e senha)