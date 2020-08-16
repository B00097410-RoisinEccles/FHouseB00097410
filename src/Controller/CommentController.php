<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \DateTime;
use App\Entity\House;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/staff/comment")

 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment_index", methods={"GET"})
     * @IsGranted("ROLE_STAFF")
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="comment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $manager = $this->getDoctrine()->getManager();

        $comment = new Comment();
        $commentContent = $request->request->get('content');
        $houseId = $request->request->get('houseId');

        // $houseId, known ID of house
        // get repository for entity of type House
        // using HouseRespository.php, lookup house using known ID
        $house = $manager->getRepository(House::class)->find($houseId);

        $comment->setContent($commentContent);
        $comment->setCreated(new DateTime('NOW'));
        $comment->setApproved(false);
        $comment->setHouse($house); // pass full house object into method

        $manager->persist($comment);
        $manager->flush();

        // redirect user to the Show House page
        // Pass the house ID
        return $this->redirect($this->generateUrl('house_show', array('id' => $houseId)));
    }

    /**
     * @Route("/{id}", name="comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/approve", name="comment_approve", methods={"POST"})
     */
    public function approve(Request $request, Comment $comment): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment->setApproved(true);
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('comment_index');
    }

    /**
     * @Route("/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index');
    }
}
