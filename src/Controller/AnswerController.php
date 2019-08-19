<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\QuestionRepository;
use App\Repository\AnswerRepository;

use App\Document\Comment;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ODM\MongoDB\DocumentManager;


/**
 * @Route("/api/v1")
 */
class AnswerController extends  AbstractController {


 public function __construct(DocumentManager $dm, QuestionRepository $questionRepository, AnswerRepository $answerRepository) {
    $this->dm = $dm;
    $this->questionRepository = $questionRepository;
    $this->answerRepository = $answerRepository;
 }


    /**
     * @Route("/answers/{answerId}", name="update_an_answer", methods={"PUT"})
     */
    public function updateAnswer($answerId, Request $request) {

            $foundAnswer = $this->answerRepository->findOneById($answerId);

            if($foundAnswer) {

                $answerBody = $request->get('answer') ?? $foundAnswer;

                $foundAnswer->setAnswer($answerBody);

                $this->dm->persist($foundAnswer);
                $this->dm->flush();
                

                return $this->json(['message' => 'answer updated', 'data' => $foundAnswer], 200);

            } else {
                return $this->json(['message' => 'answer not found'], 404);
            }

    }

    /**
     * @Route("/answers/{answerId}", name="remove_an_answer", methods={"DELETE"})
     */
    public function deleteAnswer($answerId) {
        $foundAnswer = $this->answerRepository->findOneById($answerId);

        if($foundAnswer) {
            $this->dm->remove($foundAnswer);
            $this->dm->flush();

            return $this->json(['message' => 'answer has been deleted']);

        } else {
            return $this->json(['message' => 'answer not found'], 404);
        }

}


    /**
     * @Route("/answers/{answerId}", name="get_an_answer", methods={"GET"})
     */
    public function getAnswer($answerId) {
        $answer = $this->answerRepository->findOneById($answerId);

        if(!$answer) return $this->json(['message'=> 'answer not found'], 404);

        return $this->json(['data' => $this->answerRepository->findOneById($answerId)]);
}


    /**
     * @Route("/answers/{answerId}/comments",  name="add_an_answer_to_a_question", methods={"POST"})
     */
    public function postAnswerToQuestion($answerId, Request $request) {

        $answer = $this->answerRepository->find($answerId);

        if(!$answer) return $this->json(['message' => 'answer not found'], 404);

        $commentContent = $request->get('comment');

        if(!$commentContent || trim($commentContent) === '') return $this->json(['message' => 'comment cannot be empty'], 422);

        $comment = new Comment();

        $comment->setComment($commentContent)->setAnswer($answer);

        $this->dm->persist($comment);
        $this->dm->flush();

        return $this->json(['message' => 'comment has been added successfully', 'data' => $comment]);
    }

}