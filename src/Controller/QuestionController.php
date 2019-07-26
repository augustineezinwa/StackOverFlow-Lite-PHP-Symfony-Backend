<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class QuestionController {
 
 /**
  * Creates index route
  * 
  * @Route("/")
  * @Method({"GET"})
  */
    public function index() {
        $response = new Response();
        $response->setContent(json_encode([ 'data' => 123 ]));
        $response->headers->set('ContentType', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

    /**
     * create show message route
     * 
     * @Route("/message", name="message")
     * @Method({"GET"})
     */

    public function show() {
        return new JsonResponse(['message' => ' I have got some message for you'],  200);
    }
}