<?php
    
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

use App\Repository\UserRepository;
use App\Document\User;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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
    public function signup(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, JWTEncoderInterface $tokenEncoder) {

        $email = $request->get('email');
        $password = $request->get('password');

        $foundUser = $this->userRepository->findOneBy(['email' => $email]);

        if ($foundUser) return $this->json(['message' => 'email is already in use'], 409);

      

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($userPasswordEncoder->encodePassword($user, $password));

        $this->dm->persist($user);
        $this->dm->flush();

        return $this->json(
            [
                'message' => 'registration successful', 
                'data' => $user,
                'token' => $tokenEncoder->encode(['role' => $user->getRoles(), 'username' => $user->getEmail()])

            ]);
    }

    /**
     * @Route("/auth/login", name="login", methods={"POST"})
     */
    public function login(JWTEncoderInterface $tokenEncoder) {
    
        return $this->json(
            ['message' => 'you are logged in',
            'token' => $tokenEncoder->encode(
                ['role' => $this->getUser()->getRoles(),
                 'username' => $this->getUser()->getEmail()])]);

    }


}