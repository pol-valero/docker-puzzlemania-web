<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Riddles;

use PDO;
use Salle\PuzzleMania\Model\Riddle;

final class MySQLRiddleRepository implements RiddleRepository
{
    private PDO $databaseConnection;

    public function __construct(PDO $database)
    {
        $this->databaseConnection = $database;
    }


    public function getRiddles(): array
    {
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

    public function createRiddle(Riddle $riddle): bool|string
    {
        // TODO: Implement createRiddle() method.
        $query = <<<'QUERY'
        INSERT INTO riddles(user_id, riddle, answer)
        VALUES(:user_id, :riddle, :answer)
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $userId = $riddle->getUserId();
        $riddle_text = $riddle->getRiddle();
        $answer = $riddle->getAnswer();

        $statement->bindParam('user_id', $userId, PDO::PARAM_STR);
        $statement->bindParam('riddle', $riddle_text, PDO::PARAM_STR);
        $statement->bindParam('answer', $answer, PDO::PARAM_STR);

        $statement->execute();
        // Return the id of the riddle created
        return $this->databaseConnection->lastInsertId();
    }
}
