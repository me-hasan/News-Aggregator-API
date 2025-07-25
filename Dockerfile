FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /

# Install required packages
RUN apk --no-cache add \
    postgresql-dev \
    openssh \
    bash \
    curl \
    tar \
    supervisor && \  
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Copy application files
COPY ./src /
COPY ./php.ini /usr/local/etc/php/
COPY ./pg_hba.conf /etc/postgresql/pg_hba.conf
COPY ./postgres.conf /etc/postgresql/postgresql.conf

# Copy Supervisor configuration file into the container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf 
COPY supervisord.conf /etc/supervisord.conf

EXPOSE 9000
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

