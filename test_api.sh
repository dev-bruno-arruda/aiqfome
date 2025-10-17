#!/bin/bash

# Autor: Bruno Arruda
# Este script de teste visa testar os endpoints principais da API.

BASE_URL="http://localhost/api"
TOKEN=""

echo "🚀 Testando API de Produtos Favoritos"
echo "====================================="

# 1. Teste de Login
echo "1. Testando login..."
LOGIN_RESPONSE=$(curl -s -X POST $BASE_URL/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }')

echo "Resposta do login: $LOGIN_RESPONSE"

# Extrair token da resposta
TOKEN=$(echo $LOGIN_RESPONSE | grep -o '"token":"[^"]*"' | cut -d'"' -f4)

if [ -z "$TOKEN" ]; then
    echo "❌ Falha no login. Verifique se o usuário admin@example.com existe."
    echo "Resposta completa: $LOGIN_RESPONSE"
    exit 1
fi

echo "✅ Login realizado com sucesso!"
echo "Token: $TOKEN"
echo ""

# 2. Teste de Listar Clientes
echo "2. Testando listagem de clientes..."
CUSTOMERS_RESPONSE=$(curl -s -X GET $BASE_URL/customers \
  -H "Authorization: Bearer $TOKEN")

echo "Resposta da listagem: $CUSTOMERS_RESPONSE"
echo ""

# 3. Teste de Criar Cliente
echo "3. Testando criação de cliente..."
CREATE_RESPONSE=$(curl -s -X POST $BASE_URL/customers \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "name": "Teste Cliente",
    "email": "teste@example.com"
  }')

echo "Resposta da criação: $CREATE_RESPONSE"
echo ""


# 4. Teste de Criar Cliente Repetido
echo "4. Testando criação de cliente Repetido.."
CREATE_RESPONSE=$(curl -s -X POST $BASE_URL/customers \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "name": "Teste Cliente",
    "email": "teste@example.com"
  }')

echo "Resposta da criação: $CREATE_RESPONSE"
echo ""

# 5. Teste de Adicionar Favorito
echo "4. Testando adição de favorito..."
FAVORITE_RESPONSE=$(curl -s -X POST $BASE_URL/customers/1/favorites \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "product_id": 1
  }')

echo "Resposta da adição de favorito: $FAVORITE_RESPONSE"
echo ""

# 56. Teste de Listar Favoritos
echo "5. Testando listagem de favoritos..."
FAVORITES_RESPONSE=$(curl -s -X GET $BASE_URL/customers/1/favorites \
  -H "Authorization: Bearer $TOKEN")

echo "Resposta da listagem de favoritos: $FAVORITES_RESPONSE"
echo ""

# 7. Teste de Verificar Favorito
echo "6. Testando verificação de favorito..."
CHECK_RESPONSE=$(curl -s -X GET $BASE_URL/customers/1/favorites/1/check \
  -H "Authorization: Bearer $TOKEN")

echo "Resposta da verificação: $CHECK_RESPONSE"
echo ""

echo "✅ Testes concluídos!"
echo ""
echo "📝 Para testar manualmente, use os seguintes comandos:"
echo ""
echo "Login:"
echo "curl -X POST $BASE_URL/login -H 'Content-Type: application/json' -d '{\"email\":\"admin@example.com\",\"password\":\"password\"}'"
echo ""
echo "Listar clientes:"
echo "curl -X GET $BASE_URL/customers -H 'Authorization: Bearer YOUR_TOKEN'"
echo ""
echo "Criar cliente:"
echo "curl -X POST $BASE_URL/customers -H 'Content-Type: application/json' -H 'Authorization: Bearer YOUR_TOKEN' -d '{\"name\":\"João Silva\",\"email\":\"joao@example.com\"}'"
echo ""
echo "Adicionar favorito:"
echo "curl -X POST $BASE_URL/customers/1/favorites -H 'Content-Type: application/json' -H 'Authorization: Bearer YOUR_TOKEN' -d '{\"product_id\":1}'"
echo ""
echo "Listar favoritos:"
echo "curl -X GET $BASE_URL/customers/1/favorites -H 'Authorization: Bearer YOUR_TOKEN'"
