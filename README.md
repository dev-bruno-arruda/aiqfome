# 🍕 AIQFome API

API REST para sistema de gerenciamento de produtos favoritos, desenvolvida com Laravel 11 e documentada com Swagger.

Utilizei Laravel por ter muita familiaridade com o framework. Tenho vários projetos pessoais que utilizam basicamente a mesma estruta de autenticação e autorização, resources, services, query filter, etc.
O Uso do Makefile é opcional, porém muito recomendado. Também é uma prática em projetos, pelo menos para facilitar no desenvolvimento.
Até mesmo esse Readme já possui esse formato há pelo menos 5 anos. :)

## 🚀 Características

- **Autenticação JWT** com Laravel Sanctum
- **Internacionalização (i18n)** completa (PT-BR/EN)
- **Documentação Swagger** interativa
- **Sistema de Roles** (admin/employee)
- **CRUD de Clientes** com paginação e busca
- **Sistema de Favoritos** integrado com API externa
- **Validação padronizada** com respostas estruturadas
- **Docker** com Laravel Sail

## 📋 Pré-requisitos

### Sempre que posso, utilizo um Makefile para facilitar a execução de comandos do ambiente Docker do projeto (Recomendado)

- **Docker** e **Docker Compose**
- **Make** (geralmente já instalado no macOS/Linux)
- **Git**

### P.S.: OS comandos foram testados em Ubunto e OSX

### Para desenvolvimento manual

- Docker e Docker Compose
- PHP 8.2+
- Composer
- MySQL 8.0+

### Instalação do Make (se necessário)

**macOS:**

```bash
# Boa Notícia para usuários de OSX: Make já vem instalado no macOS
# But, Se necessário, instale via Homebrew:
brew install make
```

**Ubuntu/Debian:**

```bash
sudo apt update
sudo apt install make
```

## 🛠️ Instalação

### 🚀 Instalação Rápida com Makefile (Recomendado)

1. **Clone o repositório:**

```bash
git clone git@github.com:dev-bruno-arruda/aiqfome.git
cd aiqfome
```

2. **Execute a instalação completa:**

```bash
make up
```

3. **Execute as migrações e seeders:**

```bash
make mig
```

3. **Gere a documentação Swagger:**

```bash
make swagger
```

