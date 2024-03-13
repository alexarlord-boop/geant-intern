<?php
class EntityTableMapping
{
    private string $tableName;
    private array $fieldMapping;

    public function __construct(string $tableName, array $fieldMapping)
    {
        $this->tableName = $tableName;
        $this->fieldMapping = $fieldMapping;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getFieldMapping(): array
    {
        return $this->fieldMapping;
    }
}

class EntityRepository
{
    private PDO $pdo;
    private EntityTableMapping $tableMapping;

    public function __construct(PDO $pdo, EntityTableMapping $tableMapping)
    {
        $this->pdo = $pdo;
        $this->tableMapping = $tableMapping;
    }

    // Other CRUD methods...

    public function create(EntityDTO $entity): bool
    {
        $tableName = $this->tableMapping->getTableName();
        $fieldMapping = $this->tableMapping->getFieldMapping();

        $fieldValues = [];
        foreach ($fieldMapping as $entityField => $tableField) {
            $fieldValues[$tableField] = $entity->$entityField;
        }

        $columns = implode(', ', array_keys($fieldValues));
        $placeholders = implode(', ', array_fill(0, count($fieldValues), '?'));

        $stmt = $this->pdo->prepare("INSERT INTO $tableName ($columns) VALUES ($placeholders)");

        return $stmt->execute(array_values($fieldValues));
    }
}

// Example usage
$idpMapping = new EntityTableMapping('idp_table', [
    'type' => 'entity_type',
    'name' => 'entity_name',
    'description' => 'entity_description',
    'resource_location' => 'metadata_url',
    'id' => 'entity_id',
    'dynamic_registration' => null, // Nonexistent in idp_table
    'client_secret' => null, // Nonexistent in idp_table
]);

$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
$idpRepository = new EntityRepository($pdo, $idpMapping);

$idpData = [
    'type' => 'saml_idp',
    'name' => 'IDP Name',
    'description' => 'IDP Description',
    'metadata_url' => 'https://example.com/metadata',
    'entity_id' => 'idp_entity_id',
];

$idpEntity = EntityFactory::createEntity(EntityType::IDP, $idpData);

$idpRepository->create($idpEntity);
