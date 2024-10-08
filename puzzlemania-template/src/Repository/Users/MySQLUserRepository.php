<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Users;

use PDO;
use Salle\PuzzleMania\Model\User;

final class MySQLUserRepository implements UserRepository {
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $databaseConnection;

    public function __construct(PDO $database) {
        $this->databaseConnection = $database;
    }

    public function createUser(User $user): void {
        $query = <<<'QUERY'
        INSERT INTO users(email, password, createdAt, updatedAt, profile_picture)
        VALUES(:email, :password, :createdAt, :updatedAt, :profile_picture)
        QUERY;

        $email = $user->email();
        $password = $user->password();
        $createdAt = $user->createdAt()->format(self::DATE_FORMAT);
        $updatedAt = $user->updatedAt()->format(self::DATE_FORMAT);
        $defaultPicture = 'profile_placeholder.png';


        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->bindParam('password', $password, PDO::PARAM_STR);
        $statement->bindParam('createdAt', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updatedAt', $updatedAt, PDO::PARAM_STR);
        $statement->bindParam('profile_picture', $defaultPicture, PDO::PARAM_STR);

        $statement->execute();
    }

    public function updateUser(User $user) {

        $query = <<<'QUERY'
        UPDATE users SET team = :team, updatedAt = :updatedAt WHERE id = :id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);


        $team = $user->getTeam();

        $statement->bindParam('team', $team, PDO::PARAM_INT);
        $dateTime = $user->updatedAt()->format(self::DATE_FORMAT);
        $statement->bindParam('updatedAt', $dateTime, PDO::PARAM_STR);
        $id = $user->getId();
        $statement->bindParam('id', $id, PDO::PARAM_INT);

        $statement->execute();

    }

    public function getUserByEmail(string $email) {
        $query = <<<'QUERY'
        SELECT * FROM users WHERE email = :email
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('email', $email, PDO::PARAM_STR);

        $statement->execute();

        $count = $statement->rowCount();
        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);
            return $row;
        }
        return null;
    }

    public function getUserById(int $id) {
        $query = <<<'QUERY'
        SELECT * FROM users WHERE id = :id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('id', $id, PDO::PARAM_INT);

        $statement->execute();

        $count = $statement->rowCount();
        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);
            return $row;
        }
        return null;
    }

    public function getAllUsers() {
        $query = <<<'QUERY'
        SELECT * FROM users
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->execute();

        $users = [];

        $count = $statement->rowCount();
        if ($count > 0) {
            $rows = $statement->fetchAll();

            for ($i = 0; $i < $count; $i++) {
                $user = User::create()
                    ->setId(intval($rows[$i]['id']))
                    ->setEmail($rows[$i]['email'])
                    //->setPassword($rows[$i]['password']) - don't ever expose pswd!!!!
                    ->setCreatedAt(date_create_from_format('Y-m-d H:i:s', $rows[$i]['createdAt']))
                    ->setUpdatedAt(date_create_from_format('Y-m-d H:i:s', $rows[$i]['updatedAt']));
                $users[] = $user;
            }
        }
        return $users;
    }

    public function setProfilePicture(int $id, string $profilePicture){
        $query = <<<'QUERY'
        UPDATE users SET profile_picture = :profilePicture WHERE id = :id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('id', $id, PDO::PARAM_INT);
        $statement->bindParam('profilePicture', $profilePicture, PDO::PARAM_STR);

        $statement->execute();
    }
}
