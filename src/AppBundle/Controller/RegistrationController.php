<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 26.10.17
 * Time: 14.40
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\RegistrationManager;
use AppBundle\Service\ActivateManager;
use AppBundle\Service\EmailManager;

class RegistrationController extends Controller
{
    /**
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request,
                                       RegistrationManager $manager,\Swift_Mailer $mailer/**/) {

        //$mailer = new \Swift_Mailer();
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('quiz');
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->addUser($user);
            if ($answer=$this->sendEmail($mailer, $user)) {
                return $this->redirectToRoute('homepage');
            }else{
                echo 'fuck';
                echo $answer;
            }
        }
        return $this->render('form/registration.html.twig', [
            'form' => $form->createView(),
            'errors' => $form->getErrors(true, true)
        ]);
    }
    /**
     * @Route("/verifyEmail", name="verifyEmail")
     * @Method("GET")
     */
    public function verifyEmailAction(Request $request, ActivateManager $manager)
    {
        $token = $request->query->get('token');
        if (($token !== null) && ($manager->isTokenCorrect($token))) {
            $manager->activateUser($token);
            return $this->redirectToRoute('homepage');
        }
        return $this->render('default/error.html.twig');
    }

    private function sendEmail(\Swift_Mailer $mailer, User $user): bool
    {
        $body = $this->renderView(
            'email/activate.html.twig',
            ['token' => $user->getUserToken()->getToken()]
        );
        return EmailManager::sendMail($mailer, $user, 'Verify email', $body);
    }
}