**Pronto!** A API estará rodando em [http://localhost/api](http://localhost/api)

### 📋 Comandos Makefile Disponíveis

```bash
make help          # Mostra todos os comandos disponíveis
make dup           # Inicia os containers Docker
make ddw           # Para os containers Docker
make drs           # Reinicia os containers Docker
make dci           # Instala dependências do Composer
make dcu           # Atualiza dependências do Composer
make mysql         # Acessa o container MySQL
make php           # Acessa o container PHP
make test          # Executa testes com cobertura
make lvtest        # Executa testes do Laravel
make mig           # Executa migrações com seeders
make rmig          # Reverte migrações
make clear         # Limpa caches do Laravel
make job           # Executa queue worker
make link          # Cria link de storage
make swagger       # Gera documentação Swagger
```

### 🔧 Instalação Manual (Alternativa)

1. **Clone o repositório:**

```bash
git clone git@github.com:dev-bruno-arruda/aiqfome.git
cd aiqfome
```

2. **Configure o ambiente:**

```bash
cp .env.example .env
```

3. **Instale as dependências:**

```bash
composer install
```

4. **Configure o banco de dados no `.env`:**

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=aiqfome
DB_USERNAME=sail
DB_PASSWORD=password
```

5. **Execute as migrações e seeders:**

```bash
./vendor/bin/sail artisan migrate --seed
```

6. **Inicie os containers:**

```bash
./vendor/bin/sail up -d
```

## 📖 Documentação da API

A documentação completa está disponível via Swagger UI:

**🔗 [http://localhost/api/documentation](http://localhost/api/documentation)**

### Autenticação

1. **Login:**

```bash
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'
```

2. **Use o token retornado:**

```bash
curl -X GET http://localhost/api/profile \
  -H "Authorization: Bearer {seu_token}" \
  -H "Accept: application/json"
```

### Usuários Padrão

- **Admin:** `admin@example.com` / `password123`
- **Employee:** `employee@example.com` / `password123`

## 🌐 Endpoints Principais

### Endpoints de Autenticação

- `POST /api/login` - Realizar login
- `POST /api/logout` - Realizar logout
- `GET /api/profile` - Obter perfil do usuário
- `GET /api/admin-check` - Verificar acesso de administrador
- `GET /api/token-status` - Verificar status do token

### Clientes

- `GET /api/customers` - Listar clientes (com paginação e busca)
- `POST /api/customers` - Criar cliente
- `GET /api/customers/{id}` - Obter cliente
- `PUT /api/customers/{id}` - Atualizar cliente
- `DELETE /api/customers/{id}` - Remover cliente

### Favoritos

- `GET /api/customers/{id}/favorites` - Listar favoritos do cliente
- `POST /api/customers/{id}/favorites` - Adicionar favorito
- `GET /api/customers/{id}/favorites/{productId}` - Obter favorito
- `DELETE /api/customers/{id}/favorites/{productId}` - Remover favorito
- `GET /api/customers/{id}/favorites/{productId}/check` - Verificar se é favorito

## 🌍 Internacionalização

A API suporta múltiplos idiomas com chaves estruturadas:

### Formato de Resposta

```json
{
  "message": {
    "key": "auth.login.success",
    "text": "Login realizado com sucesso"
  },
  "status": "success",
  "data": {
    "token": "1|abc123...",
    "role": "admin"
  }
}
```

### Idiomas Suportados
- **Português (pt-BR)** - Padrão
- **Inglês (en)**

### Exemplo de Uso
```bash
# Resposta em português
curl -H "Accept-Language: pt-BR" http://localhost/api/login

# Resposta em inglês  
curl -H "Accept-Language: en" http://localhost/api/login
```

## 🔐 Sistema de Roles

- **admin:** Acesso total + rota de teste de autorização
- **employee:** Acesso padrão aos recursos

## 🧪 Testes

Para facilitar a excução do teste, criei um script para testar as principais rotas.

```bash
chmod +x ./test_api.sh
./test_api.sh
```

Ou, se preferir, pode ser testado via curl:

```bash
# Login
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email": "admin@example.com", "password": "password123"}'

# Criar cliente
curl -X POST http://localhost/api/customers \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name": "Bruno Arruda", "email": "barruda@outlook.com"}'
```

### Configuração do Swagger

A documentação é gerada automaticamente:

```bash
# Regenerar documentação
./vendor/bin/sail artisan l5-swagger:generate
```

### Com Makefile (Recomendado)
```bash
# Desenvolvimento
make help          # Lista todos os comandos
make dup           # Inicia containers
make ddw           # Para containers
make drs           # Reinicia containers
make mig           # Migrações com seeders
make test          # Executa testes
make swagger       # Gera documentação

# Acesso aos containers
make php           # Acessa container PHP
make mysql         # Acessa container MySQL

# Manutenção
make clear         # Limpa caches
make dci           # Instala dependências
make dcu           # Atualiza dependências
```

### Com Laravel Sail (Alternativa)

```bash
# Laravel Sail
./vendor/bin/sail up -d              # Iniciar containers
./vendor/bin/sail down               # Parar containers
./vendor/bin/sail artisan migrate   # Executar migrações
./vendor/bin/sail artisan tinker    # Console interativo

# Desenvolvimento
./vendor/bin/sail artisan route:list           # Listar rotas
./vendor/bin/sail artisan l5-swagger:generate  # Gerar documentação
./vendor/bin/sail composer install            # Instalar dependências
```

## 🐛 Troubleshooting

### Problemas Comuns

1. **Erro "make: command not found":**
   - **macOS:** `brew install make`
   - **Ubuntu/Debian:** `sudo apt install make`

2. **Erro de conexão com banco:**
   - Verifique se o container MySQL está rodando: `make check_containers`
   - Confirme as credenciais no `.env`
   - Reinicie os containers: `make drs`

3. **Token expirando rapidamente:**
   - Verifique a variável `SANCTUM_EXPIRATION_DAYS`
   - Execute `make clear`

4. **Swagger não carrega:**
   - Execute `make swagger`
   - Verifique se o arquivo `storage/api-docs/api-docs.json` existe

5. **Containers não iniciam:**
   - Verifique se Docker está rodando
   - Execute `make ddw` e depois `make dup`
   - Verifique logs: `docker logs aiqfome-laravel.test-1`

6. **Permissões de arquivo:**
   - Execute `make clear` para limpar caches
   - Verifique permissões do diretório `storage/`
   - caso seja necessário, execute o comando

```bash
   make php
   chmod 777 -R storage
```

## 📝 Changelog

### v1.0.0

- ✅ Sistema de autenticação com Sanctum
- ✅ CRUD completo de clientes
- ✅ Sistema de favorito
- ✅ Internacionalização (PT-BR/EN)
- ✅ Documentação Swagger
- ✅ Sistema de roles
- ✅ Validação padronizada
- ✅ Middleware de formatação de erros

- **Email:** [bruno.arruda@aiqfome.com](mailto:bruno.arruda@aiqfome.com) (Quem sabe?)
- **Documentação:** [http://localhost/api/documentation](http://localhost/api/documentation)

### Qualquer problema encontrato, pode ser reportado para [brunowolly@hotmail.com](mailto:brunowolly@hotmail.com)
