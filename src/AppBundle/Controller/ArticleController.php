<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Article;

class ArticleController extends Controller
{
    /**
     * @Route("/api/article", name="articles")
     * @Method({"GET"})
     */
    public function articleAction()
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findAll();
        $json = $this->get('serializer')->serialize($articles, 'json');
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/api/article/{id}", name="articleId")
     * @Method({"GET"})
     */
    public function articleIdAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        
        if ($request->get('page')) {
            $page = $request->get('page');
            $per_page = $request->get('per_page');
            $article = $repository->getCommentsByArticle($page * $per_page - $per_page, $per_page, $id);
        } else {
            $article = $repository->find($id);
        }
        
        if (!$article) {
            return New JsonResponse(['result' => 'article with id ' . $id . ' not found']);
        }
        
        if ($request->get('format') == 'xml') {
            $response = new Response($this->renderView('AppBundle:Response:response.xml.twig', array('article' => $article)));
            $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
            return $response;
        } else {
            $json = $this->get('serializer')->serialize($article, 'json');
            return JsonResponse::fromJsonString($json);
        }
    }

}
