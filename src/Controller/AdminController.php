<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Security $security): Response
    {

        // utilisable avec page personnalisé
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "accès refusé");

        // $user = $security->getUser();
        // // or $this->getUser() (AbstractConntroller get the user too!)

        // if (!$user) {
        //     $this->addFlash('danger', 'You must an admin to access the page!');
        //     return $this->redirectToRoute('home');
        // }

        // // or !$this->isGranted('ROLE_ADMIN')
        // if (!in_array('role_admin', $user->getRoles())) {
        //     $this->addFlash('danger', 'You must an admin to access the page!');
        //     return $this->redirectToRoute('home');
        // }

        return $this->render('admin/index.html.twig');
    }
}
