<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Article;

class CommentController extends Controller
{
    /**
     * @Route("/api/comment", name="comments")
     * @Method({"GET"})
     */
    public function commentAction()
    {
        $repository = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $repository->findAll();
        $json = $this->get('serializer')->serialize($comments, 'json');
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/api/comment/{id}", name="commentId")
     * @Method({"GET"})
     */
    public function commentIdAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $repository->find($id);

        if (!$comment) {
            throw $this->createNotFoundException(
                'No comment found for id '.$id
            );
        }
        $json = $this->get('serializer')->serialize($comment, 'json');
        return JsonResponse::fromJsonString($json);
    }

}