<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Entity\Asset;
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
        /**
         * @var EntityManager $em
         */
        $em = $app['orm.em'];

        $assets = $em->createQueryBuilder()
            ->select('a')
            ->from(Asset::class, 'a')
            ->getQuery()
            ->getResult();

        return $app['twig']->render('asset/list.html.twig', [
            'assets' => $assets,
        ]);
    }
}
