<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Teams;

use DateTime;
use Salle\PuzzleMania\Model\Team;

interface TeamRepository {
    public function createTeam(string $teamName);
    public function updateTeam(int $teamId, int $numMembers, DateTime $updatedAt);
    public function getTeamByName(string $name): ?Team;
    public function getTeamById(int $id): ?Team;
    public function getAllTeams(): ?array;
    public function getIncompleteTeams();
    public function getTeamMembers(int $teamId);
    public function increaseTeamScore(int $id, int $gamePoints): void;
}