<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Model;

use DateTime;
use JsonSerializable;

class User implements JsonSerializable {
    private int $id;
    private string $email;
    private string $password;
    private int $team;
    private Datetime $createdAt;
    private Datetime $updatedAt;

public function __construct(
         int $id,
         string $email,
         string $password,
         int $team,
         Datetime $createdAt,
         Datetime $updatedAt
   )
{
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->team = $team;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Static constructor / factory
     */
    public static function create(): User {
        return new self();
    }

    /**
     * Function called when encoded with json_encode
     */
    public function jsonSerialize(): array {
        return get_object_vars($this);
    }

    public function getId() {
        return $this->id;
    }

    public function email() {
        return $this->email;
    }

    public function password() {
        return $this->password;
    }

    public function createdAt() {
        return $this->createdAt;
    }

    public function updatedAt() {
        return $this->updatedAt;
    }

    public function team() {
        if (isset($team)) {
            return $this->team;
        }

        return null;
    }

    public function getTeam() {
        return $this->team;
    }

    public function setTeam($teamId) {
        $this->team = $teamId;
    }

    /**
     * @param int $id
     */
    public function setId(int $id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email) {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password) {
        $this->password = $password;
        return $this;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt) {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}