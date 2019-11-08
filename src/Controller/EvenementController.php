<?php

namespace App\Controller;

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

class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="evenement")
     */
    public function index()
    {
        return $this->redirectToRoute('evenement.show');
    }


    /**
     * @Route("/evenement/show", name="evenement.show")
     */
    public function showEvenement(Request $request, Environment $twig, RegistryInterface $doctrine)
    {
        $evenements=$doctrine->getRepository(Evenement::class)->findAll();
        return new Response($twig->render('backOff/Evenement/showEvenements.html.twig', ['evenements' => $evenements]));

    }

    /**
     * @Route("/evenement/add", name="evenement.add")
     */
    public function addEvenement(Request $request, Environment $twig, RegistryInterface $doctrine, FormFactoryInterface $formFactory)
    {
        $form=$formFactory->createBuilder(EvenementType::class)->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $evenement=$form->getData();
            $doctrine->getEntityManager()->persist($evenement);
            $doctrine->getEntityManager()->flush();
            return $this->redirectToRoute('evenement.show');
        }
        return new Response($twig->render('backOff/Evenement/formEvenement.html.twig',['form'=>$form->createView()]));
    }

    /**
     * @Route("/evenement/delete", name="evenement.delete")
     */
    public function deleteEvenement(Request $request, Environment $twig, RegistryInterface $doctrine, FormFactoryInterface $formFactory)
    {
        $evenement=$doctrine->getRepository(Evenement::class)->find($request->query->get('id'));
        $doctrine->getEntityManager()->remove($evenement);
        $doctrine->getEntityManager()->flush();
        return $this->redirectToRoute('evenement.show');
    }

    /**
     * @Route("/evenement/edit", name="evenement.edit")
     */
    public function editEvenement(Request $request, Environment $twig, RegistryInterface $doctrine, FormFactoryInterface $formFactory)
    {
        $evenement=$doctrine->getRepository(Evenement::class)->find($request->query->get('id'));
        $form=$formFactory->createBuilder(EvenementType::class,$evenement)->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getEntityManager()->flush();
            return $this->redirectToRoute('evenement.show');
        }
        return new Response($twig->render('backOff/Evenement/formEvenement.html.twig',['form'=>$form->createView()]));
    }

}
