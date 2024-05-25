<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Models\Cities;
use \Core\View;
use Exception;
use App\Controllers\Home;

/**
 * API controller
 */
class Api extends \Core\Controller
{

    /**
     * Affiche la liste des articles / produits pour la page d'accueil
     *
     * @throws Exception
     */
    public function ProductsAction()
    {
        $query = $_GET['sort'];

        $articles = Articles::getAll($query);

        header('Content-Type: application/json');
        echo json_encode($articles);
    }

    /**
     * Recherche dans la liste des villes
     * Par query et si c'est une recherche par code postal ou par nom
     * 
     * @throws Exception
     */
    public function CitiesAction(){

        $cities = Cities::search($_GET['query'], $_GET['withcp']);
        header('Content-Type: application/json');
        echo json_encode($cities);
    }
     
    
    
    /**
     * Suppression soft d'un article
     * On passe le champ 'is_actif' a 0
     * 
     * @throws Exception
     */
    public function DeleteArticleAction(){

        $id = $_GET['id'];

        $articles = Articles::deleteOne($id);
        
    }

}
