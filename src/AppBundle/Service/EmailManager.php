<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 1.11.17
 * Time: 13.39
 */

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Exception;

class EmailManager
{
    const ADDRESS = 'yurasiustest@gmail.com';

    public static function sendMail(\Swift_Mailer $mailer, User $user, string $subject, string $body): bool
    {
        try{
            $message = (new \Swift_Message($subject))
                ->setFrom(['yurasiustest@gmail.com' => 'quiz'])
                ->setTo($user->getEmail())
                ->setBody($body, 'text/html');
            $mailer->send($message);
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}