<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Riddles;

use Salle\PuzzleMania\Model\Riddle;

interface RiddleRepository {
    public function getRiddles() : array;
    public function createRiddle(Riddle $riddle);
    public function getRiddleById(int $id) : ?Riddle;
    public function updateRiddle(Riddle $riddle);
    public function deleteRiddle(int $id);
}
