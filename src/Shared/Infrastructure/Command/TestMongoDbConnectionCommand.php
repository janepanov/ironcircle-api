<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Command;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test:mongodb',
    description: 'Test MongoDB connection and list collections'
)]
final class TestMongoDbConnectionCommand extends Command
{
    public function __construct(
        private readonly DocumentManager $documentManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $io->title('MongoDB Connection Test');

            // Test connection
            $client = $this->documentManager->getClient();
            $databases = $client->listDatabases();
            
            $io->success('Successfully connected to MongoDB');
            
            // Get current database info
            $database = $this->documentManager->getDocumentDatabase(\App\User\Infrastructure\Doctrine\Document\UserDocument::class);
            $io->info('Database: ' . $database->getDatabaseName());
            
            // List collections
            $collections = $database->listCollections();
            $collectionNames = [];
            
            foreach ($collections as $collection) {
                $collectionNames[] = $collection->getName();
            }
            
            if (empty($collectionNames)) {
                $io->warning('No collections found. Run schema:create to create indexes.');
            } else {
                $io->section('Available Collections:');
                $io->listing($collectionNames);
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('MongoDB connection failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
