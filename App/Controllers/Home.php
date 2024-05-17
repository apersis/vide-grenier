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
    public function indexAction()
    {
        \App\Controllers\User::loginWithCookies();
        
        View::renderTemplate('Home/index.html', []);

        //file_put_contents('C:\Users\Pc\Documents\CubeVideGrenier\logs.txt', print_r("apres renderTemplate ok\n", true), FILE_APPEND);
        
        

    }
}
