<?php
/**
 * Created by PhpStorm.
 * User: zalaahaz
 * Date: 29/11/19
 * Time: 08:35
 */

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Etat;
use App\Entity\PanierPlace;
use App\Entity\Place;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use App\Entity\Evenement;
use App\Form\EvenementType;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

use Twig\Environment;                            // template TWIG
use Symfony\Bridge\Doctrine\RegistryInterface;   // ORM Doctrine
use Symfony\Component\HttpFoundation\Request;    // objet REQUEST

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;  // annotation security



class CommandeController extends AbstractController
{
    /**
     * @Route("/commande/show", name="Commande.show")
     */
    public function showCommande(Request $request, Environment $twig, RegistryInterface $doctrine)
    {
        $commandes=$doctrine->getRepository(Commande::class)->findAll();
        return new Response($twig->render('backOff/Commande/showCommande.html.twig', ['commandes' => $commandes]));
    }


    /**
     * @Route("/validerCommande", name="commande.valider")
     */
    public function supprimerPanier(Request $request, Environment $twig,RegistryInterface $doctrine)
    {
        if($this->isGranted('ROLE_CLIENT')) {

            $commande = new Commande();
            $etat = new Etat();
            $etat->setNom('En attente');
            $commande->setDate(new \DateTime(date('Y-m-d')));
            $commande->setUser($this->getUser());
            $commande->setEtat($etat);

            $doctrine->getManager()->persist($commande);
            $panier_place=$doctrine->getRepository(PanierPlace::class)->findAll();
            foreach ($panier_place as $place){
                $doctrine->getManager()->remove($place);
            }
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('index.index');
        }
        return new Response($twig->render('accueil.html.twig'));

    }

}