<?php
namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationRepository;
use App\Entity\Formation;
use App\Entity\Niveau;

/**
 * Description of AdminFormationsController
 *
 * @author monicatevy
 */
class AdminFormationsController extends AbstractController{

    private const PAGEADMINFORMATIONS = "admin/admin.formations.html.twig";

    /**
     *
     * @var FormationRepository
     */
    private $repository;

    /**
     *
     * @var EntityManagerInterface
     */
    private $om;

    /**
     * 
     * @param FormationRepository $repository
     * @param EntityManagerInterface $om
     */
    function __construct(FormationRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response {
        $formations = $this->repository->findAll();
        $niveauRepository = $this->getDoctrine()->getManager()->getRepository(Niveau::class);
        $niveaux = $niveauRepository->findAll();
        return $this->render(self::PAGEADMINFORMATIONS, [
            'formations' => $formations,
            'niveaux' => $niveaux
        ]);
    }

    /**
     * @Route("/admin/formations/tri/{champ}/{ordre}", name="admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $formations = $this->repository->findAllOrderBy($champ, $ordre);
        $niveauRepository = $this->getDoctrine()->getManager()->getRepository(Niveau::class);
        $niveaux = $niveauRepository->findAll();
        return $this->render(self::PAGEADMINFORMATIONS, [
           'formations' => $formations,
           'niveaux' => $niveaux
        ]);
    }   

    /**
     * @Route("/admin/formations/recherche/{champ}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("recherche");
            $formations = $this->repository->findByContainValue($champ, $valeur);
            $niveauRepository = $this->getDoctrine()->getManager()->getRepository(Niveau::class);
            $niveaux = $niveauRepository->findAll();
            return $this->render(self::PAGEADMINFORMATIONS, [
                'formations' => $formations,
                'niveaux' => $niveaux
            ]);
        }
        return $this->redirectToRoute("admin.formations");
    }

}