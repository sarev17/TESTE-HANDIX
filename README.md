# 🚀 Teste Handix

Aplicação desenvolvida com **Laravel (API REST)** para o teste técnico da empresa Handix.

---
# Requisitos

* Laravel 13
* PHP 8.4 
* Docker
* Vue.js

# 🧱 Arquitetura e boas práticas aplicadas

Este projeto foi desenvolvido seguindo princípios modernos de arquitetura e boas práticas:

### ✅ Camadas da aplicação

* **Controller** → camada fina (sem regra de negócio)
* **Service Layer** → centraliza regras de negócio
* **FormRequest** → validação desacoplada
* **Resource (API Resource)** → controle de payload
* **Exception Handling Global** → padronização de erros
* **Logging estruturado** → observabilidade

---

# 📌 Principais funcionalidades implementadas

## 🔹 API RESTful

CRUD completo de contatos:

| Método    | Endpoint              |
| --------- | --------------------- |
| GET       | /api/v1/contacts      |
| POST      | /api/v1/contacts      |
| GET       | /api/v1/contacts/{id} |
| PUT/PATCH | /api/v1/contacts/{id} |
| DELETE    | /api/v1/contacts/{id} |

---

## 🔹 Service Layer (Camada de Serviço)

Toda regra de negócio foi isolada em serviços:

* Criação
* Atualização
* Busca
* Exclusão
* Filtros

✔️ Facilita testes
✔️ Evita lógica no controller
✔️ Código escalável

---

## 🔹 FormRequest + Validação

Validação desacoplada usando **BaseFormRequest**:

* Regras dinâmicas por método (POST vs PUT/PATCH)
* Validação parcial com `sometimes`
* Mensagens personalizadas em português

Exemplo:

```
PUT /api/v1/contacts/1
{
  "notes": "Atualizado"
}
```

✔️ Suporta atualização parcial
✔️ Retorna erros padronizados

---

## 🔹 Padronização de respostas

Todas as respostas seguem o padrão:

### ✅ Sucesso

```
{
  "success": true,
  "message": "Mensagem",
  "data": {}
}
```

### ❌ Erro

```
{
  "success": false,
  "message": "Erro de validação",
  "errors": {}
}
```

---

## 🔹 API Resource (Controle de payload)

Uso de **ContactResource** para evitar overfetching:

✔️ Remove campos desnecessários
✔️ Não expõe timestamps
✔️ Controla exatamente o retorno

---

## 🔹 Paginação

A listagem de contatos retorna:

* current_page
* per_page
* total
* last_page
* data

✔️ Ideal para frontend

---

## 🔹 Tratamento global de exceções

Centralizado em:

```
bootstrap/app.php
```

* `ServiceException` → erros de negócio
* `Throwable` → fallback global

✔️ Evita try/catch nos controllers
✔️ Mantém padrão consistente

---

## 🔹 Logging estruturado

Logs implementados no Service:

* `error()` → falhas críticas
* `warning()` → registros não encontrados

Exemplo:

```
Log::error('Erro ao criar contato', [
  'data' => $data,
  'service' => ContactService::class
]);
```

✔️ Facilita debug
✔️ Pronto para produção

---

## 🔹 Versionamento de API

```
/api/v1/contacts
```

✔️ Permite evolução futura

---

## 🔹 Documentação automática

Gerada com:

* Scribe

Disponível em:

```
http://localhost/docs
```

✔️ Teste de endpoints direto no browser

---

# ⚙️ Como rodar o projeto localmente

## 📋 Pré-requisitos

* Docker Desktop
* WSL2 (Windows)
* Ubuntu (via WSL)

⚠️ Utilize o terminal do Ubuntu (WSL)

---

## 🚀 Setup

### 1. Acessar projeto

```
cd /mnt/c/laragon/www/teste-handix
```

---

### 2. Configurar ambiente

```
cp .env.example .env
```

---

### 3. Configurar banco

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3307
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

---

### 4. Subir containers

```
./vendor/bin/sail up -d
```

---

### 5. Gerar chave

```
./vendor/bin/sail artisan key:generate
```

---

### 6. Rodar migrations

```
./vendor/bin/sail artisan migrate
```

---

### 7. Acessar

```
http://localhost
```

---

## ⚠️ Porta alternativa

Se houver conflito:

```
APP_PORT=8080
```

Depois:

```
./vendor/bin/sail down
./vendor/bin/sail up -d
```

---

# 📦 Comandos úteis

Subir:

```
./vendor/bin/sail up -d
```

Parar:

```
./vendor/bin/sail down
```

Ver containers:

```
./vendor/bin/sail ps
```

Rodar comandos:

```
./vendor/bin/sail artisan <comando>
```

---

# 🧠 Observações

### Docker + WSL

Ativar em:

```
Docker Desktop → Settings → Resources → WSL Integration
```

---

### Banco de dados

* MySQL local: 3306
* Projeto: 3307

---

# ⚡ Dica

Criar alias:

```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

---

# 🎯 Considerações finais

Este projeto foi desenvolvido com foco em:

* Arquitetura limpa
* Boas práticas de API
* Escalabilidade
* Manutenibilidade

---

🔥 Projeto pronto para evolução e uso em produção.
