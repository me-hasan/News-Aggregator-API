FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /

# Install required packages
RUN apk --no-cache add \
    postgresql-dev \
    openssh \
    bash \
    curl \
    tar

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Copy application files
COPY ./src /
COPY ./php.ini /usr/local/etc/php/
COPY ./pg_hba.conf /etc/postgresql/pg_hba.conf
COPY ./postgres.conf /etc/postgresql/postgresql.conf

# Optional MySQL config
# COPY ./my.cnf /etc/mysql/my.cnf

EXPOSE 9000
CMD ["php-fpm"]
