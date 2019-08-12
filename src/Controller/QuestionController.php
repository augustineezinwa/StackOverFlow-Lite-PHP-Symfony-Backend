<?php
    
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Document\Question;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Service\HelperService;

class QuestionController extends  AbstractController {


 public function __construct(DocumentManager $dm, $repository) {
    $this->dm = $dm;
    $this->repository = $repository;
 }
 /**
  * Creates index route
  * 
  * @Route("/", name="index", methods={"GET"})
  * 
  */
    public function index() {
        $response = new Response();
        $response->setContent(json_encode([ 'data' => 'welcome to stack overflow-lite api' ]));
        $response->headers->set('ContentType', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

    /**
     * create show message route
     * 
     * @Route("/questions", name="show_all_questions", methods={"GET"})
     * 
     */
    public function show(HelperService $hs) {

        // $repository = $this->dm->getRepository(Question::class);

        // var_dump($this->get('doctrine_mongodb')->getManager());

        // dump($hs->getLength(['fish', 'donkey', 2]));
        $questions = $this->repository->findAll();

         return $this->json(['data' => $questions]);

    }

    /**
     * show a single question
     * 
     * @Route("/questions/{questionId}", name="show_a_question", methods={"GET"})
     */
    public function getAQuestion($questionId) {

        // $repository = $this->dm->getRepository(Question::class);

        $questions = $this->repository->find($questionId);

        if(! $questions) return $this->json(['message' => 'question not found'], 404);

        return new JsonResponse(['data' => $questions]);

    }


    /**
     * @Route("/questions", name="post_question", methods={"POST"})
     * 
     */
    public function postQuestion() {

        $question = new Question();

        $question->setQuestionTitle('This is the title of my question');
        $question->setQuestionDescription('What is the description of my question');

        // $dm =  $this->get('doctrine_mongodb')->getManager();

        $this->dm->persist($question);

        $this->dm->flush();

        return new JsonResponse(['message' => 'A new question has been created'], 200);

    }

    /**
     * @Route("/questions/{questionId}", name="delete_question", methods={"DELETE"})
     */
    public function removeQuestion($questionId) {

        $question = $this->repository->findOneById($questionId);

        if($question) {
            $this->dm->remove($question);
            $this->dm->flush();
            return $this->json([
                'message' => 'question has been deleted'
            ]);
        } else {
            return $this->json([
                'message' => 'question not found'
            ], 404);
        }

    }

    // public static function getSubscribedServices()

    // {
    //     return parent::getSubscribedServices() + [ 'helper' => HelperService::class ];
    // }
}