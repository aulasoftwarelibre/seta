<?php

namespace Seta\CoreBundle\Controller\Frontend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Seta\RentalBundle\Entity\Rental;
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
     * @Route("/rental/{rental}/renew", name="renew")
     * @Security("is_granted('rent', rental)")
     */
    public function renew(Request $request, Rental $rental)
    {
        try {
            $this->get('seta.service.renew')->renewRental($rental);

            $this->addFlash('positive', 'Taquilla renovada con éxito.');
        } catch(\Exception $e) {
            $this->addFlash('negative', $e->getMessage());
        }

        return $this->redirectToRoute('history');
    }

    /**
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
