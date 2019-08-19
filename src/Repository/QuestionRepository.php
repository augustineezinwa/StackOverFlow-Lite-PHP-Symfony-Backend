<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Question;

use Doctrine\ODM\MongoDB\DocumentRepository;

class QuestionRepository extends DocumentRepository {

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
        $this->uow = $this->dm->getUnitOfWork();
        $this->classMetadata = $this->dm->getClassMetadata(Question::class);
        parent::__construct($this->dm, $this->uow, $this->classMetadata);
    }
}