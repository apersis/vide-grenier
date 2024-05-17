<?php

namespace App\Controllers;

use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Hash;
use App\Utility\Session;
use \Core\View;
use Exception;
use http\Env\Request;
use http\Exception\InvalidArgumentException;

/**
 * User controller
 */
class User extends \Core\Controller
{

    /**
     * Affiche la page de login
     */
    public function loginAction()
    {
        if(isset($_POST['submit'])){
            $f = $_POST;

            // TODO: Validation

            $this->login($f);

            // Si login OK, redirige vers le compte
            header('Location: /account');
        }

        View::renderTemplate('User/login.html');
    }

    /**
     * Page de création de compte
     */
    public function registerAction()
    {
        if(isset($_POST['submit'])){
            $f = $_POST;

            if($f['password'] !== $f['password-check']){
                // TODO: Gestion d'erreur côté utilisateur
            }

            // validation

            $this->register($f);
            // TODO: Rappeler la fonction de login pour connecter l'utilisateur
        }

        View::renderTemplate('User/register.html');
    }

    /**
     * Affiche la page du compte
     */
    public function accountAction()
    {
        $articles = Articles::getByUser($_SESSION['user']['id']);

        View::renderTemplate('User/account.html', [
            'articles' => $articles
        ]);
    }

    /*
     * Fonction privée pour enregister un utilisateur
     */
    private function register($data)
    {
        try {
            // Generate a salt, which will be applied to the during the password
            // hashing process.
            $salt = Hash::generateSalt(32);

            // Appel a la fonction pour créer un utilisateur
            $userID = \App\Models\User::createUser([
                "email" => $data['email'],
                "username" => $data['username'],
                "password" => Hash::generate($data['password'], $salt), // On genère un hash a partir du salt
                "salt" => $salt,
                "fk_ville" => $data['fk_ville']
            ]);

            return $userID;

        } catch (Exception $ex) {
            // TODO : Set flash if error : utiliser la fonction en dessous
            /* Utility\Flash::danger($ex->getMessage());*/
        }
    }

    public static function loginWithCookies(){
        
        file_put_contents('C:\Users\Pc\Documents\CubeVideGrenier\logs.txt', print_r("ok function cookie\n", true), FILE_APPEND);

        // Verification de si il existe des cookies "se souvenir de moi"
        $cookieMailExist = \App\Utility\Cookie::exists("mail"); 
        $cookieMdpExist = \App\Utility\Cookie::exists("Hpassword");
        // Verification de si un utilisateur est deja connecté

        if (empty($_SESSION)){
            $isSessionConnecte = 0;
        }else{
            $isSessionConnecte = 1;
        }

        file_put_contents('C:\Users\Pc\Documents\CubeVideGrenier\logs.txt', print_r($isSessionConnecte, true), FILE_APPEND);

        if ($cookieMailExist && $cookieMdpExist && $isSessionConnecte == 0) {

            $email = \App\Utility\Cookie::get("mail"); 
            $password = \App\Utility\Cookie::get("Hpassword");

            $user = \App\Models\User::getByLogin($email);

            if ($password !== $user['password']) {
                return false;
            }

            $usercity = \App\Models\Cities::searchById($user['fk_ville']);

            $_SESSION['user'] = array(
                'id' => $user['id'],
                'username' => $user['username'],
                'city_id' => $usercity[0]['ville_id'],
                'city_name' => $usercity[0]['ville_nom_reel'],
                'city_code' => $usercity[0]['ville_code_postal'],
            );

            return true;

        }else{
            file_put_contents('C:\Users\Pc\Documents\CubeVideGrenier\logs.txt', print_r("non cookie \n", true), FILE_APPEND);

            return true;

        }
    }

    private function login($data){
        try {
            if(!isset($data['email'])){
                throw new Exception('TODO');
            }

            $user = \App\Models\User::getByLogin($data['email']);

            if (Hash::generate($data['password'], $user['salt']) !== $user['password']) {
                return false;
            }

            // TODO: Create a remember me cookie if the user has selected the option
            // to remained logged in on the login form.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L86

            
            // Si le bouton "se souvenir de moi" est coché, créer un cookie
            $remember = array_key_exists('#', $data);
    
            if ($remember) {
            \App\Utility\Cookie::put("mail", $data['email'] , 3600*24*365);
        \App\Utility\Cookie::put("Hpassword", $user['password'], 3600*24*365);
            }


            $usercity = \App\Models\Cities::searchById($user['fk_ville']);

            $_SESSION['user'] = array(
                'id' => $user['id'],
                'username' => $user['username'],
                'city_id' => $usercity[0]['ville_id'],
                'city_name' => $usercity[0]['ville_nom_reel'],
                'city_code' => $usercity[0]['ville_code_postal'],
            );

            return true;

        } catch (Exception $ex) {
            // TODO : Set flash if error
            /* Utility\Flash::danger($ex->getMessage());*/
        }
    }

    public static function createRememberCookie($userID) {
        $Db = Utility\Database::getInstance();
        $check = $Db->select("user_cookies", ["user_id", "=", $userID]);
        if ($check->count()) {
            $hash = $check->first()->hash;
        } else {
            $hash = Utility\Hash::generateUnique();
            if (!$Db->insert("user_cookies", ["user_id" => $userID, "hash" => $hash])) {
                return false;
            }
        }
        $cookie = Utility\Config::get("COOKIE_USER");
        $expiry = Utility\Config::get("COOKIE_DEFAULT_EXPIRY");
        return(Utility\Cookie::put($cookie, $hash, $expiry));
    }

    /**
     * Logout: Delete cookie and session. Returns true if everything is okay,
     * otherwise turns false.
     * @access public
     * @return boolean
     * @since 1.0.2
     */
    public function logoutAction() {

        /*
        if (isset($_COOKIE[$cookie])){
            // TODO: Delete the users remember me cookie if one has been stored.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L148
        }*/
        // Destroy all data registered to the session.

        // Check if a remember me cookie exists.
        $cookieMailExist = \App\Utility\Cookie::exists("mail");

        if ($cookieMailExist) {
            \App\Utility\Cookie::delete("mail");
        }

        $cookiePasswordExist = \App\Utility\Cookie::exists("Hpassword");

        if ($cookiePasswordExist) {
            \App\Utility\Cookie::delete("Hpassword");
        }

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header ("Location: /");

        return true;
    }

}
