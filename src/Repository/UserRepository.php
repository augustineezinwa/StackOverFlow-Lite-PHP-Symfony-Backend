<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\User;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

use Doctrine\ODM\MongoDB\DocumentRepository;

/*
 * the userRepostiory class has to implement the UserLoaderInterface,
 * thus you have to implement loadUserByUsername method
 * this is generally enforced when you use mongodb or entity option under app_user_provider in the service yaml file
 * and refuse to set property to option
 * 
 * note always use findOneBy as this returns a user object to avoid UserInterface instance error.
 */

class UserRepository extends DocumentRepository implements UserLoaderInterface {

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
        $this->uow = $this->dm->getUnitOfWork();
        $this->classMetadata = $this->dm->getClassMetadata(User::class);
        parent::__construct($this->dm, $this->uow, $this->classMetadata);
    }

    public function loadUserByUsername($username)
    {
        return $this->findOneBy(['email' => $username]);
    }

}