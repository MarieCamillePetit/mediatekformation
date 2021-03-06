<?php
namespace App\Controller\admin;

use App\Entity\Formation;
use App\Entity\Niveau;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminFormationsController
 *
 * @author mc
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
        $this->om = $om;
    }

    /**
     * Retourne les formations
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
     * Fonction qui permet le trie des formations
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
     * Fonction qui retourne les formations qui ont ??t?? recherch??es
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
    
    /**
     * Fonction qui permet de supprimer une formation
     * @Route("/admin/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response
     */
    public function suppr(Formation $formation): Response{
        $this->om->remove($formation);
        $this->om->flush();
        $this->addFlash('Bravo!', 'Vous avez supprim?? la formation!');
        return $this->redirectToRoute('admin.formations');
    }
    
    
    /**
     * Fonction qui permet d'??diter une formation 
     * @Route("/admin/edit/{id}", name="admin.formation.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function edit(Formation $formation, Request $request): Response
    {
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->om->flush();
            $this->addFlash('Bravo!', 'Vous avez ??dit?? la formation!');
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render("admin/admin.formation.edit.html.twig", [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
            ]);
    }
    
    /**
     * Fonction qui permet l'ajout d'une formation gr??ce ?? la form FormationType
     * @Route("/admin/ajout", name="admin.formations.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->om->persist($formation);
            $this->om->flush();
            $this->addFlash('Bravo!', 'Vous avez ajout?? la formation!');
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render("admin/admin.formations.ajout.html.twig", [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
}