FROM debian:bookworm-slim

# Define PHP version
ARG PHP_VERSION=8.2

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
        curl \
        git \
        unzip \
        wget \
        nano \
        mariadb-server \
        supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Set the working directory
WORKDIR /

# Install SimpleSAMLphp, setup dirs
RUN wget "https://github.com/simplesamlphp/simplesamlphp/releases/download/v2.1.2/simplesamlphp-2.1.2.tar.gz" \
    && tar xf simplesamlphp-2.1.2.tar.gz \
    && mv simplesamlphp-2.1.2 /var/simplesamlphp \
    && rm simplesamlphp-2.1.2.tar.gz \
    && mkdir -p /var/simplesamlphp/temp \
    && chown -R www-data:www-data /var/simplesamlphp/temp \
    && chmod -R 775 /var/simplesamlphp/temp/ \
    && mkdir /var/simplesamlphp/modules/mymodule \
    && chown -R www-data:www-data /var/simplesamlphp/modules/mymodule


# Enable Apache SSL module
RUN a2enmod ssl

# Disable mpm_event module and enable mpm_prefork
RUN a2dismod mpm_event && \
    a2enmod mpm_prefork

# Copy Apache sites-enabled files into the container
COPY /proxyconfigs/apache/000-default-le-ssl.conf /etc/apache2/sites-enabled/

# Expose ports 80 and 443 for Apache
EXPOSE 80 443

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

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG COMPOSER_ALLOW_SUPERUSER=1

# Install OIDC OP module
RUN cd /var/simplesamlphp/ && \
    /usr/local/bin/composer config --no-plugins allow-plugins.simplesamlphp/composer-module-installer true && \
    /usr/local/bin/composer require simplesamlphp/simplesamlphp-module-oidc:^5.1 && \
    cp modules/oidc/config-templates/module_oidc.php config/

# Configure supervisord
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Start supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]





