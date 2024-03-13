<?php
require_once "interface.php";

class Section
{
    const IN = 'in';
    const OUT = 'out';
    const RULES = 'rules';
    const ALL = 'all';
}
class EntityProtocol
{
    const SAML = 'saml';
    const OIDC = 'oidc';
}
class EntityType
{
    const IDP = 'saml_idp';
    const IDPS = 'saml_idps';
    const SP = 'saml_sp';
    const SPS = 'saml_sps';
    const OP = 'oidc_op';
    const RP = 'oidc_rp';
}

class EntityDTO implements EntityInterface
{
    private string $section;
    private string $protocol;

    private string $type;
    private string $name;
    private string $description;
    private string $resource_location; // idp: metadata_url	op: discovery_url	rp: redirect_uri
    private ?string $id; // idp, sp: entityID or rp: client_id | nullable
    private ?string $dynamic_registration; // only in RP | nullable
    private ?string $client_secret; // only in RP | nullable

    public function __construct(
        string $section,
        string $protocol,
        string  $type,
        string  $name,
        string  $description,
        string  $resource_location,
        ?string $id = null,
        ?string $dynamic_registration = null,
        ?string $client_secret = null
    )
    {
        $this->section = $section;
        $this->protocol = $protocol;
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;
        $this->resource_location = $resource_location;
        $this->id = $id;
        $this->dynamic_registration = $dynamic_registration;
        $this->client_secret = $client_secret;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getResourceLocation(): string
    {
        return $this->resource_location;
    }

    public function getDynamicRegistration(): ?string
    {
        return $this->dynamic_registration;
    }

    public function getClientSecret(): ?string
    {
        return $this->client_secret;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getProtocol(): string {
        return $this->protocol;
    }

    public function getSection(): string
    {
        return $this->section;
    }

    public function setSection(string $section): void
    {
        $this->$section = $section;
    }
}

class EntityFactory
{

    public static function createEntity(string $type, array $data): ?EntityDTO
    {
        return match ($type) {
            EntityType::IDP => self::createIdp($data),
            EntityType::IDPS => self::createIdps($data),
            EntityType::SP => self::createSp($data),
            EntityType::SPS => self::createSps($data),
            EntityType::OP => self::createOp($data),
            EntityType::RP => self::createRp($data),
            default => null,
        };
    }

    public static function createIdp(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::IN,
            EntityProtocol::SAML,
            EntityType::IDP,
            $data['name'],
            $data['description'],
            $data['metadata_url'],
            $data['entityid'],
            null,
            null
        );
    }

    public static function createIdps(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::IN,
            EntityProtocol::SAML,
            EntityType::IDPS,
            $data['name'],
            $data['description'],
            $data['metadata_url'],
            null,
            null,
            null
        );
    }

    public static function createOp(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::IN,
            EntityProtocol::OIDC,
            EntityType::OP,
            $data['name'],
            $data['description'],
            $data['discovery_url'],
            null,
            null,
            null
        );
    }

    public static function createSp(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::OUT,
            EntityProtocol::SAML,
            EntityType::SP,
            $data['name'],
            $data['description'],
            $data['metadata_url'],
            $data["entityid"],
            null,
            null
        );
    }

    public static function createSps(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::OUT,
            EntityProtocol::SAML,
            EntityType::SPS,
            $data['name'],
            $data['description'],
            $data['metadata_url'],
            null,
            null,
            null
        );
    }

    public static function createRp(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::OUT,
            EntityProtocol::OIDC,
            EntityType::RP,
            $data['name'],
            $data['description'],
            $data['redirect_uri'],
            $data["client_id"],
            $data["dynamic_registration"],
            $data["client_secret"]
        );
    }
}