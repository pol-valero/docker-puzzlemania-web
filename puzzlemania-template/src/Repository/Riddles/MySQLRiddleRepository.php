<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Riddles;

use PDO;
use Salle\PuzzleMania\Model\Riddle;

final class MySQLRiddleRepository implements RiddleRepository {
    private PDO $databaseConnection;

    public function __construct(PDO $database) {
        $this->databaseConnection = $database;
    }

    public function getRiddles(): array {
        $query = <<<'QUERY'
        SELECT * FROM riddles
        QUERY;

        $statement = $this->databaseConnection->prepare($query);
        $statement->execute();
        $riddles = [];

        $count = $statement->rowCount();
        if ($count > 0) {
            $rows = $statement->fetchAll();
            for ($i = 0; $i < $count; $i++) {
                $riddle = Riddle::create()
                    ->setId(intval($rows[$i]['riddle_id']))
                    ->setUserId(intval($rows[$i]['user_id']))
                    ->setRiddle(strval($rows[$i]['riddle']))
                    ->setAnswer(strval($rows[$i]['answer']));
                $riddles[] = $riddle;
            }
        }
        return $riddles;
    }

    public function getRiddleById(int $id): ?Riddle {
        $query = <<<'QUERY'
        SELECT * FROM riddles WHERE riddle_id = :id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $count = $statement->rowCount();
        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);
            $riddle = Riddle::create()
                ->setId(intval($row->riddle_id))
                ->setUserId(intval($row->user_id))
                ->setRiddle(strval($row->riddle))
                ->setAnswer(strval($row->answer));
            return $riddle;
        }
        return null;
    }

    public function createRiddle(Riddle $riddle): string|bool {
        $query = <<<'QUERY'
        INSERT INTO riddles(user_id, riddle, answer)
        VALUES(:user_id, :riddle, :answer)
        QUERY;

        $userId = $riddle->getUserId();
        $riddle_text = $riddle->getRiddle();
        $answer = $riddle->getAnswer();

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindParam(':riddle', $riddle_text, PDO::PARAM_STR);
        $statement->bindParam(':answer', $answer, PDO::PARAM_STR);

        $statement->execute();
        // Return the id of the riddle created
        return $this->databaseConnection->lastInsertId();
    }

    public function updateRiddle(Riddle $riddle) {
        $query = <<<'QUERY'
        UPDATE riddles SET riddle = :riddle, answer = :answer WHERE riddle_id = :id
        QUERY;

        $id = $riddle->getId();
        $riddle_text = $riddle->getRiddle();
        $answer = $riddle->getAnswer();

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':riddle', $riddle_text, PDO::PARAM_STR);
        $statement->bindParam(':answer', $answer, PDO::PARAM_STR);

        $statement->execute();
    }

    public function deleteRiddle(int $id) {
        $query = <<<'QUERY'
        DELETE FROM riddles WHERE riddle_id = :id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function getRandomRiddles(): array {
        $query = <<<'QUERY'
        SELECT * FROM riddles ORDER BY RAND() LIMIT 3
        QUERY;

        $statement = $this->databaseConnection->prepare($query);
        $statement->execute();
        $riddles = [];

        $count = $statement->rowCount();
        if ($count > 0) {
            $rows = $statement->fetchAll();
            for ($i = 0; $i < $count; $i++) {
                $riddle = Riddle::create()
                    ->setId(intval($rows[$i]['riddle_id']))
                    ->setUserId(intval($rows[$i]['user_id']))
                    ->setRiddle(strval($rows[$i]['riddle']))
                    ->setAnswer(strval($rows[$i]['answer']));
                $riddles[] = $riddle;
            }
        }

        return $riddles;
    }
}