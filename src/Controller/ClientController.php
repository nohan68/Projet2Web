<?php
/**
 * Created by PhpStorm.
 * User: zalaahaz
 * Date: 29/11/19
 * Time: 08:13
 */

namespace App\Controller;


use App\Entity\Commande;
use App\Entity\Etat;
use App\Entity\User;
use App\Form\CoordonneeType;
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

/**
 * @package App\Controller
 * @Route(name="", path="/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */

class ClientController extends AbstractController {

    /**
     * @Route("/show", name="Client.show")
     */
    public function showClient(Request $request, Environment $twig, RegistryInterface $doctrine)
    {
        $clients=$doctrine->getRepository(User::class)->findAll();
        return new Response($twig->render('backOff/Clients/showClients.html.twig', ['clients' => $clients]));

    }

    /**
     * @Route("/client/delete", name="Client.delete")
     */
    public function deleteClient(Request $request, Environment $twig, RegistryInterface $doctrine, FormFactoryInterface $formFactory)
    {
        $user=$doctrine->getRepository(User::class)->find($request->query->get('id'));
        $doctrine->getEntityManager()->remove($user);
        $doctrine->getEntityManager()->flush();
        return $this->redirectToRoute('Client.show');
    }

    /**
     * @Route("/commande/show", name="Commande.expedier")
     */
    public function expedierCommande(Request $request, Environment $twig, RegistryInterface $doctrine){
        $commande=$doctrine->getRepository(Commande::class)->find($request->query->get('id'));
        $etat = $doctrine->getRepository(Etat::class)->find(2);
        $commande->setEtat($etat);
        $doctrine->getManager()->persist($commande);
        $doctrine->getEntityManager()->flush();
        return $this->redirectToRoute('Commande.show');
    }
}