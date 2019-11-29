<?php


namespace App\Controller;

use App\Form\CoordonneeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use App\Entity\User;

use App\Entity\Evenement;
use App\Form\EvenementType;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

use Twig\Environment;                            // template TWIG
use Symfony\Bridge\Doctrine\RegistryInterface;   // ORM Doctrine
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;  // annotation security

class AccountController extends AbstractController
{

    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(ResetPasswordType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $passwordEncoder = $this->get('security.password_encoder');
            $oldPassword = $request->request->get('etiquettebundle_user')['oldPassword'];

            // Si l'ancien mot de passe est bon
            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $newEncodedPassword = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($newEncodedPassword);

                $em->persist($user);
                $em->flush();

                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('profile');
            } else {
                $form->addError(new FormError('Ancien mot de passe incorrect'));
            }
        }

        return $this->render('account/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/coordonnees/show", name="Coordonnees.show")
     */
    public function showCoordonnees(Request $request, Environment $twig, RegistryInterface $doctrine)
    {
        $coordonnees=$doctrine->getRepository(User::class)->findBy(['id'=>$this->getUser()]);
        return new Response($twig->render('frontOff/Coordonnees/showCoordonnees.html.twig', ['coordonnees' => $coordonnees]));

    }

    /**
     * @Route("/coordonnees/edit", name="Coordonnees.edit")
     */
    public function editCoordnnees(Request $request, Environment $twig, RegistryInterface $doctrine, FormFactoryInterface $formFactory)
    {
        $coordonnees=$doctrine->getRepository(User::class)->find($request->query->get('id'));
        $form=$formFactory->createBuilder(CoordonneeType::class,$coordonnees)->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getEntityManager()->flush();
            return $this->redirectToRoute('Coordonnees.show');
        }
        return new Response($twig->render('frontOff/Coordonnees/formCoordonnees.html.twig',['form'=>$form->createView()]));
    }
}