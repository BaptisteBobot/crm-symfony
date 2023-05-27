<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/post", name="post_")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {   
        $user = $security->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour créer un post.');
        }
        $post = new Post();
        $post->setCreatedBy($user);  // Set the current logged in user as the creator of the post
    
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();
    
            return $this->redirectToRoute('post_index');
        }
    
        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", requirements={"id"="\d+"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, Post $post,Security $security): Response
    {
         // Vérifier si l'utilisateur a le rôle "admin" ou s'il est le créateur du post
    $user = $security->getUser();
    if (!$user || (!$this->isGranted('ROLE_ADMIN') && $post->getCreatedBy() !== $user)) {
        throw new AccessDeniedException('Vous n\'êtes pas autorisé à modifier ce post.');
    }

    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
    }

    return $this->render('post/edit.html.twig', [
        'form' => $form->createView(),
        'post' => $post,
    ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id"="\d+"})
     */
    public function delete(EntityManagerInterface $entityManager, Post $post): Response
    {
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('post_index');
    }
}
