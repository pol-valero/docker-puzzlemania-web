<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Teams;

use PDO;
use Salle\PuzzleMania\Model\Team;

final class MySQLTeamRepository implements TeamRepository {
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $databaseConnection;

    public function __construct(PDO $database) {
        $this->databaseConnection = $database;
    }

    public function createTeam(Team $team): void {
        // TODO: Implement createTeam() method.
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
                ->setNumMembers($row->numMembers);
        }
        return null;
    }

    public function getAllTeams(): ?array {
        // TODO: Implement getAllTeams() method.
        return null;
    }

    public function increaseTeamScore(int $id, int $gamePoints): void {
        // TODO: Implement method. It should add the gamePoints to current team score
    }
}