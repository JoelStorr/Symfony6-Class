<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{


    private array $messages = [
        ['message' => 'Hello', 'created' => '2023/01/12'],
        ['message' => 'Hi', 'created' => '2023/02/12'],
        ['message' => 'Bye!', 'created' => '2021/05/12']
    ];


    #[Route('/', name: 'app_index')]
    public function index(UserProfileRepository $profiles): Response
    {


        $user = new User();
        $user->setEmail('email@email.com');
        $user->setPassword('1234567890');


        $profile = new UserProfile();
        $profile->setUser($user);
        $profiles->save($profile, true);



        /*    $profile = $profiles->find(2);
        $profiles->remove($profile, true); */

        return $this->render(
            'hello/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' => 3
            ]
        );
    }

    #[Route('/messages/{id<\d+>}',  name: 'app_showOne')]

    public function showOne(int $id): Response
    {
        return $this->render(
            'hello/show_one.html.twig',
            [
                'message' => $this->messages[$id]
            ]
        );
    }
}
