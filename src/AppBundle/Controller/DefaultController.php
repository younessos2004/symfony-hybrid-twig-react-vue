<?php

namespace AppBundle\Controller;

use AppBundle\State\AppState;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    /**
     * @Route("/api", name="api")
     */
    public function apiApartmentsAction(Request $request)
    {

        $appState = new AppState();

        $em = $this->get('doctrine.orm.default_entity_manager');
        $apartments = $em->getRepository('AppBundle:Apartment')->findAll();

        $appState->setApartments($apartments);

        $response = new JsonResponse();

        $response->setContent($appState->jsonSerialize());

        return $response;

    }


    /**
     * @Route("/{limit}", name="homepage")
     */
    public function indexAction(Request $request, int $limit = 3 )
    {

        $appState = new AppState();

        $em = $this->get('doctrine.orm.default_entity_manager');
        $apartments = $em->getRepository('AppBundle:Apartment')->findByLimit($limit);

        $appState->setApartments($apartments);

        return $this->render('default/index.html.twig', [
            'appstate' => $appState,
            'appstate_serialized' => $appState->jsonSerialize()
        ]);
    }

    /**
     * @Route("/country/{country}", name="filtered")
     */
    public function filteredAction(Request $request, $country=false )
    {

        $appState = new AppState();

        $em = $this->get('doctrine.orm.default_entity_manager');
        $apartments = $em->getRepository('AppBundle:Apartment')->findByCountry($country);

        $appState->setSelectedCountry($country);
        $appState->setSortBy($country);
        $appState->setApartments($apartments);

        return $this->render('default/index.html.twig', [
            'appstate' => $appState,
            'appstate_serialized' => $appState->jsonSerialize()
        ]);
    }

}
