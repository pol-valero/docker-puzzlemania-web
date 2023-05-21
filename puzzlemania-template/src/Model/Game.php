<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Model;

use DateTime;
use JsonSerializable;

class Game implements JsonSerializable {
    private int $id;
    private int $user_id;
    private int $score;
    private int $riddle1_id;
    private int $riddle2_id;
    private int $riddle3_id;
    private DateTime $createdAt;

//    public function __construct(int $id, int $user_id, int $score, array $riddles, DateTime $createdAt) {
//        $this->id = $id;
//        $this->user_id = $user_id;
//        $this->score = $score;
//        $this->riddle1_id = $riddles[0];
//        $this->riddle2_id = $riddles[1];
//        $this->riddle3_id = $riddles[2];
//        $this->createdAt = $createdAt;
//    }

    /**
     * Static constructor / factory
     */
    public static function create(): Game {
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
     * @return int
     */
    public function userId(): int {
        return $this->user_id;
    }

    /**
     * @return int
     */
    public function score(): int {
        return $this->score;
    }

    /**
     * @return int
     */
    public function riddle1Id(): int {
        return $this->riddle1_id;
    }

    /**
     * @return int
     */
    public function riddle2Id(): int {
        return $this->riddle2_id;
    }

    /**
     * @return int
     */
    public function riddle3Id(): int {
        return $this->riddle3_id;
    }

    /**
     * @return DateTime
     */
    public function createdAt(): DateTime {
        return $this->createdAt;
    }

    /**
     * @return array
     */
    public function riddles(): array {
        $riddles[0] = $this->riddle1_id;
        $riddles[1] = $this->riddle2_id;
        $riddles[2] = $this->riddle3_id;

        return $riddles;
    }

    /**
     * @param int $id
     */
    public function setId(int $id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id) {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @param int $riddle1_id
     */
    public function setRiddle1Id(int $riddle1_id) {
        $this->riddle1_id = $riddle1_id;
        return $this;
    }

    /**
     * @param int $riddle2_id
     */
    public function setRiddle2Id(int $riddle2_id) {
        $this->riddle2_id = $riddle2_id;
        return $this;
    }

    /**
     * @param int $riddle3_id
     */
    public function setRiddle3Id(int $riddle3_id) {
        $this->riddle3_id = $riddle3_id;
        return $this;
    }

    /**
     * @param array $riddles
     */
    public function setRiddles(array $riddles) {
        $this->riddle1_id = $riddles[0];
        $this->riddle2_id = $riddles[1];
        $this->riddle3_id = $riddles[2];
        return $this;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score) {
        $this->score = $score;
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