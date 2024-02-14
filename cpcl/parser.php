<?php

require_once '../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

// Read the YAML file
$fileContent = file_get_contents('proxy.yaml');

// Parse the YAML content
$data = Yaml::parse($fileContent);

// Access the parsed data
$samlIdpConfig = $data['in']['saml_idp'];
$samlOPConfig = $data['in']['oidc_op'];
$samlSpConfig = $data['out']['saml_sp'];
$oidcRpConfig = $data['out']['oidc_rp'];

// Access the rules
$rules = $data['rules'];

// Display some information
echo "SAML IDP Configuration:\n";
print_r($samlIdpConfig);

echo "\nSAML SP Configuration:\n";
print_r($samlSpConfig);

echo "\nOIDC RP Configuration:\n";
print_r($oidcRpConfig);

echo "\nRules:\n";
print_r($rules);
