FROM debian:bookworm-slim

# Define version
ARG PHP_VERSION=8.2
ARG SSP_VERSION=2.1.3

# Install necessary packages
RUN apt-get update \
    && apt-get install -y \
        apache2 \
        php${PHP_VERSION} \
        php${PHP_VERSION}-mysqli \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-pdo-sqlite \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-ldap \
        php${PHP_VERSION}-http \
        php${PHP_VERSION}-raphf \
        curl \
        git \
        unzip \
        wget \
        nano \
        mariadb-server \
        supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG COMPOSER_ALLOW_SUPERUSER=1

# Set the working directory
WORKDIR /

# Add php settings
COPY php-ini-overwrite.ini /etc/php/8.2/apache2/conf.d/php-ini-overwrite.ini

# Install SimpleSAMLphp, setup dirs
RUN wget "https://github.com/simplesamlphp/simplesamlphp/releases/download/v${SSP_VERSION}/simplesamlphp-${SSP_VERSION}-full.tar.gz" \
    && tar xf "simplesamlphp-${SSP_VERSION}-full.tar.gz" \
    && mv "simplesamlphp-${SSP_VERSION}" /var/simplesamlphp \
    && rm "simplesamlphp-${SSP_VERSION}-full.tar.gz" \
    && mkdir -p /var/simplesamlphp/temp \
    && chown -R www-data:www-data /var/simplesamlphp/temp \
    && chmod -R 775 /var/simplesamlphp/temp/ \
    && mkdir /var/simplesamlphp/modules/mymodule \
    && chown -R www-data:www-data /var/simplesamlphp/modules/mymodule

# Enable Apache SSL module and rewrite module
RUN a2enmod ssl && a2enmod rewrite

# Disable mpm_event module and enable mpm_prefork
RUN a2dismod mpm_event && \
    a2enmod mpm_prefork

# Copy Apache sites-enabled files into the container
COPY /proxyconfigs/apache/000-default-le-ssl.conf /etc/apache2/sites-enabled/

# Expose ports 80 and 443 for Apache
EXPOSE 80 443 3306 8000

# Download wait-for-it.sh script
ADD https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh

# Copy SQL scripts to create the database and test RP client
COPY init-db.sql /docker-entrypoint-initdb.d/
COPY client.sql .

# Run the SQL script during image build
RUN service mariadb start \
    && mysql -u root -e "source /docker-entrypoint-initdb.d/init-db.sql" \
    && service mariadb stop


# Install OIDC OP module
RUN cd /var/simplesamlphp/ && \
    /usr/local/bin/composer config --no-plugins allow-plugins.simplesamlphp/composer-module-installer true && \
    /usr/local/bin/composer require simplesamlphp/simplesamlphp-module-oidc:^5.1 && \
    cp modules/oidc/config-templates/module_oidc.php config/

# Install Metarefresh module
RUN cd /var/simplesamlphp/ && \
    /usr/local/bin/composer config --no-plugins allow-plugins.simplesamlphp/composer-module-installer true && \
    /usr/local/bin/composer require simplesamlphp/simplesamlphp-module-metarefresh

# Clone Laravel app from Git (specifically from the dev branch)
RUN git clone -b dev https://github.com/alexarlord-boop/cpcl-app.git /var/www/laravel-app

# Install Laravel dependencies, and generate the application key
RUN cd /var/www/laravel-app && \
    composer install --no-dev --optimize-autoloader && \
    php artisan key:generate

# Grant permissions
RUN chown -R www-data:www-data /var/www/laravel-app && \
    chmod 775 /var/www/laravel-app/public/config/module_metarefresh.php && \
    chmod -R 775 /var/www/laravel-app/storage && \
    chmod -R 775 /var/www/laravel-app/bootstrap/cache

# Transfer scripts
COPY laravel-update.sh /
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chmod +x laravel-update.sh
COPY proxyconfigs/simplesamlphp/bin/initMDSPdo.php /var/simplesamlphp/bin/
COPY proxyconfigs/simplesamlphp/bin/importPdoMetadata.php /var/simplesamlphp/bin/

# Start supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

