<?php

namespace Seta\CoreBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Seta\CoreBundle\Form\SendEmailType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RentalController
 * @package Seta\CoreBundle\Controller\Backend
 *
 * @Route("/email")
 */
class SendEmailController extends Controller
{
    /**
     * @Route("/new", name="seta_backend_email_new")
     * @Method(methods={"GET"})
     */
    public function newSendEmail()
    {
        $form = $this->createForm(SendEmailType::class, null, [
            'action' => $this->generateUrl('seta_backend_email_create'),
            'method' => 'POST',
        ]);
        
        return $this->render('backend/email/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name="seta_backend_email_create")
     * @Method(methods={"POST"})
     */
    public function createSendEmail(Request $request)
    {
        $form = $this->createForm(SendEmailType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $subject = $data['subject'];
            $to = $data['to'];
            $body = $data['body'];

            $this->addFlash('success', 'Mensaje enviado con éxito.');

            if ('rental' === $to) {
                $recipients = $this->get('seta.repository.user')->findAllEmailAddress();
            } elseif ('all' === $to) {
                $recipients = $this->get('seta.repository.user')->findAllEmailAddressWithActiveRental();
            } else {
                $recipients = [];
            }

            array_walk($recipients, function (&$entry) {
                $entry = $entry['email'];
            });

            $this->get('seta.mailer.notification')->send($recipients, $subject, $body);

            return $this->redirectToRoute('seta_backend_email_new');
        }

        return $this->render('backend/rental/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
