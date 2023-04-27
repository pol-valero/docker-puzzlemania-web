<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Riddles;

use Salle\PuzzleMania\Model\Riddle;
use Salle\PuzzleMania\Model\User;

interface RiddleRepository
{
    public function getRiddles();
    public function createRiddle(Riddle $riddle);
}
