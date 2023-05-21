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

    public function createGame(Game $game): Game {
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

        return $this->getLastGame();
    }

    public function getLastGame(): ?Game {
        $query = <<<'QUERY'
        SELECT * FROM games ORDER BY id DESC LIMIT 1
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->execute();

        $count = $statement->rowCount();

        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);

            return Game::create()
                ->setId($row->id)
                ->setUserId($row->user_id)
                ->setRiddle1Id($row->riddle1_id)
                ->setRiddle2Id($row->riddle2_id)
                ->setRiddle3Id($row->riddle3_id)
                ->setScore($row->score);
        }

        return null;
    }

    public function getGameById(int $id): ?Game {
        $query = <<<'QUERY'
        SELECT * FROM games WHERE id = :id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('id', $id, PDO::PARAM_INT);

        $statement->execute();

        $count = $statement->rowCount();
        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);

            return Game::create()
                ->setId($row->id)
                ->setUserId($row->user_id)
                ->setRiddle1Id($row->riddle1_id)
                ->setRiddle2Id($row->riddle2_id)
                ->setRiddle3Id($row->riddle3_id)
                ->setScore($row->score);
        }

        return null;
    }

    public function getGamesByUser(int $user_id): ?array {
        $query = <<<'QUERY'
        SELECT * FROM games WHERE user_id = :user_id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('user_id', $user_id, PDO::PARAM_INT);

        $statement->execute();

        $count = $statement->rowCount();
        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);
            return $row;
        }

        return null;
    }

    public function getAllGames(): ?array {
        $query = <<<'QUERY'
        SELECT * FROM games
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->execute();

        $games = [];

        $count = $statement->rowCount();
        if ($count > 0) {
            $rows = $statement->fetchAll();

            for ($i = 0; $i < $count; $i++) {
                $game = Game::create()
                    ->setId(intval($rows[$i]['id']))
                    ->setUserId(intval($rows[$i]['user_id']))
                    ->setRiddle1Id(intval($rows[$i]['riddle1_id']))
                    ->setRiddle2Id(intval($rows[$i]['riddle2_id']))
                    ->setRiddle3Id(intval($rows[$i]['riddle3_id']))
                    ->setScore(intval($rows[$i]['score']))
                    ->setCreatedAt(date_create_from_format('Y-m-d H:i:s', $rows[$i]['createdAt']));
                $games[] = $game;
            }
        }

        return $games;
    }

    public function updateGameScore(int $id, int $score): void {
        $query = <<<'QUERY'
        UPDATE games SET score = :score WHERE id = :id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('id', $id, PDO::PARAM_STR);
        $statement->bindParam('score', $score, PDO::PARAM_STR);

        $statement->execute();
    }
}