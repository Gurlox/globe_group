<?php

namespace App\Controller;

use App\Entity\Movie;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\RateManager;

class MovieController extends FOSRestController
{
    public function getMoviesAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository(Movie::class)->findForPagination();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->handleView(
            $this->view(
                $pagination,
                Response::HTTP_OK
            )
        );
    }

    public function getMovieDetailsAction(int $id)
    {
        $details = $this->getDoctrine()->getRepository(Movie::class)->findDetails($id);
        return $this->handleView(
            $this->view(
                $details,
                Response::HTTP_OK
            )
        );
    }

    public function postMovieRateAction(Request $request, int $id)
    {
        /** @var RateManager $rateManager */
        $rateManager = $this->container->get('app.rate_manager');
        return $this->handleView(
            $this->view(
                $rateManager->rateMovie($id, $request->request->get('rate', null))
            )
        );
    }
}