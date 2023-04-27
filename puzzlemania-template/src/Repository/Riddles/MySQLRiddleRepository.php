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


    public function getRiddles()
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
                $riddle = new Riddle(
                    $rows[$i]['riddle_id'],
                    $rows[$i]['user_id'],
                    $rows[$i]['riddle'],
                    $rows[$i]['answer']
                );
                $riddles[] = $riddle;
            }
        }
        return $riddles;
    }

    public function createRiddle(Riddle $riddle)
    {
        // TODO: Implement createRiddle() method.
    }
}
