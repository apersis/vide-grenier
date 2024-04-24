<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Utility\Upload;
use \Core\View;

/**
 * Product controller
 */
class Product extends \Core\Controller
{

    /**
     * Affiche la page d'ajout
     * @return void
     */

    /*
    *TLR : issue #19 -> image volumineuse
    *Fonction qui vérifié la taille, l'extension et s'il y a un nom de l'image dans le champs 
    *avant la création de la page du produit + ajout de message
    */
    public function indexAction()
    {
        $error = "";
        if(isset($_POST['submit'])) {
            try {
                $f = $_POST;
                if (!empty($_FILES['picture']['name'])) {
                    if ($_FILES['picture']['size'] > 0 && $_FILES['picture']['size'] < 4000000) {
                        $fileExtension = exif_imagetype($_FILES['picture']['tmp_name']);
                        $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
                        if (in_array($fileExtension, $allowedTypes)) {
                            $f['user_id'] = $_SESSION['user']['id'];
                            $id = Articles::save($f);
                            $pictureName = Upload::uploadFile($_FILES['picture'], $id);
                            if ($pictureName) {
                                Articles::attachPicture($id, $pictureName);
                                header('Location: /product/' . $id);
                            }else {
                                unlink($_FILES['picture']['tmp_name']);
                                $error = "L'upload a échoué.";
                            }
                        }else{
                            $error = "L'extension de l'image n'est pas la bonne.";
                            
                        }
                    }else{
                        $error = "La taille de l'image est trop volumineuse.";
                    }
                }else{
                    $error = "Pas de nom de fichier.";
                }
            } catch (\Exception $e) {

            }
        }
    
        View::renderTemplate('Product/Add.html', ['error' => $error]);
    }
    

    /**
     * Affiche la page d'un produit
     * @return void
     */
    public function showAction()
    {
        $id = $this->route_params['id'];

        try {
            Articles::addOneView($id);
            $suggestions = Articles::getSuggest();
            $article = Articles::getOne($id);
        } catch(\Exception $e){
            var_dump($e);
        }

        View::renderTemplate('Product/Show.html', [
            'article' => $article[0],
            'suggestions' => $suggestions
        ]);
    }
}
