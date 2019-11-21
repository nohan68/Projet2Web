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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PanierPlaceController
{
    /**
     * @Route("/ajoutPanier/{n}", name="PanierPlace.ajout")
     */
    public function indexClient(Request $request, Environment $twig,RegistryInterface $doctrine, $n)
    {
        if($this->isGranted('ROLE_CLIENT')) {
            $evenement=$doctrine->getRepository(Evenement::class)->find($n);
            $panierPlace = new PanierPlace();
            //$panierPlace->setUserId($this->user);

            return $this->redirectToRoute('homepage');
        }
        return new Response($twig->render('accueil.html.twig'));

    }
}