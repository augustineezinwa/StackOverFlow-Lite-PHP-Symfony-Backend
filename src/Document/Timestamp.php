<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


trait Timestamp {

    /**
     * @MongoDB\Field(type="date")
     */
    private $createdAt;

    /**
     * @MongoDB\Field(type="date")
     */
    private $updatedAt;


    /**
     * @MongoDB\PrePersist
     */
    public function triggerCreatedAt() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }


    /**
     * @MongoDB\PreUpdate
     */
    public function triggerUpdatedAt() {
        $this->updatedAt = new \DateTime();
    }


    
}