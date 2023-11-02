<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Service\UtilsService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Article;
class ApiArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private SerializerInterface $serializerInterface;
    private EntityManagerInterface $em;
    public function __construct(ArticleRepository $articleRepository, 
    SerializerInterface $serializerInterface, EntityManagerInterface $em){
        $this->articleRepository = $articleRepository;
        $this->serializerInterface = $serializerInterface;
        $this->em = $em;
    }

    #[Route('/api/article', name: 'app_api_article')]
    public function index(): Response
    {
        return $this->render('api_article/index.html.twig', [
            'controller_name' => 'ApiArticleController',
        ]);
    }
    #[Route('/api/article/all', name: 'app_api_article_all')]
    public function getAllArticle(){
        $articles = $this->articleRepository->findAll();
        return $this->json($articles,200, ['Content-Type'=>'application/json', 'Access-Control-Allow-Origin'=>'*'], ['groups'=>'articles']);
    }
    #[Route('/api/article/id/{id}', name: 'app_api_article_id')]
    public function getArticleById($id){
        $id = UtilsService::cleanInput($id);
        $article = $this->articleRepository->find($id);
        if($article){
            return $this->json($article,200, ['Content-Type'=>'application/json', 'Access-Control-Allow-Origin'=>'*'], ['groups'=>'articles']);
        }
        else{
            return $this->json(['error'=>'L\'article n\'existe pas'],206, ['Content-Type'=>'application/json', 'Access-Control-Allow-Origin'=>'*'], 
            ['groups'=>'articles']);
        }
    }
    #[Route('/api/article/add', name:'app_api_article_add', methods:'POST')]
    public function addArticle(Request $request, UserRepository $userRepository): Response{
        $json = $request->getContent();
        $data = $this->serializerInterface->decode($json, 'json');
        $article = new Article();
        $article->setTitle(UtilsService::cleanInput($data['title'])); 
        $article->setContent(UtilsService::cleanInput($data['content'])); 
        $article->setDate(new \DateTimeImmutable(UtilsService::cleanInput($data['date'])));
        $article->setAuthor($userRepository->findOneBy(['email'=> UtilsService::cleanInput($data['author']['email'])]));
        $this->em->persist($article);
        $this->em->flush();
        return $this->json(['error'=>'L\'article a été ajouté en BDD'],200,
        ['Content-Type'=>'application/json', 'Access-Control-Allow-Origin'=>'*']);
    }
}
