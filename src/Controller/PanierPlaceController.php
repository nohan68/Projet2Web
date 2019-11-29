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

    /**
     * @Route("/ajoutPanier/{n}", name="PanierPlace.ajout")
     */
    public function ajoutPanier(Request $request, Environment $twig,RegistryInterface $doctrine, $n)
    {

        if($this->isGranted('ROLE_CLIENT')) {
            /*
            $evenement=$doctrine->getRepository(Evenement::class)->find($n);
            $evenement->setNombrePlaces($evenement->getNombrePlaces()-1);
            $panierPlace = new PanierPlace();
            $panierPlace->setUser($this->getUser());
            $panierPlace->setEvenement($evenement);
            $panierPlace->setQuantite(1);
            $panierPlace->setDateAjout(new \DateTime(date("Y-m-d")));
            $doctrine->getManager()->persist($panierPlace);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('index.index');
            */
            $evenementsAAjouter = $doctrine->getRepository(Evenement::class)->find($n);
            $user = $this->getUser();

            $doctrine->getRepository(Evenement::class)->find($n);
            $enregistrementDejaEnPanier = $doctrine->getRepository(PanierPlace::class)->findOneBy(array('user'=>$user,'evenement'=>$evenementsAAjouter));

            $evenementQtCourante = $evenementsAAjouter->getNombrePlaces();
            if ($evenementQtCourante > 0) {
                $evenementsAAjouter->setNombrePlaces($evenementQtCourante - 1);

                if ($enregistrementDejaEnPanier == null) {
                    $enregistrement = new PanierPlace();
                    $enregistrement->setUser($user)
                        ->setEvenement($evenementsAAjouter)
                        ->setDateAjout(new \DateTime(date('Y-m-d')))
                        ->setQuantite(1);
                    $doctrine->getEntityManager()->persist($enregistrement);
                    $doctrine->getEntityManager()->flush();
                } else {
                    // On incrémente le nombre d'évenements
                    $evenementPanier = $doctrine->getRepository(PanierPlace::class)->findOneBy(array('user' => $user, 'evenement' => $evenementsAAjouter));
                    $qtCourante = $evenementPanier->getQuantite();
                    $evenementPanier->setQuantite($qtCourante + 1);
                    $doctrine->getEntityManager()->persist($evenementPanier);
                    $doctrine->getEntityManager()->flush();
                }
            }
            return $this->redirectToRoute('index.index');
        }
        return new Response($twig->render('accueil.html.twig'));

    }

    /**
     * @Route("/supprimerPanier/{n}", name="PanierPlace.supprimer")
     */
    public function supprimerPanier(Request $request, Environment $twig,RegistryInterface $doctrine, $n)
    {
        if($this->isGranted('ROLE_CLIENT')) {
            $panier_place=$doctrine->getRepository(PanierPlace::class)->find($n);
            $panier_place->getEvenement()->setNombrePlaces($panier_place->getEvenement()->getNombrePlaces()+1);

            $doctrine->getManager()->remove($panier_place);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('index.index');
        }
        return new Response($twig->render('accueil.html.twig'));

    }

    /**
     * @Route("/panier/delete", name="PanierPlace.delete")
     */
    public function deletePanierPlace(Request $request, Environment $twig, RegistryInterface $doctrine)
    {
        $panier=$doctrine->getRepository(PanierPlace::class)->find($request->query->get('evenement_id'));
        $doctrine->getEntityManager()->remove($panier);
        $doctrine->getEntityManager()->flush();
        return $this->redirectToRoute('index.index');
    }
}