# ☕ Desafio Mosyle - API em PHP

Este projeto é uma API RESTful construída em PHP com Docker, utilizando uma arquitetura MVC customizada. O objetivo é gerenciar usuários e o histórico de consumo de café.

---

## 🚀 Tecnologias Utilizadas

- PHP 8.x
- Docker + Docker Compose
- NGINX
- JWT para autenticação
- Estrutura MVC customizada (sem framework externo)

---

## ⚙️ Como Rodar o Projeto

### 1. Subir o ambiente

```bash
docker compose up -d --build
```

### 2. Conectar ao banco

```bash
docker exec -it php_app php src/Database/Database.php
```

### 3. Rodar migrations

```bash
docker exec -it php_app php src/Database/migrate.php
```

---

## 🗂️ Estrutura do Projeto

```
desafio-mosyle/
├── Dockerfile
├── docker-compose.yml
├── docker/nginx/default.conf
├── public/
│   └── index.php
├── src/
│   ├── Controllers/
│   ├── Core/
│   ├── Database/
│   ├── Middleware/
│   ├── Models/
│   └── Services/
├── autoload.php

```

---

## 🔐 Autenticação

A maioria das rotas exige um token JWT no cabeçalho:

```
Authorization: Bearer {{token}}
```

Faça login para obter seu token.

---

## 📌 Endpoints da API

### 🧍‍♂️ Usuário

#### `POST /register` - Criar usuário

- Form Data: `name`, `email`, `password`

#### `POST /login` - Login

- Form Data: `email`, `password`
- Retorno: `{ "token": "..." }`

#### `GET /users` - Listar usuários (requer token)

#### `GET /users/{id}` - Ver usuário (requer token)

#### `POST /users/{id}` - Atualizar (via `_method=PUT`, requer token)

#### `POST /users/{id}` - Deletar (via `_method=DELETE`, requer token)

---

### ☕ Café

#### `POST /coffee/drink/{user_id}` - Registrar consumo

- Form Data: `drink`, `date`

#### `GET /coffee/history/{user_id}` - Histórico diário

#### `GET /coffee/ranking/day/{date}` - Ranking diário

#### `GET /coffee/ranking/lastdays/{days}` - Ranking últimos X dias

---

## 📦 Postman - Uso de Variáveis

Defina um ambiente com:

- `base_url`: `http://localhost:8888`
- `token`: será preenchido automaticamente

---

## 📝 Licença

Este projeto foi desenvolvido como parte de um desafio técnico.
