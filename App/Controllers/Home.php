<?php

namespace App\Controllers;

use App\Models\Articles;
use \Core\View;
use Exception;
use \App\Controllers\User;

/**
 * Home controller
 */
class Home extends \Core\Controller
{

    /**
     * Affiche la page d'accueil
     *
     * @return void
     * @throws \Exception
     */
    public static function indexAction()
    {
        \App\Controllers\User::loginWithCookies();
        
        View::renderTemplate('Home/index.html', []);      
        
    }
}
