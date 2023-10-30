<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
class RegisterController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserRepository $repo;
   
    public function __construct(UserRepository $repo, EntityManagerInterface $em){
        $this->repo = $repo;
        $this->em = $em;
   }
    #[Route('/register', name: 'app_register')]
    public function addUser(UserPasswordHasherInterface $hash, EntityManagerInterface $em, Request $request): Response
    {
        $msg = "";
        $user = new User();
        $form = $this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);
        //test si le formulaire est submit
        if ($form->isSubmitted() && $form->isValid()) {
            //tester si le compte existe déja
            if($this->repo->findOneBy(["email"=> $form->get("email")->getData()])){
                $msg ="Le compte : ".$user->getEmail()." existe déja";
            }
            //test si le compte n'existe pas
            else{
                $pass = $request->request->all('register')['password']['first'];
                $hash = $hash->hashPassword($user, $pass);
                $user->setPassword($hash);
                $user->setActivated(false);
                $user->setRoles(["ROLE_USER"]);
                $this->em->persist($user);
                $this->em->flush();
                $msg = "Le compte : ".$user->getEmail()." a été ajouté en BDD";
            }
        }
        return $this->render('register/index.html.twig', [
            'msg' => $msg,
            'form' =>$form->createView()
        ]);
    }
}
