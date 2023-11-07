<?php

namespace App\Controller;

use App\Repository\TestsRepository;
use App\Entity\Tests;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UtilsService;
class TestsController extends AbstractController
{
    private EntityManagerInterface $em;
    private SerializerInterface $serializer;
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer){
        $this->em = $em;
        $this->serializer = $serializer;
    }
    #[Route('/tests', name: 'app_tests')]
    public function index(): Response
    {
        return $this->render('tests/index.html.twig', [
            'controller_name' => 'TestsController',
        ]);
    }
    #[Route('/tests/validation', name:'app_tests_validation')]
    public function validationTests(Request $request){
        $json = $request->getContent();
        if($json){
            $data = $this->serializer->decode($json, 'json');
            $test = new Tests();
            $test->setTitle(UtilsService::cleanInput($data['title']));
            $test->setDate(new \DateTimeImmutable(UtilsService::cleanInput($data['date'])));
            $test->setStatut((boolean)UtilsService::cleanInput($data['statut']));
            $this->em->persist($test);
            $this->em->flush();
            $message = ['Erreur:'=>'enregistrement OK'];
            $code = 200;
        }
        else{
            $message = ['Erreur:'=>'le json n\'existe pas'];
            $code = 400;
        }
        return $this->json($message, $code, ['Content-Type'=>'application/json', 'Access-Control-Allow-Origin'=>'*']);
    }
}
