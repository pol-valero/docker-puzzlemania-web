<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Users;

use Salle\PuzzleMania\Model\User;

interface UserRepository {
    public function createUser(User $user): void;
    public function getUserByEmail(string $email);
    public function getUserById(int $id);
    public function getAllUsers();
}
