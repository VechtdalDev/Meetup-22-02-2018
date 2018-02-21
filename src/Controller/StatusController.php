<?php

namespace Controller;

use Silex\Application;
use Doctrine\ORM\EntityManager;
use Entity\Status;

/**
 * Class StatusController
 */
class StatusController
{
    /**
     * @param Application $app
     *
     * @return mixed
     */
    public function index(Application $app)
    {
        /**
         * @var EntityManager $em
         */
        $em = $app['orm.em'];

        $statuses = $em->createQueryBuilder()
            ->select('s')
            ->from(Status::class, 's')
            ->getQuery()
            ->getResult();

        return $app['twig']->render('statuses/index.html.twig', [
            'statuses' => $statuses,
        ]);
    }
}