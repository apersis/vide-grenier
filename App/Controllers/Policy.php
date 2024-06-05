<?php

namespace App\Controllers;

use \Core\View;

/**
 * Policy controller
 */
class Policy extends \Core\Controller
{
        /**
     * Page de politique de confidentialité
     */
    public function privacyAction()
    {
        View::renderTemplate('Policy/privacy.html');
    }

            /**
     * Page de politique relative aux cookies
     */
    public function cookieAction()
    {
        View::renderTemplate('Policy/cookie.html');
    }
}