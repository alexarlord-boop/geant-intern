<?php

require_once '../vendor/autoload.php';
require_once 'IOUtils.php';
require_once 'EntityDTO.php';

use Symfony\Component\Yaml\Yaml;


$io = new IOUtils();

// Read the YAML file
//$fileContent = $io->readFile('proxy.yaml');
//

//foreach ($entityTypes as $entityType => [$direction, $entityProtocol]) {
//    $entityData = $data[$direction][$entityType] ?? null;
//    if ($entityData) {
//        $entity = EntityFactory::createEntity($entityType, $entityData);
//        print_r($entity);
//        // Handle entity processing based on $entityCategory (saml or oidc)
//        if ($entity->getProtocol() === EntityProtocol::SAML) {
//            $phpMetadataPath = $directionFilepath[$direction];
//
//            // Assuming $srcFilename is meant to be defined here or elsewhere
//            $srcFilename = "some_value";  // Replace with the actual value or logic
//
//            $xmlData = getMetadataFromUrl($entity->getResourceLocation());
//            storeXmlMetadata($xmlData, $srcFilename);
//
//            $phpMetadata = convertXmlToPhp($srcFilename, $phpMetadataPath);
//            storePhpMetadata($phpMetadata);
//
//            $jsonMetadata = convertPhpToJson($phpMetadataPath);
//
//            // TODO: Perform DB operations with $jsonMetadata
//        }
//
//        if ($entity->getProtocol() === EntityProtocol::OIDC) {
//            // TODO:- DB crud
//        }
//
//    }
//}
//

// TODO:- think of rules module
//$rules = $data['rules'] ?? null;


class Parser
{
    private array $entityTypes = [
        'saml_idp' => ['in', 'saml'],
        'saml_idps' => ['in', 'saml'],
        'saml_sp' => ['out', 'saml'],
        'saml_sps' => ['out', 'saml'],
        'oidc_op' => ['in', 'oidc'],
        'oidc_rp' => ['out', 'oidc'],
    ];

    private array $directionFilepath = [
        'in' => 'saml2O-idp-remote.php',
        'out' => 'saml2O-sp-remote.php',
    ];

    private array $entities;

    public function parseYamlFile($fileContent): mixed
    {
        // Parse the YAML content
        return Yaml::parse($fileContent);
    }

    private function setEntities($entities)
    {
        $this->entities = $entities;
    }

    public function getEntities(): array
    {
        return $this->entities;
    }

    public function extractEntities($yamlData): void
    {
        $entitiesTmp = array();
        foreach ($this->entityTypes as $entityType => [$direction, $entityProtocol]) {
            $entityData = $yamlData[$direction][$entityType] ?? null;
            if ($entityData) {
                $entity = EntityFactory::createEntity($entityType, $entityData);
                $entitiesTmp[] = $entity;
            }
        }

        $this->setEntities($entitiesTmp);
    }



    public function extractRules()
    {
        //TODO:-
    }


}