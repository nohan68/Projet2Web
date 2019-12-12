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
use App\Entity\User;
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
            $etat = $doctrine->getRepository(Etat::class)->find(1);
            $commande->setDate(new \DateTime(date('Y-m-d')));
            $commande->setUser($this->getUser());
            $commande->setEtat($etat);
            $doctrine->getManager()->persist($commande);


            $panier_place=$doctrine->getRepository(PanierPlace::class)->findAll();
            foreach ($panier_place as $place){
                $nouvellePlace = new Place();
                $nouvellePlace->setCommande($commande);
                $nouvellePlace->setEvenement($place->getEvenement());
                $nouvellePlace->setPrix($place->getEvenement()->getPrix());
                $nouvellePlace->setQuantite($place->getQuantite());
                $doctrine->getManager()->remove($place);
                $doctrine->getManager()->persist($nouvellePlace);
            }
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('index.index');
        }
        return new Response($twig->render('accueil.html.twig'));
    }

    /**
     * @Route("/commandeClient/show", name="MesCommandes.show")
     */
    public function showCommandeClient(Request $request, Environment $twig, RegistryInterface $doctrine)
    {
        $commandes=$doctrine->getRepository(Commande::class)->findBy(['user'=>$this->getUser()]);
        return new Response($twig->render('frontOff/Commande/showCommandes.html.twig', ['commandes' => $commandes]));
    }

    /*
    /**
     * @Route("/detailCommande/show", name="MesCommandes.detail")
     */
    /*
    public function commandeDetails(Request $request, Environment $twig, RegistryInterface $doctrine)
    {
        $produits=$doctrine->getRepository(Commande::class)->findBy(['user'=>$this->getUser()]);
        return new Response($twig->render('frontOff/Commande/detailCommande.html.twig', ['produits' => $produits]));
    } */

    /**
     * @Route("/detailCommande/{id}", name="MesCommandes.detail", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function commandeDetails(Request $request, Environment $twig, RegistryInterface $doctrine, $id = null)
    {
        $commande = $doctrine->getRepository(Commande::class)->findBy(array('id' => $id));
        $places = $doctrine->getRepository(Place::class)->findBy(array('commande' => $commande));

        return new Response($twig->render('frontOff/Commande/detailCommande.html.twig', ['places' => $places]));
    }
}