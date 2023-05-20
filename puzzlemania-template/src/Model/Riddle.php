<?php

namespace Salle\PuzzleMania\Model;

use JsonSerializable;

class Riddle implements JsonSerializable
{
    private int $id;
    private int $userId;
    private string $riddle;
    private string $answer;

    /**
     * Static constructor / factory
     */
    public static function create(): Riddle
    {
        return new self();
    }
    /**
     * Function called when encoded with json_encode
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getRiddle(): string
    {
        return $this->riddle;
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @param int $id
     * @return Riddle
     */
    public function setId(int $id): Riddle
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param int $userId
     * @return Riddle
     */
    public function setUserId(int $userId): Riddle
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param string $riddle
     * @return Riddle
     */
    public function setRiddle(string $riddle): Riddle
    {
        $this->riddle = $riddle;
        return $this;
    }

    /**
     * @param string $answer
     * @return Riddle
     */
    public function setAnswer(string $answer): Riddle
    {
        $this->answer = $answer;
        return $this;
    }

}