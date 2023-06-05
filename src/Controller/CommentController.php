<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/{post}/new", name="comment_new", methods={"GET","POST"})
     */
    public function new(Post $post, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour créer un commentaire.');
        }
        
        $comment = new Comment();
        $comment->setCreatedBy($user);
        $comment->setCreatedAt(new \DateTime()); // Définir la date et l'heure actuelles
        $comment->setPost($post);
        
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render('comment/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if ($user !== $comment->getCreatedBy()) {
            return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
        }

        return $this->render('comment/edit.html.twig', [
            'post' => $comment->getPost(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="comment_delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if ($user !== $comment->getCreatedBy()) {
            return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
        }

        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
    }
}
