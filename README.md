# 🚀 Teste Handix

Aplicação desenvolvida com **Laravel (API REST)** + **Vue.js (SPA dentro do próprio Laravel)** para o teste técnico da Handix.

---

# 📦 Stack utilizada

- Laravel 13
- PHP 8.4 (via Laravel Sail)
- MySQL 8.4
- Vue.js (dentro de `resources/js`)
- Vite (build do frontend)
- Docker (Laravel Sail)

---

# 🧱 Arquitetura

O projeto segue boas práticas modernas:

- **Controller** → camada fina
- **Service Layer** → regras de negócio
- **FormRequest** → validação desacoplada
- **API Resource** → controle de resposta
- **Exception Handling Global**
- **Logging estruturado**

✔️ Código escalável  
✔️ Baixo acoplamento  
✔️ Pronto para produção  

---

# 📌 Funcionalidades

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

## 🔹 Backend

- Validação robusta via FormRequest
- Regras centralizadas no Service
- Tratamento global de exceções
- Paginação nativa
- Logging estruturado

---

## 🔹 Frontend (Vue.js)

O frontend está **integrado dentro do Laravel**:


resources/js


- SPA simples
- Consome API via Axios
- Build com Vite
- Hot reload via porta 5173

---

# ⚙️ Como rodar o projeto

## 📋 Pré-requisitos

- Docker Desktop
- WSL2 (Windows)
- Node.js (opcional, caso rode fora do container)

---

## 🚀 Setup completo (PASSO A PASSO)

### 1. Clonar projeto

```bash
git clone <repo>
cd teste-handix
```

### 2. Configurar ambiente
```
cp .env.example .env
```
### 3. Ajustar variáveis importantes

```
APP_PORT=80
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

⚠️ Porta externa do MySQL está mapeada para 3307, mas internamente é 3306

### 4. Instalar dependências do Laravel
```
composer install
```

### 5. Subir containers (Laravel Sail)
```
./vendor/bin/sail up -d
```

### 6. Gerar chave da aplicação
```
./vendor/bin/sail artisan key:generate
```

### 7. Rodar migrations
```
./vendor/bin/sail artisan migrate
```

## ⚡ Frontend (Vue + Vite)

O frontend roda dentro do mesmo projeto Laravel.

▶️ Instalar dependências
```
./vendor/bin/sail npm install
```

▶️ Rodar ambiente de desenvolvimento (hot reload)
```
./vendor/bin/sail npm run dev
```

Acesse:
```
http://localhost
```

▶️ Build para produção
```
./vendor/bin/sail npm run build
```

## 🔥 Portas utilizadas
| Serviço    | Porta      |
| ---------  | ---------- |
| Laravel    | 80         |
| Vite       | 5173       |
| MySql      | 3307       |


## 📦 Comandos úteis
Subir containers
```
./vendor/bin/sail up -d
```
Parar containers
```
./vendor/bin/sail down
```
Acessar container
```
./vendor/bin/sail shell
```
Rodar artisan
```
./vendor/bin/sail artisan <comando>
```

Rodar npm
```
./vendor/bin/sail npm run dev
```

🧠 Observações importantes

🔹 Vue dentro do Laravel
O frontend NÃO é separado
Está dentro de resources/js
Usa Vite integrado ao Laravel
Compartilha o mesmo container

🔹 Docker + Sail
Ambiente padronizado
PHP 8.4 isolado
MySQL já configurado
Hot reload funcionando

🔹 Banco de dados

Interno (container): mysql:3306

Externo (host): localhost:3307
⚡ Dica (alias)

**Para facilitar comandos:**
```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

Depois disso:
```
sail up -d
sail artisan migrate
```

## 🎯 Considerações finais

Projeto desenvolvido com foco em:

Arquitetura limpa

Separação de responsabilidades

Integração fullstack (Laravel + Vue)

Execução simplificada com Docker