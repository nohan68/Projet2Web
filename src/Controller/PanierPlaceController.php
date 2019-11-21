<?php
/**
 * Created by PhpStorm.
 * User: zalaahaz
 * Date: 15/11/19
 * Time: 08:35
 */

namespace App\Controller;


use App\Entity\Evenement;
use App\Entity\PanierPlace;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class PanierPlaceController extends AbstractController
{

    public $panier=[
        [ ]
    ];

    /**
     * @Route("/ajoutPanier/{n}", name="PanierPlace.ajout")
     */
    public function indexClient(Request $request, Environment $twig,RegistryInterface $doctrine, $n)
    {
        if($this->isGranted('ROLE_CLIENT')) {
            $evenement=$doctrine->getRepository(Evenement::class)->find($n);
            $panierPlace = new PanierPlace();
            //$panierPlace->setUserId($this->user);

            return $this->redirectToRoute('index.index');
        }
        return new Response($twig->render('accueil.html.twig'));

    }

    /**
     * @Route("/panier/show", name="PanierPlace.show")
     */
    public function showPanier(Request $request, Environment $twig, RegistryInterface $doctrine){
        $panier=$doctrine->getRepository(PanierPlace::class)->findAll();
        $evenements=$doctrine->getRepository(Evenement::class)->findAll();
        return new Response($twig->render('frontOff/frontOFFICE.html.twig', ['panier' => $panier, 'evenements' => $evenements]));
    }
}