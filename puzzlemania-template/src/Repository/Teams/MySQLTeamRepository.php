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
        INSERT INTO teams(name, numMembers, createdAt, updatedAt)
        VALUES(:name, :numMembers, :createdAt, :updatedAt)
        QUERY;

        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam('name', $teamName, PDO::PARAM_STR);
        $statement->bindParam('numMembers', $numMembers, PDO::PARAM_INT);
        $statement->bindParam('createdAt', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updatedAt', $updatedAt, PDO::PARAM_STR);

        $statement->execute();

        //get the last inserted id
        $statement = $this->databaseConnection->prepare("SELECT LAST_INSERT_ID()");
        $statement->execute();
        $result = $statement->fetchAll();

        return $result[0]["LAST_INSERT_ID()"];
    }

    public function updateTeam(Team $team) {

    }

    public function getTeamByName(string $name) {
        // TODO: Implement getTeamByName() method.
    }

    public function getTeamById(int $id) {
        $query = <<<'QUERY'
        SELECT * FROM teams WHERE id = :id
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

    public function getAllTeams() {
        // TODO: Implement getAllTeams() method.
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