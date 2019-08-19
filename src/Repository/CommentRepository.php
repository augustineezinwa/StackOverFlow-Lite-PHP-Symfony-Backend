<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Comment;

class CommentRepository extends DocumentRepository {

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
        $this->uow = $this->dm->getUnitOfWork();
        $this->classMetadata = $this->dm->getClassMetadata(Comment::class);
        parent::__construct($this->dm, $this->uow, $this->classMetadata);
    }

}