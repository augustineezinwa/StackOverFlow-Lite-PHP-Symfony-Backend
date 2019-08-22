<?php
    
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Repository\UserRepository;
use App\Document\User;

use Doctrine\ODM\MongoDB\DocumentManager;



/**
 * @Route("/api/v1")
 */
class AuthController extends  AbstractController { 


    public function __construct(UserRepository $userRepository, DocumentManager $dm)
    {
        $this->userRepository = $userRepository;
        $this->dm = $dm;
    }


    /**
     * @Route("/auth/signup", name="sign_up", methods={"POST"})
     */
    public function signup(Request $request, UserPasswordEncoderInterface $userPasswordEncoder) {

        $email = $request->get('email');
        $password = $request->get('password');

        $foundUser = $this->userRepository->findOneBy(['email' => $email]);

        if ($foundUser) return $this->json(['message' => 'email is already in use'], 409);

      

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($userPasswordEncoder->encodePassword($user, $password));

        $this->dm->persist($user);
        $this->dm->flush();

        return $this->json(['message' => 'registration successful', 'data' => $user], 201);





    }

}