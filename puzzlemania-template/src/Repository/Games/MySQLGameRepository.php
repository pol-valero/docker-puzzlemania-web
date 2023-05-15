<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Games;

use PDO;
use Salle\PuzzleMania\Model\Game;

final class MySQLGameRepository implements GameRepository {
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $databaseConnection;

    public function __construct(PDO $database) {
        $this->databaseConnection = $database;
    }

    public function createGame(Game $game): void {
        $query = <<<'QUERY'
        INSERT INTO games(user_id, score, riddle1_id, riddle2_id, riddle3_id, createdAt)
        VALUES(:user_id, :score, :riddle1_id, :riddle2_id, :riddle3_id, :createdAt)
        QUERY;

        $user_id = $game->userId();
        $score = $game->score();
        $riddle1_id = $game->riddle1Id();
        $riddle2_id = $game->riddle2Id();
        $riddle3_id = $game->riddle3Id();
        $createdAt = $game->createdAt()->format(self::DATE_FORMAT);

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('user_id', $user_id, PDO::PARAM_STR);
        $statement->bindParam('score', $score, PDO::PARAM_STR);
        $statement->bindParam('riddle1_id', $riddle1_id, PDO::PARAM_STR);
        $statement->bindParam('riddle2_id', $riddle2_id, PDO::PARAM_STR);
        $statement->bindParam('riddle3_id', $riddle3_id, PDO::PARAM_STR);
        $statement->bindParam('createdAt', $createdAt, PDO::PARAM_STR);

        $statement->execute();
    }

    public function getLastGame() {
        $query = <<<'QUERY'
        SELECT * FROM games ORDER BY id DESC LIMIT 1
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->execute();

        $count = $statement->rowCount();

        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);
            return $row;
        }
        return null;
    }

    public function getGameById(int $id) {
        // TODO: Implement getGameById() method.
    }

    public function getGamesByUser(int $user_id) {
        // TODO: Implement getGamesByUser() method.
    }

    public function getAllGames() {
        // TODO: Implement getAllGames() method.
    }
}