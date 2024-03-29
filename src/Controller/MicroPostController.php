<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class MicroPostController extends AbstractController
{

    # NOTE: Micro Post Index
    #[Route('/', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {



        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAllWithComments(),
        ]);
    }


    # NOTE: Top Liked
    #[Route('/micro-post/top-liked', name: 'app_micro_post_topliked')]
    public function topLiked(MicroPostRepository $posts): Response
    {

        

        return $this->render('micro_post/top_liked.html.twig', [
            'posts' => $posts->findAllWithMinLikes(2),
        ]);
    }

    # NOTE: Post by Followed Users
    #[Route('/micro-post/follows', name: 'app_micro_post_follows')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function follows(MicroPostRepository $posts): Response
    {

        /** @var User $currentUser */
        $currentUser = $this->getUser();


        return $this->render('micro_post/follows.html.twig', [
            'posts' => $posts->findAllByAuthors(
                $currentUser->getFollows()
            ),
        ]);
    }



    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    #[IsGranted(MicroPost::VIEW, 'post')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    // NOTE: ADD Function

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    #[IsGranted('ROLE_WRITER')]
    public function add(Request $request, MicroPostRepository $posts): Response
    {

      

        $form = $this->createForm(MicroPostType::class, new MicroPost());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setAuthor($this->getUser());
            $posts->save($post, true);

            //Note:Add a Flash Message
            $this->addFlash('success', 'Your micro post has been added.');


            //Note: Add Redirect
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->renderForm(
            'micro_post/add.html.twig',
            [
                'form' => $form
            ]
        );
    }


    // NOTE: Edit Function

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    #[IsGranted(MicroPost::EDIT, 'post')]
    public function edit(MicroPost $post, Request $request, MicroPostRepository $posts): Response
    {


        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $posts->save($post, true);

            //Note:Add a Flash Message
            $this->addFlash('success', 'Your micro post has been updated.');


            //Note: Add Redirect
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->renderForm(
            'micro_post/edit.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }


    //NOTE: Add Comment Function

    #[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(MicroPost $post, Request $request, CommentRepository $comments): Response
    {


        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());
            $comments->save($comment, true);

            //Note:Add a Flash Message
            $this->addFlash('success', 'Your comment post has been updated.');


            //Note: Add Redirect
            return $this->redirectToRoute('app_micro_post_show', ['post' => $post->getId()]);
        }

        return $this->renderForm(
            'micro_post/comment.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }
}
