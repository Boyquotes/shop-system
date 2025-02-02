<?php

declare(strict_types=1);

namespace App\Module\User\Domain\Entity;

use App\Module\User\Domain\Enum\UserRole;
use App\Module\User\Domain\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepositoryInterface::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(
    columns: ['email'],
    name: 'user_search_idx'
)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column]
    #[Groups(['default'])]
    private readonly string $id;

    #[ORM\Column(length: 200, unique: true)]
    #[Groups(['default'])]
    private string $email;

    #[ORM\Column]
    #[Groups(['default'])]
    private string $password;

    #[ORM\Column(length: 100)]
    #[Groups(['default'])]
    private string $name;

    #[ORM\Column(length: 100)]
    #[Groups(['default'])]
    private string $surname;

    /**
     * @var string[]
     */
    #[ORM\Column]
    #[Groups(['default'])]
    private array $roles;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['default'])]
    private ?string $lastLoginIp;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['default'])]
    private ?DateTimeImmutable $lastLoginTime;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['default'])]
    private ?string $lastLoginUserAgent;

    #[ORM\Column]
    #[Groups(['default'])]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column]
    #[Groups(['default'])]
    private readonly DateTimeImmutable $createdAt;

    public function __construct(
        string $email,
        string $password,
        string $name,
        string $surname
    ) {
        $this->id = (string) Uuid::v1();
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
        $this->roles = [UserRole::USER->value];
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function isAdmin(): bool
    {
        return in_array(UserRole::ADMIN->value, $this->roles);
    }

    public function getLastLoginIp(): ?string
    {
        return $this->lastLoginIp;
    }

    public function setLastLoginIp(?string $lastLoginIp): self
    {
        $this->lastLoginIp = $lastLoginIp;
        return $this;
    }

    public function getLastLoginTime(): ?DateTimeImmutable
    {
        return $this->lastLoginTime;
    }

    public function setLastLoginTime(DateTimeImmutable $lastLoginTime): self
    {
        $this->lastLoginTime = $lastLoginTime;
        return $this;
    }

    public function getLastLoginUserAgent(): ?string
    {
        return $this->lastLoginUserAgent;
    }

    public function setLastLoginUserAgent(string $lastLoginUserAgent): self
    {
        $this->lastLoginUserAgent = $lastLoginUserAgent;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function eraseCredentials()
    {
    }
}
