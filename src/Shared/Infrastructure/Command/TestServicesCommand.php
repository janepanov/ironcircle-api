<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Command;

use App\Circle\Domain\Repository\CircleRepositoryInterface;
use App\Comment\Domain\Repository\CommentRepositoryInterface;
use App\Post\Domain\Repository\PostRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\Vote\Domain\Repository\VoteRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test:services',
    description: 'Test that all repository services are properly wired'
)]
final class TestServicesCommand extends Command
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CircleRepositoryInterface $circleRepository,
        private readonly PostRepositoryInterface $postRepository,
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly VoteRepositoryInterface $voteRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Service Configuration Test');

        $services = [
            'UserRepository' => get_class($this->userRepository),
            'CircleRepository' => get_class($this->circleRepository),
            'PostRepository' => get_class($this->postRepository),
            'CommentRepository' => get_class($this->commentRepository),
            'VoteRepository' => get_class($this->voteRepository),
        ];

        $io->section('Registered Repository Services:');
        
        foreach ($services as $interface => $implementation) {
            $io->writeln(sprintf(
                '<info>✓</info> %s → <comment>%s</comment>',
                $interface,
                $implementation
            ));
        }

        $io->newLine();
        $io->success('All repository services are properly wired!');

        return Command::SUCCESS;
    }
}
