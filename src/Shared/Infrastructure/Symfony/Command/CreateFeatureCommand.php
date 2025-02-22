<?php

namespace App\Shared\Infrastructure\Symfony\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'ddd:feature:create',
    description: 'Creates the folder structure and the entity for a new DDD feature.',
)]
class CreateFeatureCommand extends Command
{
    private string $featureName;
    private string $basePath;

    public const SRC_PATH = 'src';

    public const DOCTRINE_PERSISTENCE = 'Doctrine';
    public const GUZZLE_PERSISTENCE = 'Guzzle';
    public const PERSISTENCE_TYPES = [self::DOCTRINE_PERSISTENCE, self::GUZZLE_PERSISTENCE, 'None'];
    public const DEFAULT_PERSISTENCE_TYPE = self::DOCTRINE_PERSISTENCE;

    public const ROUTES_FILE = '/config/routes.yaml';

    public const FOLDERS = [
        'AppCommand' => 'Application/Command',
        'AppQuery' => 'Application/Query',
        'DomainEntity' => 'Domain/Entity',
        'DomainRepository' => 'Domain/Repository',
        'InfrastructureController' => 'Infrastructure/Symfony/Controller',
        'InfrastructureRequest' => 'Infrastructure/Symfony/Http/Request',
    ];

    public const DOCTRINE_FOLDERS = [
        'InfrastructureDoctrine' => 'Infrastructure/Doctrine/Persistence',
        'InfrastructureDoctrineMapping' => 'Infrastructure/Doctrine/Mapping',
    ];

    public const GUZZLE_FOLDERS = [
        'InfrastructureGuzzle' => 'Infrastructure/Rest',
        'InfrastructureGuzzleClient' => 'Infrastructure/Rest/Client',
    ];

    public const FEATURE_FILES = [
        'DomainEntity' => '/#featureName#.php',
        'DomainRepository' => '/#featureName#RepositoryInterface.php',
        'InfrastructureController' => '/#featureName#Controller.php',
        'InfrastructureRequest' => '/#featureName#Request.php',
        'InfrastructureDoctrine' => '/Doctrine#featureName#Repository.php',
        'InfrastructureDoctrineMapping' => '/#featureName#.orm.xml',
        'InfrastructureGuzzle' => '/Guzzle#featureName#Repository.php',
        'InfrastructureGuzzleClient' => '/#featureName#ApiClient.php',
    ];

