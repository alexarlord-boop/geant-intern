<?php

$config = [

    /* This is the name of this authentication source, and will be used to access it later. */
    'default-sp' => [
        'saml:SP',
        'entityID' => 'https://alpe3.incubator.geant.org/saml-sp',

        /*
         * The entity ID of the IdP this should SP should contact.
         * Can be NULL/unset, in which case the user will be shown a list of available IdPs.
         */
        'idp' => null,
        // 'idp' => 'https://alpe1.incubator.geant.org/saml-idp',

        /*
         * If SP behind the SimpleSAMLphp in IdP/SP proxy mode requests
         * AuthnContextClassRef, decide whether the AuthnContextClassRef will be
         * processed by the IdP/SP proxy or if it will be passed to the original
         * IdP in front of the IdP/SP proxy.
         */
//        'proxymode.passAuthnContextClassRef' => true,

        /*
         * The attributes parameter must contain an array of desired attributes by the SP.
         * The attributes can be expressed as an array of names or as an associative array
         * in the form of 'friendlyName' => 'name'. This feature requires 'name' to be set.
         * The metadata will then be created as follows:
         * <md:RequestedAttribute FriendlyName="friendlyName" Name="name" />
         */
//      'attributes' => [
        //          'useridattr' => ['urn:oid:0.9.2342.19200300.100.1.1', 'urn:oid:1.3.6.1.4.1.5923.1.1.1.1'],
        //    ],


    ],


    'example-userpass' => [
        'exampleauth:UserPass',
        'student:studentpass' => [
            'uid' => ['student'],
            'eduPersonAffiliation' => ['member', 'student'],
        ],
        'employee:employeepass' => [
            'uid' => ['employee'],
            'eduPersonAffiliation' => ['member', 'employee'],
        ],
    ],



    'admin' => [
        'core:AdminPassword',
    ],

];