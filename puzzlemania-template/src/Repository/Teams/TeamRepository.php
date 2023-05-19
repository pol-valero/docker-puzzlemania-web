<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Teams;

use Salle\PuzzleMania\Model\Team;

interface TeamRepository {
    public function createTeam(Team $team): void;
    public function getTeamByName(string $name): ?Team;
    public function getTeamById(int $id): ?Team;
    public function getAllTeams(): ?array;
    public function increaseTeamScore(int $id, int $gamePoints): void;
}