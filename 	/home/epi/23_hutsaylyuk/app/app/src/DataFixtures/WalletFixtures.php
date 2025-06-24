<?php

/**
 * This file is part of the Finanse project.
 *
 * @author Sofiya Hutsaylyuk
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Fixture for generating random wallets for users.
 */
class WalletFixtures extends AbstractBaseFixtures implements FixtureGroupInterface
{
    /**
     * Loads sample wallets into the database.
     */
    protected function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        /** @var User[] $users */
        $users = $this->manager->getRepository(User::class)->findAll();

        $this->createMany(10, 'wallet', function (int $i) use ($users): Wallet {
            $wallet = new Wallet();
            $wallet->setName($this->faker->word . ' wallet');
            $wallet->setBalance((string) $this->faker->randomFloat(2, 0, 1000));
            $wallet->setCreatedAt(new \DateTimeImmutable());
            $wallet->setUpdatedAt(new \DateTimeImmutable());

            if (!empty($users)) {
                $wallet->setUser($this->faker->randomElement($users));
            }

            $this->setReference('wallet_' . $i, $wallet); // zmienione z addReference()

            return $wallet;
        });

        $this->manager->flush();
    }

    public static function getGroups(): array
    {
        return ['wallet'];
    }
}
