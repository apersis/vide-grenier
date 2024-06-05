<?php

namespace App\Controllers;

use \Core\View;

/**
 * Stats controller
 */
class Stats extends \Core\Controller
{
    /**
     * Page admin de stats
     */
    public function statsAction()
    {
        View::renderTemplate('Stats/statistics.php');
    }
}