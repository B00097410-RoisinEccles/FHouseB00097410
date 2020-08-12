<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Entity\House;
use App\Form\SponsorType;
use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/sponsor")
 * @IsGranted("ROLE_STAFF")
 */
class SponsorController extends AbstractController
{
    /**
     * @Route("/", name="sponsor_index", methods={"GET"})
     */
    public function index(SponsorRepository $sponsorRepository): Response
    {
        return $this->render('sponsor/index.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sponsor_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sponsor = new Sponsor();
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sponsor);
            $entityManager->flush();

            return $this->redirectToRoute('sponsor_index');
        }

        return $this->render('sponsor/new.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sponsor_show", methods={"GET"})
     */
    public function show(Sponsor $sponsor): Response
    {
        return $this->render('sponsor/show.html.twig', [
            'sponsor' => $sponsor,
        ]);
    }

    /**
     * @Route("/{id}/associate", name="sponsor_associate", methods={"GET"})
     */
    public function associate(Sponsor $sponsor): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $houses = $entityManager->getRepository(House::class)->findAll();

        return $this->render('sponsor/associate.html.twig', [
            'houses' => $houses,
            'sponsor' => $sponsor
        ]);
    }

    /**
     * @Route("/{id}/associate", name="sponsor_associate_to", methods={"POST"})
     */
    public function associateTo(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository): Response
    {
        // Get the houseId from form submission
        $houseId = $request->request->get('houseId');

        // Get house from DB based on the ID
        $entityManager = $this->getDoctrine()->getManager();
        $house = $entityManager->getRepository(House::class)->find($houseId);

        // Updating the House object, to point at the requested Sponsor
        $house->setSponsor($sponsor);
        $entityManager->persist($house);
        $entityManager->flush();

        return $this->render('sponsor/index.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sponsor_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sponsor $sponsor): Response
    {
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sponsor_index');
        }

        return $this->render('sponsor/edit.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sponsor_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sponsor $sponsor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sponsor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sponsor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sponsor_index');
    }
}
