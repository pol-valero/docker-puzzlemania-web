<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Riddles;

use Salle\PuzzleMania\Model\Riddle;

interface RiddleRepository
{
    public function getRiddles();
    public function createRiddle(Riddle $riddle);
}
