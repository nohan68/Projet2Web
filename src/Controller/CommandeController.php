<?php
/**
 * Created by PhpStorm.
 * User: zalaahaz
 * Date: 29/11/19
 * Time: 08:35
 */

namespace App\Controller;

use App\Entity\Commande;
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
class CommandeController
{
    /**
     * @Route("/commande/show", name="Commande.show")
     */
    public function showCommande(Request $request, Environment $twig, RegistryInterface $doctrine)
    {
        $commandes=$doctrine->getRepository(Commande::class)->findAll();
        return new Response($twig->render('backOff/Commande/showCommande.html.twig', ['commandes' => $commandes]));
    }
}