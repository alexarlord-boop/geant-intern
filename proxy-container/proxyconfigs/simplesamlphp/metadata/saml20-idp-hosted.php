<?php

$metadata['https://alpe3.incubator.geant.org/saml-idp'] = [
    /*
     * The hostname for this IdP. This makes it possible to run multiple
     * IdPs from the same configuration. '__DEFAULT__' means that this one
     * should be used by default.
     */
    'host' => '__DEFAULT__',

    /*
     * The private key and certificate to use when signing responses.
     * These can be stored as files in the cert-directory or retrieved
     * from a database.
     */
    'privatekey' => 'alpe3.incubator.geant.org.pem',
    'certificate' => 'alpe3.incubator.geant.org.crt',

    /*
     * The authentication source which should be used to authenticate the
     * user. This must match one of the entries in config/authsources.php.
     */
    'auth' => 'default-sp',
    'attributes.NameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
    'authproc' => array(
        // Convert LDAP names to oids.
        100 => array('class' => 'core:AttributeMap', 'name2oid'),
    ),
];
