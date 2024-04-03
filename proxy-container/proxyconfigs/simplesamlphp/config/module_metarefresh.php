<?php



$config = [
    'sets' => [
        'saml_idp' => [
            'cron' => ['frequent'],
            'sources' => [

            ],
            'expireAfter' => 60 * 60 * 24 * 4, // Maximum 4 days cache time.
            'outputDir' => 'metadata/shared/saml_idp',
            'outputFormat' => 'flatfile',
        ],
        'saml_idps' => [
            'cron' => ['frequent'],
            'sources' => [

            ],
            'expireAfter' => 60 * 60 * 24 * 4, // Maximum 4 days cache time.
            'outputDir' => 'metadata/shared/saml_idps',
            'outputFormat' => 'flatfile',
        ],
        'saml_sp' => [
            'cron' => ['frequent'],
            'sources' => [

            ],
            'expireAfter' => 60 * 60 * 24 * 4, // Maximum 4 days cache time.
            'outputDir' => 'metadata/shared/saml_sp',
            'outputFormat' => 'flatfile',
        ],
        'saml_sps' => [
            'cron' => ['frequent'],
            'sources' => [

            ],
            'expireAfter' => 60 * 60 * 24 * 4, // Maximum 4 days cache time.
            'outputDir' => 'metadata/shared/saml_sps',
            'outputFormat' => 'flatfile',
        ],
    ]
];
