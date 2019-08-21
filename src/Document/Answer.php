<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

/**
 * @MongoDB\Document(repositoryClass="App\Repository\AnswerRepository")
 * @HasLifecycleCallbacks()
 */
class Answer implements JsonSerializable {

  use Timestamp;
  
  /**
   * @MongoDB\Id
   */
  private $id;

  /**
   * @MongoDB\Field(type="string")
   */
  private $answer;

  /**
   * @MongoDB\ReferenceOne(targetDocument="Question", inversedBy="answers")
   */
  private $question;

  /**
   * @MongoDB\ReferenceMany(targetDocument="Comment", mappedBy="answer", cascade={"remove"})
   */
  private $comments;

  public function __construct()
  {
    $this->comments = new ArrayCollection();
      
  }

  public function getAnswers(): ?string {
      return $this->answer;
  }

  public function getComments() {
      return $this->comments->toArray();
  }


  public function setAnswer(String $answers): self {
      $this->answer = $answers;
      return $this;
  } 

  public function getQuestion() {
      return $this->question;
  }

  public function setQuestion(Question $question): self {
        $this->question = $question;
        return $this;
  }

  public function setComment(Comment $comment): self {
      $this->comment[] = $comment;
      return $this;
  }

  public function jsonSerialize()
  {
      return [
          'id' => $this->id,
          'answer' => $this->answer,
          'comments' => $this->getComments(),
          'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
          'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')
      ];
  }

}