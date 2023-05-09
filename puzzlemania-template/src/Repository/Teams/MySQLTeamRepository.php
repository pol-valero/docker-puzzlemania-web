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
}