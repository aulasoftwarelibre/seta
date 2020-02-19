<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\Controller\Backend;

use App\Form\NewRentalType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RentalController
 * @package Seta\CoreBundle\Controller\Backend
 *
 * @Route("/rental")
 */
class RentalController extends AbstractController
{

    /**
     * @Route("/new", name="seta_backend_rental_new", methods={"GET"})
     */
    public function newRental(Request $request)
    {
        $form = $this->createForm(NewRentalType::class, null, [
            'action' => $this->generateUrl('seta_backend_rental_create'),
            'method' => 'POST',
        ]);

        return $this->render('backend/rental/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name="seta_backend_rental_create", methods={"POST"})
     */
    public function createRental(Request $request)
    {
        $form = $this->createForm(NewRentalType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $user = $data['user'];
            $zone = $data['zone'];

            try {
                $this->get('seta.service.rental')->rentFirstFreeZoneLocker($user, $zone);
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());

                return $this->render('backend/rental/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $this->addFlash('success', 'Taquilla alquilada con éxito');
            $this->getDoctrine()->getManager()->refresh($user);

            return $this->render('backend/rental/created.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('backend/rental/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
