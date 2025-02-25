<?php

namespace App\Models;

use App\Utility\Hash;
use Core\Model;
use App\Core;
use Exception;
use App\Utility;

/**
 * City Model:
 */
class Cities extends Model {

    public static function search($str, $bolCP) {
        $db = static::getDB();

        if ($bolCP == 1){ // Si le booléen CP est a true la recherche se fait par code postal
            $stmt = $db->prepare('SELECT ville_id, ville_nom_reel FROM villes_france WHERE ville_code_postal LIKE :query');
        }else{ // Sinon elle se fait par le nom de la ville
            $stmt = $db->prepare('SELECT ville_id, ville_nom_reel FROM villes_france WHERE ville_nom_reel LIKE :query');
        }
        
        $query = '%' . $str . '%';

        $stmt->bindParam(':query', $query);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_DEFAULT);
    }

    public static function searchById($id) {
        $db = static::getDB();

        if ($id != null){ 
            $stmt = $db->prepare('SELECT ville_id, ville_nom_reel, ville_code_postal, ville_longitude_deg, ville_latitude_deg FROM villes_france WHERE ville_id LIKE :query');
        }else {
            return false;
        }
        
        $query = $id;

        $stmt->bindParam(':query', $query);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_DEFAULT);
    }

    public static function getBestCity()
    {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT ville_nom_reel FROM videgrenierenligne.articles INNER JOIN villes_france on fk_ville = villes_france.ville_id GROUP BY ville_id ORDER BY count(*) desc limit 1;");

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


}
