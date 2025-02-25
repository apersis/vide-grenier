<?php

namespace App\Models;

use App\Utility\Hash;
use Core\Model;
use App\Core;
use Exception;
use App\Utility;

/**
 * User Model:
 */
class User extends Model {

    /**
     * Crée un utilisateur
     */
    public static function createUser($data) {
        $db = static::getDB();

        $stmt = $db->prepare('INSERT INTO users(username, email, password, salt, fk_ville) VALUES (:username, :email, :password,:salt,:fk_ville)');

        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':salt', $data['salt']);
        $stmt->bindParam(':fk_ville', $data['fk_ville']);

        $stmt->execute();

        return $db->lastInsertId();
    }

    public static function getByLogin($login)
    {
        $db = static::getDB();

        $stmt = $db->prepare("
            SELECT * FROM users WHERE ( users.email = :email) LIMIT 1
        ");

        $stmt->bindParam(':email', $login);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function login() {
        $db = static::getDB();

        $stmt = $db->prepare('SELECT * FROM articles WHERE articles.id = ? LIMIT 1');

        $stmt->execute([$id]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getBestUser()
    {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT username FROM videgrenierenligne.articles INNER JOIN users on user_id = users.id GROUP BY user_id ORDER BY count(*) desc limit 1;");

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getNbrUser()
    {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT count(*) as nombre FROM users;");

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public static function getNbrGifter()
    {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT COUNT(DISTINCT users.id) as nombre
        FROM users
        JOIN articles ON users.id = articles.user_id;");

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}
