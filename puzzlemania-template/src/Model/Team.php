<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Model;

use DateTime;
use JsonSerializable;

class Team implements JsonSerializable {
    private int $id;
    private string $name;
    private int $numMembers;
    private DateTime $createdAt;
    private DateTime $updatedAt;

//    public function __construct(int $id, string $name, int $numMembers, DateTime $createdAt, DateTime $updatedAt) {
//        $this->id = $id;
//        $this->name = $name;
//        $this->numMembers = $numMembers;
//        $this->createdAt = $createdAt;
//        $this->updatedAt = $updatedAt;
//    }

    /**
     * Static constructor / factory
     */
    public static function create(): Team {
        return new self();
    }

    public function jsonSerialize(): array {
        return get_object_vars($this);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string {
        return $this->name;
    }

    /**
     * @param int $id
     */
    public function setId(int $id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @param int $numMembers
     */
    public function setNumMembers(int $numMembers) {
        $this->numMembers = $numMembers;
        return $this;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }
}