# Comece com a imagem oficial do PHP 8.2 com Apache
FROM php:8.2-apache

# 1. Instale as dependências básicas + as necessárias para adicionar o repo (curl, gnupg)
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    unixodbc-dev \
    libzip-dev \
    unzip

# 2. Adicione a chave GPG da Microsoft
RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg

# 3. Adicione o repositório da Microsoft para o Debian 12 (Bookworm), que é a base do php:8.2
RUN curl -fsSL https://packages.microsoft.com/config/debian/12/prod.list > /etc/apt/sources.list.d/mssql-release.list

# 4. Atualize os pacotes e instale o driver ODBC (ACEITANDO O EULA)
RUN apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 \
    && rm -rf /var/lib/apt/lists/*

# 5. Agora, instale as extensões PHP (como você já fazia)
RUN pecl install sqlsrv pdo_sqlsrv

# 6. Ative as extensões
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv