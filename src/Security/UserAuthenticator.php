<?php

namespace App\Security;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class UserAuthenticator extends AbstractGuardAuthenticator
{

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        // todo
        return $request->isMethod('POST') && 
        ($request->attributes->get('_route') === 'login' 
        || $request->attributes->get('_route') === 'sign_up' 
        || $request->attributes->get('_route') === 'api_login_check');
    }

    public function getCredentials(Request $request)
    {
        // todo
       return
        [
            'email' => $request->get('email') ?? json_decode($request->getContent(), true)['email'],
            'password' => $request->get('password') ?? json_decode($request->getContent(), true)['password']
        ];
        
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // todo
        return $userProvider->loadUserByUsername($credentials['email']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // todo
        // check user password

        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // todo
        throw new CustomUserMessageAuthenticationException('invalid email or password');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // todo

        // dd('success');
  
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
        return new JsonResponse(['message' => $authException->getMessage()], 401);
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
