<?php
namespace App\Controller\admin;

use App\Entity\Niveau;
use App\Repository\NiveauRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminNiveauxController
 * 
 * @author mc
 */
class AdminNiveauxController extends AbstractController{
    
    /**
     *
     * @var NiveauRepository
     */
    private $repository;

    /**
     *
     * @var EntityManagerInterface
     */
    private $om;

    /**
     * 
     * @param NiveauRepository $repository
     * @param EntityManagerInterface $om
     */
    function __construct(NiveauRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->om = $om;
    }
        
    /**
     * Fonction qui permet de supprimer un niveau
     * @Route("/admin/niveaux/suppr/{id}", name="admin.niveaux.suppr")
     * @param Niveau $niveau
     * @return Response
     */
    public function suppr(Niveau $niveau): Response{
        try{
            $this->om->remove($niveau);
            $this->om->flush();
            $this->addFlash('Bravo!', 'Vous avez supprimé le niveau!');
        }
        catch (ForeignKeyConstraintViolationException $e){
            $this->addFlash('impossible', 'Vous ne pouvez pas supprimer ce niveau... Des formations utilisent celui-ci');

        }
        return $this->redirectToRoute('admin.niveaux');
    }
    
    /**
     * Fonction qui permet d'ajouter un niveau
     * @Route("/admin/niveaux/ajout", name="admin.niveau.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response {
        if($this->isCsrfTokenValid('Ajout_token', $request->get('_token'))){
            $addNiveau = $request->get("Niveau");
            $niveau = new Niveau();
            $niveau->setLevel($addNiveau);
            $this->om->persist($niveau);
            $this->om->flush();
            $this->addFlash('Bravo!', 'Vous avez ajouté le niveau!');
        }
        return $this->redirectToRoute('admin.niveaux');
    }
    /**
     * Retourne les niveaux
     * @Route("/admin/niveaux", name="admin.niveaux")
     * @return Response
     */
    public function index(): Response{
        $niveaux = $this->repository->findAll();
        return $this->render("admin/admin.niveaux.html.twig", [
            'niveaux' => $niveaux,
        ]);
    }
    
}