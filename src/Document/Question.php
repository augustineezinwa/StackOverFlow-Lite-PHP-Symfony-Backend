<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use JsonSerializable;

/**
 * @MongoDB\Document
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


    public function jsonSerialize(): array
    {
        return [
            'id' =>  $this->id,
             'question_title' => $this->questionTitle,
             'question_description' => $this->questionDescription,
             'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
             'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')
            //  'updated_at' => ((array) $this->updatedAt)['date']
        ];
        
    }

}