    public function __construct(private readonly Filesystem $filesystem, private readonly string $projectDir)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('feature_name', InputArgument::REQUIRED, 'The name of the feature (e.g., Task)');
    }

    protected function showDoctrineConfigurationInfo(SymfonyStyle $io): void
    {
        $infrastructureDoctrineMappingNamespace = $this->getNamespaceFor('InfrastructureDoctrineMapping', self::DOCTRINE_PERSISTENCE);
        $domainEntityNamespace = $this->getNamespaceFor('DomainEntity');

        $dir = str_replace('App\\', '%kernel.project_dir%/src/', $infrastructureDoctrineMappingNamespace);
        $dir = str_replace('\\', '/', $dir);

        $io->info("** Remember to add the following configuration to your doctrine.yaml mappings section: **
            $this->featureName:
                type: xml
                is_bundle: false
                dir: '$dir'
                prefix: $domainEntityNamespace
        ");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->featureName = ucfirst((string) $input->getArgument('feature_name'));

        $persistenceType = $this->askForPersistenceType($io);
        $this->basePath = self::SRC_PATH . '/' . $this->featureName;

        try {
            $this->createFolders($persistenceType);
            $this->createFiles($persistenceType);

            $this->addFeatureRoute();

            $io->success("Feature '$this->featureName' structure created successfully!");

            if (self::DOCTRINE_PERSISTENCE === $persistenceType) {
                $this->showDoctrineConfigurationInfo($io);
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('An error occurred: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }

    protected function askForPersistenceType(SymfonyStyle $io): string
    {
        return $io->choice(
            question: 'Choose the persistence type for this feature:',
            choices: self::PERSISTENCE_TYPES,
            default: self::DEFAULT_PERSISTENCE_TYPE
        );
    }

    protected function createFolders(string $persistenceType): void
    {
        $folders = array_map(fn ($folder) => "$this->basePath/$folder", array_values(self::FOLDERS));

        if (self::DOCTRINE_PERSISTENCE === $persistenceType) {
            $folders[] = array_map(fn ($folder) => "$this->basePath/$folder", array_values(self::DOCTRINE_FOLDERS));
        }

        if (self::GUZZLE_PERSISTENCE === $persistenceType) {
            $folders[] = array_map(fn ($folder) => "$this->basePath/$folder", array_values(self::GUZZLE_FOLDERS));
        }

        foreach ($folders as $folder) {
            $this->filesystem->mkdir($folder, 0755);
        }
    }

    protected function getFileFor(string $key, ?string $persistenceType = null): string
    {
        $file = self::FEATURE_FILES[$key];

        $folder = match ($persistenceType) {
            self::DOCTRINE_PERSISTENCE => self::DOCTRINE_FOLDERS[$key],
            self::GUZZLE_PERSISTENCE => self::GUZZLE_FOLDERS[$key],
            default => self::FOLDERS[$key],
        };

        return "$this->basePath/$folder/" . str_replace('#featureName#', $this->featureName, $file);
    }

    protected function getNamespaceFor(string $key, ?string $persistenceType = null): string
    {
        $namespace = "App\\$this->featureName";

        $folder = match ($persistenceType) {
            self::DOCTRINE_PERSISTENCE => self::DOCTRINE_FOLDERS[$key],
            self::GUZZLE_PERSISTENCE => self::GUZZLE_FOLDERS[$key],
            default => self::FOLDERS[$key],
        };

        $subNamespace = str_replace('/', '\\', $folder);

        return "$namespace\\$subNamespace";
    }

    protected function createFiles(string $persistenceType): void
    {
        $files = [];

        $files[$this->getFileFor('DomainEntity')] = $this->getEntityTemplate();
        $files[$this->getFileFor('DomainRepository')] = $this->getRepositoryInterfaceTemplate();
        $files[$this->getFileFor('InfrastructureController')] = $this->getControllerTemplate();
        $files[$this->getFileFor('InfrastructureRequest')] = $this->getRequestTemplate();

        if (self::DOCTRINE_PERSISTENCE === $persistenceType) {
            $files[$this->getFileFor('InfrastructureDoctrine', self::DOCTRINE_PERSISTENCE)] = $this->getDoctrineRepositoryTemplate();
            $files[$this->getFileFor('InfrastructureDoctrineMapping', self::DOCTRINE_PERSISTENCE)] = $this->getOrmMappingTemplate();
        }

        if (self::GUZZLE_PERSISTENCE === $persistenceType) {
            $files[$this->getFileFor('InfrastructureGuzzle', self::GUZZLE_PERSISTENCE)] = $this->getGuzzleRepositoryTemplate();
            $files[$this->getFileFor('InfrastructureGuzzleClient', self::GUZZLE_PERSISTENCE)] = $this->getGuzzleApiClientTemplate();
        }

        foreach ($files as $filePath => $content) {
            if (!$this->filesystem->exists($filePath)) {
                $this->filesystem->dumpFile($filePath, $content);
            }
        }
    }

    private function addFeatureRoute(): void
    {
        $routesFile = $this->projectDir . self::ROUTES_FILE;
        $lowerFeatureName = strtolower($this->featureName);

        $routeConfiguration = <<<YAML


api_$lowerFeatureName:
    resource: ../src/$this->featureName/Infrastructure/Symfony/Controller
    type: attribute
    prefix: /api
    
YAML;
        file_put_contents($routesFile, $routeConfiguration, FILE_APPEND);
    }

    /**
     * Get the template for the entity class.
     */
    private function getEntityTemplate(): string
    {
        $namespace = $this->getNamespaceFor('DomainEntity');

        return <<<PHP
<?php

namespace $namespace;

class {$this->featureName}
{
    public function __construct(
        public ?int \$id = null,
        public ?string \$name = null,
    ) {
    }
}
PHP;
    }

    /**
     * Get the template for the repository interface.
     */
    private function getRepositoryInterfaceTemplate(): string
    {
        $namespace = $this->getNamespaceFor('DomainRepository');
        $domainEntityNamespace = $this->getNamespaceFor('DomainEntity');

        return <<<PHP
<?php

namespace $namespace;

use $domainEntityNamespace\\$this->featureName;

interface {$this->featureName}RepositoryInterface
{
    public function save({$this->featureName} \$entity): void;

    public function findById(int \$id): ?{$this->featureName};

    public function all(): array;

    public function delete({$this->featureName} \$entity): void;
}
PHP;
    }

    /**
     * Get the template for the Doctrine repository class.
     */
    private function getDoctrineRepositoryTemplate(): string
    {
        $namespace = $this->getNamespaceFor('InfrastructureDoctrine', self::DOCTRINE_PERSISTENCE);
        $domainRepositoryNamespace = $this->getNamespaceFor('DomainRepository');
        $domainEntityNamespace = $this->getNamespaceFor('DomainEntity');

        return <<<PHP
<?php

namespace $namespace;

use $domainRepositoryNamespace\\{$this->featureName}RepositoryInterface;
use $domainEntityNamespace\\{$this->featureName};
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Doctrine{$this->featureName}Repository extends ServiceEntityRepository implements {$this->featureName}RepositoryInterface
{
    public function __construct(ManagerRegistry \$registry)
    {
        parent::__construct(\$registry, {$this->featureName}::class);
    }
    
    public function save({$this->featureName} \$entity): void
    {
        \$this->getEntityManager()->persist(\$entity);
        \$this->getEntityManager()->flush();
    }

    public function findById(int \$id): ?{$this->featureName}
    {
        return \$this->find(['id' => \$id]);
    }

    public function all(): array
    {
        return \$this->findAll();
    }

    public function delete({$this->featureName} \$entity): void
    {
        \$this->getEntityManager()->remove(\$entity);
        \$this->getEntityManager()->flush();
    }
}
PHP;
    }

    /**
     * Get the template for the ORM mapping file.
     */
    private function getOrmMappingTemplate(): string
    {
        $lowerFeatureName = strtolower($this->featureName);
        $entityNamespace = $this->getNamespaceFor('DomainEntity');

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                      http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    <entity name="$entityNamespace\\{$this->featureName}" table="{$lowerFeatureName}">
        <id name="id" type="integer" column="id">
            <generator strategy="AUTO"/>
        </id>
        <field name="name" type="string" length="255"/>
    </entity>
</doctrine-mapping>
XML;
    }

    /**
     * Get the template for the controller class.
     */
    private function getControllerTemplate(): string
    {
        $lowerFeatureName = strtolower($this->featureName);
        $namespace = $this->getNamespaceFor('InfrastructureController');

        return <<<PHP
<?php

namespace $namespace;

use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class {$this->featureName}Controller extends AbstractController
{
    #[Tag(name: '$this->featureName')]
    #[Route('/$lowerFeatureName', name: '{$lowerFeatureName}_', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse(['message' => '$this->featureName Controller']);
    }
}
PHP;
    }

    /**
     * Get the template for the request class.
     */
    private function getRequestTemplate(): string
    {
        $namespace = $this->getNamespaceFor('InfrastructureRequest');

        return <<<PHP
<?php

namespace $namespace;

use Symfony\Component\Validator\Constraints as Assert;

class {$this->featureName}Request
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string \$name;
}
PHP;
    }

    /**
     * Get the template for the Guzzle repository class.
     */
    private function getGuzzleRepositoryTemplate(): string
    {
        $namespace = $this->getNamespaceFor('InfrastructureGuzzle', self::GUZZLE_PERSISTENCE);
        $domainRepositoryNamespace = $this->getNamespaceFor('DomainRepository');
        $domainEntityNamespace = $this->getNamespaceFor('DomainEntity');
        $infrastructureGuzzleClientNamespace = $this->getNamespaceFor('InfrastructureGuzzleClient', self::GUZZLE_PERSISTENCE);

        return <<<PHP
<?php

namespace $namespace;

use $domainEntityNamespace\\{$this->featureName};
use $domainRepositoryNamespace\\{$this->featureName}RepositoryInterface;
use $infrastructureGuzzleClientNamespace\\{$this->featureName}ApiClient;

class Guzzle{$this->featureName}Repository implements {$this->featureName}RepositoryInterface
{
    public function __construct(private readonly {$this->featureName}ApiClient \$apiClient)
    {
    }

    public function save($this->featureName \$entity): void
    {
        // TODO: Implement save() method.
    }

    public function findById(int \$id): ?$this->featureName
    {
        // TODO: Implement findById() method.
        return null;
    }

    public function all(): array
    {
        // TODO: Implement all() method.
        return [];
    }

    public function delete($this->featureName \$entity): void
    {
        // TODO: Implement delete() method.
    }
}
PHP;
    }

    /**
     * Get the template for the Guzzle API client class.
     */
    private function getGuzzleApiClientTemplate(): string
    {
        $lowerFeatureName = strtolower($this->featureName);

        $namespace = $this->getNamespaceFor('InfrastructureGuzzleClient', self::GUZZLE_PERSISTENCE);

        return <<<PHP
<?php

namespace $namespace;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class {$this->featureName}ApiClient
{
    private Client \$client;

    public function __construct()
    {
        \$this->client = new Client([
            'base_uri' => 'https://api.example.com/',
            'timeout'  => 5.0,
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function getData(): array
    {
        \$response = \$this->client->get('/{$lowerFeatureName}');
        return json_decode(\$response->getBody()->getContents(), true);
    }
}
PHP;
    }
}
