
# Teste Handix

Apliação desenvolvida com Laravel + Vue para o teste técnico da empresa Handix.

## 🚀 Como rodar o projeto localmente (Laravel + Sail)

Este projeto utiliza **Docker com Laravel Sail** para padronizar o ambiente de desenvolvimento.

---

## 📋 Pré-requisitos

Antes de iniciar, você precisa ter instalado:

- Docker Desktop
- WSL2 (Windows)
- Ubuntu (via WSL)

> ⚠️ Importante: Utilize o terminal do Ubuntu (WSL).  
> O Sail **não funciona** em Git Bash, CMD ou PowerShell.

---

## ⚙️ Configuração inicial

### 1. Acessar o projeto

No terminal do Ubuntu (WSL):

```
cd /mnt/c/laragon/www/teste-handix
```

### 2. Copiar arquivos de ambiente
```
cp .env.example .env
``` 

### 3. Ajustar variáveis do banco
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3307
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
``` 

### 4. Subir os containers
```
./vendor/bin/sail up -d
```

### 5. Gerar chave da aplicação
```
./vendor/bin/sail artisan key:generate
```

### 6. Rodar migrations
```
./vendor/bin/sail artisan migrate
```

### 7. Acessar aplicação
```
http://localhost
```

⚠️ Se não abrir

Pode haver conflito de porta (ex: Laragon).

Edite o .env:

```
APP_PORT=8080
```

Depois reinicie:

```
./vendor/bin/sail down
./vendor/bin/sail up -d
```

Acesse:
```
http://localhost:8080
```

## 📦 Comandos úteis

Subir ambiente
```
./vendor/bin/sail up -d
```
Parar ambiente
```
./vendor/bin/sail down
```
Ver containers
```
./vendor/bin/sail ps
```
Rodar comandos Artisan
```
./vendor/bin/sail artisan <comando>
```

## 🧠 Observações importantes
### Docker + WSL

No Docker Desktop, habilite:

Settings → Resources → WSL Integration

Ative a distribuição Ubuntu.

### Conflito de portas
MySQL local costuma usar 3306
Este projeto usa 3307

### ⚡ Dica (opcional)

Criar alias para facilitar:

```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```
Agora você pode usar:

```
sail up -d
sail artisan migrate
```