<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 31.10.17
 * Time: 0.10
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends Controller
{
    /**
     * @Route("/quiz", name="quiz")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function quizAction(){
        return new Response('hello');
    }
}