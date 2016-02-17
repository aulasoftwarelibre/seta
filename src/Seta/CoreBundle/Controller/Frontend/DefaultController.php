<?php

namespace Seta\CoreBundle\Controller\Frontend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
        $rentals = $this->get('seta.repository.rental')->getLastRentals($this->getUser());

        return $this->render('frontend/default/history.html.twig', [
            'rentals' => $rentals,
        ]);
    }

    /**
     * @Route("/renew/{code}", name="renew")
     * @Route("/renew/{code}", name="email_renew")
     * @Method(methods={"GET"})
     */
    public function emailRenew(Request $request, $code)
    {
        $rental = $this->get('seta.repository.rental')->findOneBy(['renewCode' => $code]);

        if (!$rental) {
            throw $this->createNotFoundException();
        }

        $error = null;

        try {
            $this->get('seta.service.renew')->renewRental($rental);
        } catch(\Exception $e) {
            $error = $e->getMessage();
        }

        return $this->render('frontend/default/renew.html.twig', [
            'error' => $error,
            'rental' => $rental,
        ]);
    }
}
