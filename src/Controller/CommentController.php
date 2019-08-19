<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CommentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * @Route("/api/v1")
 */
class CommentController extends AbstractController {

    public function __construct(CommentRepository $commentRepository, DocumentManager $dm) {
        $this->commentRepository = $commentRepository;
        $this->dm = $dm;
    }

    /**
     * @Route("/comments/{commentId}", name="fetch_a_comment", methods={"GET"})
     */
    function getAComment($commentId) {
        $comment = $this->commentRepository->find($commentId);

        if(!$comment) return $this->json(['message' => 'comment not found'], 404);

        return $this->json($comment);

    }

    /**
     * @Route("/comments/{commentId}", name="delete_a_comment", methods={"DELETE"})
     */
    function deleteAComment($commentId) {
        $comment = $this->commentRepository->find($commentId);

        if(!$comment) return $this->json(['message' => 'comment not found'], 404);

        $this->dm->remove($comment);
        $this->dm->flush();

        return $this->json(['message' => 'this comment has been deleted']);
    }

    /**
     * @Route("comments/{commentId}", name="update_a_comment", methods={"PUT"})
     */
    function updateComment($commentId, Request $request) {
        $comment = $this->commentRepository->find($commentId);
        if(!$comment) return $this->json(['message' => 'comment not found']);

        $commentContent = trim($request->get('comment'));

        $comment->setComment($commentContent);

        $this->dm->persist($comment);
        $this->dm->flush();

        return $this->json(['message' => 'this comment has been updated']);
    }
}