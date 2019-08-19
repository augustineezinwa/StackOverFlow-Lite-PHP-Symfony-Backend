<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use JsonSerializable;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @MongoDB\Document(repositoryClass="App\Repository\QuestionRepository")
 * 
 * @HasLifecycleCallbacks() 
 */
class Question implements JsonSerializable {

    use Timestamp;

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    private $questionTitle;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    private $questionDescription;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Answer", mappedBy="question")
     */
    public $answers;

    public function __construct() {
        $this->answers = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }
    public function getQuestionTitle(): ? string {
        return $this->questionTitle;
    }

    public function setQuestionTitle(String $questionTitle): void {
        $this->questionTitle = $questionTitle;
    }

    public function getQuestionDescription(): ? string {
        return $this->questionDescription;
    }

    public function setQuestionDescription(String $questionDescription): void {
        $this->questionDescription = $questionDescription;
    }

    public function setAnswer(Answer $answer) {
            $this->answers[] = $answer;
            return $this;
        }

    public function getAnswers() {
        return $this->answers->toArray();
    }


    public function jsonSerialize(): array
    {
        return [
            'id' =>  $this->id,
             'question_title' => $this->questionTitle,
             'question_description' => $this->questionDescription,
             'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
             'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
             'answers' => $this->getAnswers()
            //  'updated_at' => ((array) $this->updatedAt)['date']
        ];
        
    }

}
