<?php

namespace App\Models;

use Core\Model;
use App\Core;
use DateTime;
use Exception;
use App\Utility;

/**
 * Articles Model
 */
class Articles extends Model {

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getAll($filter) {
        $db = static::getDB();

        $query = 'SELECT * FROM articles WHERE is_actif = 1';

        switch ($filter){
            case 'views':
                $query .= ' ORDER BY articles.views DESC';
                break;
            case 'data':
                $query .= ' ORDER BY articles.published_date DESC';
                break;
            case '':
                break;
        }
        $stmt = $db->query($query);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getAround($longitude, $latitude){
        $db = static::getDB();
        $stmt = $db->prepare( ' SELECT articles.*, articles.id as id, villes_france.ville_latitude_deg, villes_france.ville_longitude_deg, villes_france.ville_nom_reel,
                        (6371 * acos(cos(radians(:latitude)) * cos(radians(villes_france.ville_latitude_deg)) * cos(radians(villes_france.ville_longitude_deg) - radians(:longitude)) + sin(radians(:latitude)) * sin(radians(villes_france.ville_latitude_deg)))) AS distance
                    FROM articles
                    INNER JOIN villes_france ON articles.fk_ville = villes_france.ville_id
                    WHERE articles.is_actif = 1
                    ORDER BY distance');

            $stmt->bindParam(':longitude', $longitude);
            $stmt->bindParam(':latitude', $latitude);

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    
    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getOne($id) {
        $db = static::getDB();

        $stmt = $db->prepare('
        SELECT * FROM articles
        INNER JOIN users ON articles.user_id = users.id
        INNER JOIN villes_france ON articles.fk_ville = ville_id
        WHERE articles.id = ? AND is_actif = 1
        LIMIT 1');
        $stmt->execute([$id]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function addOneView($id) {
        $db = static::getDB();

        $stmt = $db->prepare('
            UPDATE articles 
            SET articles.views = articles.views + 1
            WHERE articles.id = ?');

        $stmt->execute([$id]);
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getByUser($id) {
        $db = static::getDB();

        $stmt = $db->prepare('
            SELECT *, articles.id as id FROM articles
            LEFT JOIN users ON articles.user_id = users.id
            WHERE articles.user_id = ? AND is_actif = 1');

        $stmt->execute([$id]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getSuggest() {
        $db = static::getDB();

        $stmt = $db->prepare('
            SELECT *, articles.id as id FROM articles
            INNER JOIN users ON articles.user_id = users.id
            WHERE is_actif = 1
            ORDER BY published_date DESC LIMIT 10');

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }



    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function save($data) {
        $db = static::getDB();

        $stmt = $db->prepare('INSERT INTO articles(name, description, user_id, published_date, fk_ville) VALUES (:name, :description, :user_id, :published_date, :fk_ville)');

        $published_date =  new DateTime();
        $published_date = $published_date->format('Y-m-d');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':published_date', $published_date);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':fk_ville', $data['fk_ville']);

        $stmt->execute();

        return $db->lastInsertId();
    }

    public static function deleteOne($idPicture){
        $db = static::getDB();

        $stmt = $db->prepare('UPDATE articles SET is_actif = 0 WHERE articles.picture = :picture;');

        $stmt->bindParam(':picture', $idPicture);

        $stmt->execute();
    }

    public static function attachPicture($articleId, $pictureName){
        $db = static::getDB();

        $stmt = $db->prepare('UPDATE articles SET picture = :picture WHERE articles.id = :articleid');

        $stmt->bindParam(':picture', $pictureName);
        $stmt->bindParam(':articleid', $articleId);


        $stmt->execute();
    }

    
    public static function getNumberByMounth()
    {
        $db = static::getDB();

        $stmt = $db->prepare("
        SELECT 
            DATE_FORMAT(published_date, '%Y-%m') AS month,
            COUNT(*) AS article_count
        FROM 
            articles
        GROUP BY 
            month
        ORDER BY 
            month;
        ");

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getNumberByActif()
    {
        $db = static::getDB();

        $stmt = $db->prepare("
        SELECT 
            is_actif,
            COUNT(*) AS article_count
        FROM 
            articles
        GROUP BY 
            is_actif
        ORDER BY
            is_actif;
        ");

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getBestArticle()
    {
        $db = static::getDB();

        $stmt = $db->prepare("
        SELECT name FROM articles ORDER BY views desc LIMIT 1;
        ");
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }



}
