<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Teams;

use Salle\PuzzleMania\Model\Team;

interface TeamRepository {
    public function createTeam(Team $team): void;
    public function getTeamByName(string $name);
    public function getTeamById(int $id);
    public function getAllTeams();
}