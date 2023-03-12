<?php

namespace App\Controllers;

use \Core\View;
use \App\Helpers\NotificationHelper;
use App\Models\User;

class AuthBasicController extends \Core\Controller
{
    private const REALM = 'oPh?\dRG>B413a;E:5';

    public function loginAction()
    {
        if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
            header('WWW-Authenticate: Basic realm="' . self::REALM . '"');
            header('HTTP/1.1 401 Unauthorized', true, 401);

            View::renderTemplate('AuthBasic/login.html.twig');
            return;
        } else {
            $user = User::findByMailAddressAndPassword($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

            if ($user == null) {
                NotificationHelper::set('authBasic.login', 'danger', 'Le nom d\'utilisateur est invalide');
            } else {
                session_regenerate_id();

                NotificationHelper::set('authBasic.login', 'success', 'Le processus de connexion a réussi');

                $_SESSION['user'] = $user;
            }

            header('Location: /auth');
            exit;
        }
    }

    public function loginCancelAction()
    {
        NotificationHelper::set('authBasic.login', 'warning', 'Le processus de connexion a été annulé');
        header('Location: /auth');
        exit;
    }


    public function logoutAction()
    {
        session_destroy();
        session_start();

        header('WWW-Authenticate: Basic realm="' . self::REALM . '"');
        header('HTTP/1.1 401 Unauthorized', true, 401);

        NotificationHelper::set('authBasic.logout', 'success', 'Le processus de déconnexion a réussi');
        View::renderTemplate('AuthBasic/logout.html.twig');
    }

    public function logoutCancelAction()
    {
        NotificationHelper::set('authBasic.logout', 'success', 'Le processus de déconnexion a réussi');
        header('Location: /auth');
        exit;
    }
}
