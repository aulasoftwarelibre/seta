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

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController as BaseAdminController;
use App\Entity\Locker;
use App\Entity\Penalty;
use App\Entity\Rental;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends BaseAdminController
{
    /**
     * @Route("/", name="easyadmin")
     */
    public function indexAction(Request $request)
    {
        // if the URL doesn't include the entity name, this is the index page
        if (null === $request->query->get('entity')) {
            // define this route in any of your own controllers
            return $this->redirectToRoute('admin_dashboard');
        }

        // don't forget to add this line to serve the regular backend pages
        return parent::indexAction($request);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @Route("/dashboard", name="admin_dashboard", methods={"GET"})
     */
    public function dashboardAction(Request $request)
    {
        return $this->render('backend/default/dashboard.html.twig');
    }

    /**
     * Asigna una taquilla libre a un usuario.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("user-new-rent", name="user_new_rent")
     */
    public function userNewRent(Request $request)
    {
        $id = $request->query->get('id');
        /** @var User $user */
        $user = $this->get('seta.repository.user')->find($id);

        if (!$user) {
            $this->addFlash('warning', 'El usuario no existe');
        } else {
            try {
                $this->get('seta.service.rental')->rentFirstFreeLocker($user);
                $this->addFlash('success', 'Nueva taquilla asignada');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->redirectToAction($request);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("user-list-rent", name="user_list_rent")
     */
    public function userListRent(Request $request)
    {
        return $this->redirectToAction($request);
    }

    /**
     * Cambia el estado de una taquilla.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("locker-lock", name="locker_lock")
     * @Route("locker-unlock", name="locker_unlock")
     */
    public function lockerToogleStatus(Request $request)
    {
        $id = $request->query->get('id');
        /** @var Locker $locker */
        $locker = $this->get('seta.repository.locker')->find($id);

        if (!$locker) {
            throw $this->createNotFoundException('La taquilla no existe.');
        }

        $status = $locker->getStatus();

        if ($status !== Locker::AVAILABLE && $status !== Locker::UNAVAILABLE) {
            $this->addFlash('warning', 'La taquilla debe ser devuelta antes de cambiar su estado.');
        } else {
            $locker->setStatus($status === Locker::AVAILABLE ? Locker::UNAVAILABLE : Locker::AVAILABLE);

            $this->get('doctrine.orm.default_entity_manager')->persist($locker);
            $this->get('doctrine.orm.default_entity_manager')->flush();
        }

        return $this->redirectToAction($request);
    }

    /**
     * Renueva un alquiler.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("rental-renew", name="rental_renew")
     */
    public function rentalRenew(Request $request)
    {
        $id = $request->query->get('id');
        /** @var Rental $rental */
        $rental = $this->get('seta.repository.rental')->find($id);

        if (!$rental) {
            throw $this->createNotFoundException('El alquiler indicado no existe.');
        }

        try {
            $this->get('seta.service.renew')->renewRental($rental);
            $this->addFlash('success', 'La taquilla ha sido renovada');
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToAction($request);
    }

    /**
     * Devuelve un alquiler.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("rental-return", name="rental_return")
     */
    public function rentalReturn(Request $request)
    {
        $id = $request->query->get('id');
        /** @var Rental $rental */
        $rental = $this->get('seta.repository.rental')->find($id);

        if (!$rental) {
            throw $this->createNotFoundException('El alquiler indicado no existe.');
        }

        try {
            $this->get('seta.service.return')->returnRental($rental);
            $this->addFlash('success', 'La taquilla ha sido devuelta');
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToAction($request);
    }

    /**
     * Cierra una sanción.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("penalty-close", name="penalty_close")
     */
    public function penaltyClose(Request $request)
    {
        $id = $request->query->get('id');
        /** @var Penalty $penalty */
        $penalty = $this->get('seta.repository.penalty')->find($id);

        if (!$penalty) {
            throw $this->createNotFoundException('La sanción indicada no existe.');
        }

        try {
            $this->get('seta.service.close_penalty')->closePenalty($penalty);
            $this->addFlash('success', 'La sanción se ha cerrado');
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToAction($request);
    }

    /**
     * Vuelve al listado del tipo de entidad que está en uso.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToAction(Request $request, $action = 'list')
    {
        $refererUrl = $request->query->get('referer', '');

        return !empty($refererUrl)
            ? $this->redirect(urldecode($refererUrl))
            : $this->redirect($this->generateUrl('easyadmin', ['action' => $action, 'entity' => $this->entity['name']]));
    }

    /**
     * Preconfigura un usuario.
     *
     * @return User
     */
    public function createNewUserEntity()
    {
        $user = new User();
        $group = $this->get('seta.repository.group')->findOneBy(['name' => 'Usuarios']);
        $user->addGroup($group);

        return $user;
    }

    /**
     * Lista los préstamos activos.
     *
     * @param $entityClass
     * @param $sortDirection
     * @param null $sortField
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createRentalListQueryBuilder($entityClass, $sortDirection, $sortField = null)
    {
        $queryBuilder = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField);
        $queryBuilder->andWhere($queryBuilder->expr()->isNull('entity.returnAt'));

        return $queryBuilder;
    }

    /**
     * Busca entra los préstamos activos.
     *
     * @param $entityClass
     * @param $searchQuery
     * @param array $searchableFields
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createRentalSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields)
    {
        $queryBuilder = parent::createSearchQueryBuilder($entityClass, $searchQuery, $searchableFields);
        $queryBuilder
            ->leftJoin('entity.user', 'user')
            ->leftJoin('entity.locker', 'locker')
            ->orWhere($queryBuilder->expr()->like('user.username', ':fuzzy_query'))
            ->orWhere($queryBuilder->expr()->like('user.nic', ':fuzzy_query'))
            ->orWhere($queryBuilder->expr()->like('locker.code', ':fuzzy_query'))
            ->andWhere($queryBuilder->expr()->isNull('entity.returnAt'));

        return $queryBuilder;
    }
}
