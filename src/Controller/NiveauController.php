<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NiveauRepository;

class NiveauController extends AbstractController
{
    /**
     *
     * @var NiveauRepository
     */
    private $repository;

    /**
     * 
     * @param NiveauRepository $repository
     */
    function __construct(NiveauRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * Retourne les niveaux
     * @Route("/niveaux", name="niveaux")
     * @return Response
     */
    public function index(): Response{
        $niveaux = $this->repository->findAll();
        return $this->render('formations/index.html.twig', [
            'niveaux' => $niveaux,
        ]);
    }
}