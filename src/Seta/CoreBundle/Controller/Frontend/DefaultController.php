<?php

namespace Seta\CoreBundle\Controller\Frontend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(Request $request)
    {
        return $this->render('frontend/default/index.html.twig', [
        ]);
    }

    /**
     * @Route("/history", name="history")
     * @Security("is_granted('ROLE_USER')")
     */
    public function history(Request $request)
    {
        return $this->render('frontend/default/history.html.twig', [
            'rentals' => $this->getUser()->getRentals(),
        ]);
    }
}
