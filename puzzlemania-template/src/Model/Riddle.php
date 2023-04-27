<?php

namespace Salle\PuzzleMania\Model;

use JsonSerializable;

class Riddle implements JsonSerializable
{
    private int $id;
    private int $userId;
    private string $riddle;
    private string $answer;

    public function __construct(int $riddle_id, int $user_id, string $riddle, string $answer)
    {
        $this->id = $riddle_id;
        $this->userId = $user_id;
        $this->riddle = $riddle;
        $this->answer = $answer;
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
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param string $riddle
     */
    public function setRiddle(string $riddle): void
    {
        $this->riddle = $riddle;
    }

    /**
     * @param string $answer
     */
    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }



}