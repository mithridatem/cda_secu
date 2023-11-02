<?php
namespace App\Service;
use App\Repository\UserRepository;
use App\Service\UtilsService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class JwtService{
    private string $token;
    private UserPasswordHasherInterface $hash;
    private UserRepository $userRepository;
    public function __construct(string $token, UserPasswordHasherInterface $hash, UserRepository $userRepository){
        $this->token = $token;
        $this->hash = $hash;
        $this->userRepository = $userRepository;
    }
    public function authentification(string $email, string $password){
        $email = UtilsService::cleanInput($email);
        $password = UtilsService::cleanInput($password);
        $user = $this->userRepository->findOneByEmail($email);
        //$user = $this->userRepository->findOneBy(['email'=>$email]);
        if($user){
            if($this->hash->isPasswordValid($user, $password)){
                return true;
            }
            else{
                return false;
            }   
        }
        else{
            return false;
        }
    }
}