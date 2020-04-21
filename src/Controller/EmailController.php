<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailController extends AbstractController
{
    public static function sendEmail(User $user)
    {
        if ($user->getAge() > 18) {
            return true;
        }
        return false;
    }
}
