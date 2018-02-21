<?php

namespace Controller;

use Silex\Application;
use Doctrine\ORM\EntityManager;
use Entity\Status;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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

    /**
     * Creates new status
     */
    public function create(Application $application)
    {
        $status = new Status();

        $formFactory = $application['form.factory'];
        $form = $formFactory->createBuilder(FormType::class, $status)
            ->add('id', HiddenType::class)
            ->add('name', TextType::class, ['required' => true])
            ->add('save', SubmitType::class, [
                'label' => 'Save status',
            ])
            ->getForm();

        $request = $application['request_stack']->getCurrentRequest();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $application['orm.em'];

            $em->persist($status);
            $em->flush();

            return $application->redirect('/statuses');
        }

        return $application['twig']->render('statuses/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}