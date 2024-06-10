<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Models\Cities;
use App\Models\User;
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
        $idVille = $_GET['idVille']; 

        $query = $_GET['sort'];

        if ($query == 'around'){
            $ville = Cities::searchById($idVille);
            $longitude = $ville[0]['ville_longitude_deg'];
            $latitude = $ville[0]['ville_latitude_deg'];
            $articles = Articles::getAround($longitude, $latitude);
        }else{
            $articles = Articles::getAll($query);
        }
        
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

    /**
     * On va reccuperer le nombre d'articles par mois
     * 
     * 
     * @throws Exception
     */
    public static function getNumberByMounthAction(){

        $articles = Articles::getNumberByMounth();

        header('Content-Type: application/json');
        
        echo json_encode($articles);
        
    }


    /**
     * On va reccuperer le nombre d'articles selon si ils sont actifs
     * 
     * @throws Exception
     */
    public static function getNumberByActifAction(){

        $articles = Articles::getNumberByActif();

        header('Content-Type: application/json');
        
        echo json_encode($articles);
        
    }

    /**
     * On va reccuperer le meilleur utilisateur, celui qui a donné le plus d'articles
     * 
     * @throws Exception
     */
    public static function getBestUserAction(){

        $username = User::getBestUser();

        header('Content-Type: application/json');
        
        echo json_encode($username);
        
    }

    /**
     * On va reccuperer la ville où le plus d'articles sont données
     * 
     * @throws Exception
     */
    public static function getBestCityAction(){

        $cityname = Cities::getBestCity();

        header('Content-Type: application/json');
        
        echo json_encode($cityname);
        
    }

    /**
     * On va reccuperer le meilleur article, celui vu le plus de fois
     * 
     * @throws Exception
     */
    public static function getBestArticleAction(){

        $articlename = Articles::getBestArticle();

        header('Content-Type: application/json');
        
        echo json_encode($articlename);
        
    }

    /**
     * On va reccuperer le nombre total d'utilisateur
     * 
     * @throws Exception
     */
    public static function getNbrUserAction(){

        $nbrUser = User::getNbrUser();

        header('Content-Type: application/json');
        
        echo json_encode($nbrUser);
        
    }

    /**
     * On va reccuperer le nombre d'utilisateur ayant deja posté un article
     * 
     * @throws Exception
     */
    public static function getNbrGifterAction(){

        $nbrUser = User::getNbrGifter();

        header('Content-Type: application/json');
        
        echo json_encode($nbrUser);
        
    }
}
