<?php

namespace App\Document;

use JsonSerializable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="App\Repository\CommentRepository")
 * @MongoDB\HasLifecycleCallbacks
 */
class Comment implements JsonSerializable {

    use Timestamp;

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     */
    private $comment;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Answer", inversedBy="comments")
     */
    private $answer;

    public function getComments(): string {
        return $this->comment;
    }

    public function getId() {
        return $this->id;
    }

    public function getAnswer() {
        return $this->answer;
    }

    public function setComment(String $comment): self {
        $this->comment = $comment;
        return $this;
    }

    public function setAnswer(Answer $answer): self {
        $this->answer = $answer;
        return $this;
    }


    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'comment' => $this->comment,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')
        ];
    }

}