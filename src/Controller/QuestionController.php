<?php
    
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Document\Question;
use App\Repository\QuestionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Service\HelperService;
use App\Document\Answer;
use App\Repository\AnswerRepository;


/**
 * 
 * @Route("/api/v1")
 * 
 */
class QuestionController extends  AbstractController {


 public function __construct(DocumentManager $dm, QuestionRepository $questionRepository, AnswerRepository $answerRepository) {
    $this->dm = $dm;
    $this->questionRepository = $questionRepository;
    $this->answerRepository = $answerRepository;
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
        
        $questions = $this->questionRepository->findAll();

         return $this->json(['data' => $questions]);

    }

    /**
     * show a single question
     * 
     * @Route("/questions/{questionId}", name="show_a_question", methods={"GET"})
     * @ParamConverter("question", options={"id" = "questionId"})
     * @IsGranted("QUESTION_EDIT", subject="question")
     */
    public function getAQuestion(Question $question) {

        
        // $repository = $this->dm->getRepository(Question::class);

        // $question = $this->questionRepository->find($questionId);

        // if(! $question) return $this->json(['message' => 'question not found'], 404);

        return new JsonResponse(['data' => $question]);

    }


    /**
     * @Route("/questions", name="post_question", methods={"POST"})
     * 
     */
    public function postQuestion(Request $request) {

        $question = new Question();

        $question->setQuestionTitle($request->get('title'));
        $question->setQuestionDescription($request->get('description'));
        $question->setAuthor($this->getUser());

        // $dm =  $this->get('doctrine_mongodb')->getManager();

        $this->dm->persist($question);

        $this->dm->flush();

        return new JsonResponse(['message' => 'A new question has been created'], 200);

    }

    /**
     * @Route("/questions/{questionId}", name="delete_question", methods={"DELETE"})
     */
    public function removeQuestion($questionId) {

        $question = $this->questionRepository->findOneById($questionId);

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

    /**
     * @Route("/questions/{questionId}/answers", name="add_answers_to_questions", methods={"POST"})
     */
    public function addAnswerToQuestion($questionId, Request $request) {
        $question = $this->questionRepository->findOneById($questionId);

        if($question) {
            $answerBody = $request->get('answer');

            $answer = new Answer();
            $answer->setAnswer($answerBody)->setQuestion($question);

            $this->dm->persist($answer);
   
            $this->dm->flush();

            return $this->json([
                'message' => 'this answer has been added to this question',
                'data' => $answer
            ], 201);
        }

    }

    /**
     * @Route("/questions/{questionId}/answers", name="get_answers_for_a_question", methods={"GET"})
     */
    public function getAnswersToAQuestion($questionId) {
        $question = $this->questionRepository->findOneBy(['id' => $questionId]);
        if(!$question) {
            return $this->json(['message' => 'question not found'], 404);
        }
        return $this->json($question->getAnswers());
    }




    // public static function getSubscribedServices()

    // {
    //     return parent::getSubscribedServices() + [ 'helper' => HelperService::class ];
    // }
}