<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Teams;

use DateTime;
use PDO;
use Salle\PuzzleMania\Model\Team;

final class MySQLTeamRepository implements TeamRepository {
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $databaseConnection;

    public function __construct(PDO $database) {
        $this->databaseConnection = $database;
    }

    public function createTeam(string $teamName) {
        $numMembers = 1;
        $createdAt = new DateTime();
        $updatedAt = new DateTime();

        $createdAt = $createdAt->format(self::DATE_FORMAT);
        $updatedAt = $updatedAt->format(self::DATE_FORMAT);

        $query = <<<'QUERY'
        INSERT INTO teams(name, numMembers, score, createdAt, updatedAt)
        VALUES(:name, :numMembers, :score, :createdAt, :updatedAt)
        QUERY;

        $score = 0;

        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam('name', $teamName, PDO::PARAM_STR);
        $statement->bindParam('numMembers', $numMembers, PDO::PARAM_INT);
        $statement->bindParam('score', $score, PDO::PARAM_INT);
        $statement->bindParam('createdAt', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updatedAt', $updatedAt, PDO::PARAM_STR);

        $statement->execute();

        //get the last inserted id
        $statement = $this->databaseConnection->prepare("SELECT LAST_INSERT_ID()");
        $statement->execute();
        $result = $statement->fetchAll();

        return $result[0]["LAST_INSERT_ID()"];
    }

    public function updateTeam(int $teamId, int $numMembers, DateTime $updatedAt) {
        $query = <<<'QUERY'
        UPDATE teams SET numMembers = numMembers + :numMembers, updatedAt = :updatedAt WHERE id = :id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $dateTime = $updatedAt->format(self::DATE_FORMAT);

        $statement->bindParam('numMembers', $numMembers, PDO::PARAM_INT);
        $statement->bindParam('updatedAt', $dateTime, PDO::PARAM_STR);
        $statement->bindParam('id', $teamId, PDO::PARAM_INT);

        $statement->execute();
    }

    public function getTeamByName(string $name): ?Team {
        // TODO: Implement getTeamByName() method.
        return null;
    }

    public function getTeamById(int $id): ?Team {
        $query = <<<'QUERY'
        SELECT * FROM teams WHERE id = :id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('id', $id, PDO::PARAM_INT);

        $statement->execute();

        $count = $statement->rowCount();
        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);

            return Team::create()
                ->setId($row->id)
                ->setName($row->name)
                ->setNumMembers($row->numMembers)
                ->setScore($row->score);
        }
        return null;
    }

    public function getAllTeams(): ?array {
        // TODO: Implement getAllTeams() method.
        return null;
    }

    public function increaseTeamScore(int $id, int $gamePoints): void {
        $query = <<<'QUERY'
        UPDATE teams SET score = score + :score, updatedAt = :updatedAt WHERE id = :id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $dateTime = new DateTime();
        $updatedAt = $dateTime->format(self::DATE_FORMAT);

        $statement->bindParam('id', $id, PDO::PARAM_INT);
        $statement->bindParam('score', $gamePoints, PDO::PARAM_INT);
        $statement->bindParam('updatedAt', $updatedAt, PDO::PARAM_STR);

        $statement->execute();
    }

    public function getIncompleteTeams() {
        $query = <<<'QUERY'
        SELECT id, name, numMembers FROM teams WHERE numMembers <= 1
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->execute();

        $rows = $statement->fetchAll();

        $count = $statement->rowCount();

       if ($count > 0) {
            return $rows;
        }

        return null;

    }

    public function getTeamMembers(int $teamId) {
        $query = <<<'QUERY'
        SELECT email FROM users WHERE team = :teamId
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('teamId', $teamId, PDO::PARAM_INT);

        $statement->execute();

        $rows = $statement->fetchAll();

        $count = $statement->rowCount();
        if ($count > 0) {
            return $rows;
        }

        return null;
    }
}