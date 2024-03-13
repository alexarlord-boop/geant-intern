<?php

$config = array (
    'enable.saml20-idp' => true,
    'baseurlpath' => 'https://alpe3.incubator.geant.org/simplesaml/',
    'secretsalt' => '9ynycq856wnq1zwleuze2zzki51ck3pb',
    'technicalcontact_name' => 'Alex Petrunin',
    'technicalcontact_email' => 'petrunina602@gmail.com',
    'auth.adminpassword' => '1111',
    'timezone' => 'Europe/Berlin',
    'tempdir' => '/var/simplesamlphp/temp',
    'metadatadir' => '/var/simplesamlphp/metadata',
    'logging.handler' => 'file',
    'module.enable' => array(
        'exampleauth' => true,
        'saml' => true,
        'admin' => true,
        'core' => null,
        'cron' => true,
        'oidc' => true,
        'mymodule' => true,
    ),

    'database.dsn' => 'mysql:host=localhost;dbname=simplesamlphp;charset=utf8',
    'database.username' => 'ptest',
    'database.password' => '1111',

    'theme.use' => 'mymodule:fancytheme',

    'metadata.sources' => array(
        array('type' => 'flatfile'),  // comment this line for next line source usage
        array('type' => 'pdo',
            'dsn' => 'mysql:host=localhost;dbname=simplesamlphp;charset=utf8',
            'username' => 'ptest',
            'password' => '1111',
            'prefix' => '', // Adjust if you have a table prefix
            'query' => 'SELECT metadata FROM saml_metadata WHERE entityid = :entityid',
            'map' => array(
                'entityid' => 'entityid',
                'metadata' => 'metadata',
            ),
        ),
    ),
);
