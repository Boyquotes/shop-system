<?php

declare(strict_types=1);

namespace App\Module\Product\Application\Voter;

use App\Module\Product\Domain\Entity\Product;
use App\Module\User\Domain\Entity\User;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductsVoter extends Voter
{
    public const GET_ALL = 'GET_ALL_PRODUCTS';
    public const CREATE = 'CREATE_PRODUCT';
    public const SHOW = 'SHOW_PRODUCT';
    public const UPDATE = 'UPDATE_PRODUCT';
    public const DELETE = 'DELETE_PRODUCT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::GET_ALL, self::CREATE, self::SHOW, self::UPDATE, self::DELETE])
            && ($subject instanceof Product || $subject === null);
    }

    /**
     * @param Product $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::GET_ALL => $this->canGetAll(),
            self::CREATE => $this->canCreate($user),
            self::SHOW => $this->canShow(),
            self::UPDATE => $this->canUpdate($user),
            self::DELETE => $this->canDelete($user),
            default => throw new Exception('Invalid attribute.')
        };
    }

    private function canGetAll(): bool
    {
        return true;
    }

    private function canCreate(User $user): bool
    {
        return $user->isAdmin();
    }

    private function canShow(): bool
    {
        return true;
    }

    private function canUpdate(User $user): bool
    {
        return $user->isAdmin();
    }

    private function canDelete(User $user): bool
    {
        return $user->isAdmin();
    }
}
