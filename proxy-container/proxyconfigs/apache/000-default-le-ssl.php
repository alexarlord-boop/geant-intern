<VirtualHost *:443>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/laravel-app/public
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    SSLEngine on

    ServerName alpe3.incubator.geant.org
    SetEnv SIMPLESAMLPHP_CONFIG_DIR /var/simplesamlphp/config
    Alias /simplesaml /var/simplesamlphp/public

    <Directory /var/simplesamlphp/public>
    Require all granted
    </Directory>

    <Directory /var/www/laravel-app/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
    </Directory>

<!--    <Directory /var/www/html/sesstest>-->
<!--    AuthType shibboleth-->
<!--    ShibRequestSetting requireSession 1-->
<!--    require valid-user-->
<!--    </Directory>-->


    Include /etc/letsencrypt/options-ssl-apache.conf
    SSLCertificateFile /etc/letsencrypt/live/alpe3.incubator.geant.org/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/alpe3.incubator.geant.org/privkey.pem


</VirtualHost>

