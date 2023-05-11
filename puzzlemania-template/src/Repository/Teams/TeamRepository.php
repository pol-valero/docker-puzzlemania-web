<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Teams;

use Salle\PuzzleMania\Model\Team;

interface TeamRepository {
    public function createTeam(string $teamName);
    public function updateTeam(Team $team);
    public function getTeamByName(string $name);
    public function getTeamById(int $id);
    public function getAllTeams();

    public function getIncompleteTeams();
}