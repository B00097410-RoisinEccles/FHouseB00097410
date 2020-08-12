<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class StaffController extends AbstractController
{
    /**
     * @Route("/staff", name="staff")
     * @IsGranted("ROLE_STAFF")
     */
    public function index()
    {
        return $this->render('staff/index.html.twig', [
            'controller_name' => 'StaffController',
        ]);
    }
}
