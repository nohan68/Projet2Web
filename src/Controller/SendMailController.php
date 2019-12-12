<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SendMailController extends AbstractController
{
    /**
     * @Route("/forgotten_password", name="app_forgotten_password")
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator, RegistryInterface $doctrine): Response
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $user = $doctrine->getRepository(User::class)->find($email);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('app_forgotten_password');
            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $doctrine->getEntityManager()->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_forgotten_password');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom('projetweb@dev-web.io')
                ->setTo($user->getEmail())
                ->setBody(
                    "Cliquez sur ce lien pour réinitialiser votre mot de passe : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('app_forgotten_password');
        }

        return $this->render('security/forgotPassword.html.twig');
    }
}