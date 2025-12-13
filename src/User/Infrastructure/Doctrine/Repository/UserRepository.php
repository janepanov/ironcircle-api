<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Username;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserRole;
use App\User\Infrastructure\Doctrine\Document\UserDocument;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

final class UserRepository implements UserRepositoryInterface
{
    private DocumentRepository $repository;

    public function __construct(private readonly DocumentManager $documentManager)
    {
        $this->repository = $documentManager->getRepository(UserDocument::class);
    }

    public function save(User $user): void
    {
        $document = $this->repository->find($user->id()->value());

        if ($document === null) {
            $document = $this->toDocument($user);
            $this->documentManager->persist($document);
        } else {
            $this->updateDocument($document, $user);
        }

        $this->documentManager->flush();
    }

    public function findById(UserId $id): ?User
    {
        $document = $this->repository->find($id->value());

        return $document ? $this->toDomain($document) : null;
    }

    public function findByEmail(Email $email): ?User
    {
        $document = $this->repository->findOneBy(['email' => $email->value()]);

        return $document ? $this->toDomain($document) : null;
    }

    public function findByUsername(Username $username): ?User
    {
        $document = $this->repository->findOneBy(['username' => $username->value()]);

        return $document ? $this->toDomain($document) : null;
    }

    public function existsByEmail(Email $email): bool
    {
        return $this->repository->findOneBy(['email' => $email->value()]) !== null;
    }

    public function existsByUsername(Username $username): bool
    {
        return $this->repository->findOneBy(['username' => $username->value()]) !== null;
    }

    public function delete(User $user): void
    {
        $document = $this->repository->find($user->id()->value());

        if ($document !== null) {
            $this->documentManager->remove($document);
            $this->documentManager->flush();
        }
    }

    private function toDocument(User $user): UserDocument
    {
        return new UserDocument(
            $user->id()->value(),
            $user->username()->value(),
            $user->email()->value(),
            $user->passwordHash(),
            $user->role()->value,
            $user->createdAt(),
            $user->isActive()
        );
    }

    private function updateDocument(UserDocument $document, User $user): void
    {
        $document->setUsername($user->username()->value());
        $document->setEmail($user->email()->value());
        $document->setPasswordHash($user->passwordHash());
        $document->setRole($user->role()->value);
        $document->setUpdatedAt($user->updatedAt());
        $document->setIsActive($user->isActive());
    }

    private function toDomain(UserDocument $document): User
    {
        return User::create(
            UserId::fromString($document->getId()),
            new Username($document->getUsername()),
            new Email($document->getEmail()),
            $document->getPasswordHash(),
            UserRole::from($document->getRole())
        );
    }
}
