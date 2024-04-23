<?php

$config = [];

// Populate the configuration array
$config = [
    'items_per_page' => 20,
    'authCodeDuration' => 'PT10M',
    'refreshTokenDuration' => 'P1M',
    'accessTokenDuration' => 'PT1H',
    'cron_tag' => 'hourly',
    'signer' => 'Lcobucci\JWT\Signer\Rsa\Sha256',
    'auth' => 'default-sp',
    'useridattr' => 'urn:oid:1.3.6.1.4.1.5923.1.1.1.1',
    'permissions' => [
        'attribute' => 'eduPersonEntitlement',
        'client' => [
            'urn:example:oidc:manage:client',
        ],
    ],
    'authproc.oidc' => [],
    'acrValuesSupported' => [],
    'authSourcesToAcrValuesMap' => [],
    'forcedAcrValueForCookieAuthentication' => null,
];

// Database connection parameters
$host = 'localhost';
$dbname = 'simplesamlphp';
$username = 'ptest';
$password = '1111';

// Create a PDO connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
echo PHP_EOL;

$sql = "SELECT * FROM rulesets WHERE isActive = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();

if ($stmt === false) {
    die("Error executing query: " . $conn->errorInfo()[2]);
}

// Fetch results
$rulesets = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo print_r($rulesets, true);

// Populate translate table with 'pass' oidc<->saml conversions
$translate = [];
foreach ($rulesets as $ruleset) {
//    echo print_r($ruleset);
    $key = $ruleset['out']; // Assuming 'out' is the column containing the OIDC claim
    $value = $ruleset['in']; // Assuming 'in' is the column containing the SAML attribute
    $translate[$key] = [$value];
}
echo print_r($translate, true);
$config['translate'] = $translate;

return $config;
