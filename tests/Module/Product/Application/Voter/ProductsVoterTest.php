<?php

declare(strict_types=1);

namespace App\Tests\Module\Product\Application\Voter;

use App\Module\Product\Application\Voter\ProductsVoter;
use App\Module\User\Domain\Entity\User;
use App\Tests\AbstractUnitTestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ProductsVoterTest extends AbstractUnitTestCase
{
    protected ProductsVoter $voter;
    protected TokenInterface $token;
    protected User $user;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->voter = new ProductsVoter();
        $this->token = $this->createMock(TokenInterface::class);

        $this->user = $this->createMock(User::class);
        $this->user
            ->method('isAdmin')
            ->willReturn(false);

        $this->admin = $this->createMock(User::class);
        $this->admin
            ->method('isAdmin')
            ->willReturn(true);
    }

    public function testUserCanGetAll(): void
    {
        $this->token
            ->method('getUser')
            ->willReturn($this->user);

        $this->assertTrue(
            $this->useMethod(
                object: $this->voter,
                method: 'voteOnAttribute',
                args: [
                    ProductsVoter::GET_ALL,
                    null,
                    $this->token
                ]
            )
        );
    }

    public function testUserCantNew(): void
    {
        $this->token
            ->method('getUser')
            ->willReturn($this->user);

        $this->assertFalse(
            $this->useMethod(
                object: $this->voter,
                method: 'voteOnAttribute',
                args: [
                    ProductsVoter::CREATE,
                    null,
                    $this->token
                ]
            )
        );
    }

    public function testAdmincanCreate(): void
    {
        $this->token
            ->method('getUser')
            ->willReturn($this->admin);

        $this->assertTrue(
            $this->useMethod(
                object: $this->voter,
                method: 'voteOnAttribute',
                args: [
                    ProductsVoter::CREATE,
                    null,
                    $this->token
                ]
            )
        );
    }

    public function testUserCanShow(): void
    {
        $this->token
            ->method('getUser')
            ->willReturn($this->user);

        $this->assertTrue(
            $this->useMethod(
                object: $this->voter,
                method: 'voteOnAttribute',
                args: [
                    ProductsVoter::SHOW,
                    null,
                    $this->token
                ]
            )
        );
    }

    public function testUserCantUpdate(): void
    {
        $this->token
            ->method('getUser')
            ->willReturn($this->user);

        $this->assertFalse(
            $this->useMethod(
                object: $this->voter,
                method: 'voteOnAttribute',
                args: [
                    ProductsVoter::UPDATE,
                    null,
                    $this->token
                ]
            )
        );
    }

    public function testAdminCanUpdate(): void
    {
        $this->token
            ->method('getUser')
            ->willReturn($this->admin);

        $this->assertTrue(
            $this->useMethod(
                object: $this->voter,
                method: 'voteOnAttribute',
                args: [
                    ProductsVoter::UPDATE,
                    null,
                    $this->token
                ]
            )
        );
    }

    public function testUserCantDelete(): void
    {
        $this->token
            ->method('getUser')
            ->willReturn($this->user);

        $this->assertFalse(
            $this->useMethod(
                object: $this->voter,
                method: 'voteOnAttribute',
                args: [
                    ProductsVoter::DELETE,
                    null,
                    $this->token
                ]
            )
        );
    }

    public function testAdminCanDelete(): void
    {
        $this->token
            ->method('getUser')
            ->willReturn($this->admin);

        $this->assertTrue(
            $this->useMethod(
                object: $this->voter,
                method: 'voteOnAttribute',
                args: [
                    ProductsVoter::DELETE,
                    null,
                    $this->token
                ]
            )
        );
    }
}
