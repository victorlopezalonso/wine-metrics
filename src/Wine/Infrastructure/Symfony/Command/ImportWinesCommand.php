<?php

namespace App\Wine\Infrastructure\Symfony\Command;

use App\Wine\Domain\Entity\Wine;
use App\Wine\Domain\Repository\WineRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:wines:import',
    description: 'Import wines from a CSV file with the format: name, year'
)]
class ImportWinesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WineRepositoryInterface $wineRepository,
    ) {
        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'Path to the CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('file');

        if (!file_exists($filePath)) {
            $io->error("File not found: $filePath");

            return Command::FAILURE;
        }

        $io->title('Importing Wines from CSV');

        if (!$handle = fopen($filePath, 'r')) {
            $io->error('Could not open the file.');

            return Command::FAILURE;
        }

        $header = fgetcsv($handle);

        if (false === $header || count($header) < 2) {
            $io->error('Invalid CSV format. Expected columns: name, year');

            return Command::FAILURE;
        }

        $wineCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            [$name, $year] = $row;

            if ($this->wineRepository->findByNameAndYear($name, (int) $year)) {
                $io->warning("Wine '$name' ($year) already exists. Skipping...");
                continue;
            }

            $this->entityManager->persist(new Wine(
                name: $name,
                year: (int) $year
            ));

            ++$wineCount;
        }

        fclose($handle);

        $this->entityManager->flush();

        $io->success("Successfully imported $wineCount wines.");

        return Command::SUCCESS;
    }
}
