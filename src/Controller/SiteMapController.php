<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SiteMapController extends AbstractController
{
    /**
     * @Route("/site/map", name="site_map")
     */
    public function index()
    {
        return $this->render('site_map/index.html.twig', [
            'controller_name' => 'SiteMapController',
        ]);
    }
}
