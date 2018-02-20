<?php

namespace Controller;

use Silex\Application;

/**
 * Class HomeController
 */
class AssetController
{
    /**
     * @param Application $app
     * @return string
     */
    public function list(Application $app)
    {
        return $app['twig']->render('asset/list.html.twig', [
            'assets' => [],
        ]);
    }
}
