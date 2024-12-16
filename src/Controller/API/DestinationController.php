<?php

namespace App\Controller\API;

use App\Entity\Destination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DestinationController extends AbstractController {

    #[Route('/api/destinations', name: 'api_destinations', methods: ['GET'])]
    public function index(EntityManagerInterface $em, Request $request): Response {
        $name = $request->query->get('name');
        $destinations = $em->getRepository(Destination::class)->findByFilters($name);
        return $this->json($destinations);
    }
}