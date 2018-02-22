<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Entity\Asset;
use Silex\Application;
use Silex\Application as Applicatie;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Entity\Status;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormFactory as FormulierenFabriek;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request as Verzoek;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse as VerwijzVerzoek;

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

    /**
     * @param Applicatie $app
     * @return string
     */
    public function toevoegen(Applicatie $app)
    {
        $formulier = $this->maakFormulier($app);
        if ($formulier instanceof VerwijzVerzoek) {
            $app['session']->getFlashBag()->add('succes', 'Uw asset is toegevoegd.');

            return $formulier;
        }

        return $app['twig']->render('asset/niew.html.twig', ['formulier' => $formulier]);
    }

    /**
     * @param Applicatie $app
     * @param            $id
     * @return \Symfony\Component\Form\FormView|VerwijzVerzoek
     */
    public function bewerk(Applicatie $app, $id)
    {
        /** @var EntityManager $em */
        $em = $app['orm.em'];

        $assetResultaat = $em->createQuery('SELECT a FROM ' . Asset::class . ' a WHERE a.id = :id')->setParameter(':id', $id)->getResult();
        $asset = $assetResultaat[0];

        $formulier = $this->maakFormulier($app, $asset);

        if ($formulier instanceof VerwijzVerzoek) {
            $app['session']->getFlashBag()->add('succes', 'Uw asset is gewijzigd.');

            return $formulier;
        }

        return $app['twig']->render('asset/niew.html.twig', ['formulier' => $formulier]);
    }

    private function maakFormulier(Applicatie $app, $asset = null)
    {
        $statussen = $app['orm.em']->createQuery('SELECT s FROM ' . Status::class . ' s')->getResult();

        /** @var FormulierenFabriek $formulierFabriek */
        $formulierFabriek = $app['form.factory'];

        $formulier = $formulierFabriek->createBuilder()
            ->add('id', HiddenType::class)
            ->add('name', TextType::class)
            ->add('tag', TextType::class)
            ->add('device', TextType::class)
            ->add('brand', TextType::class)
            ->add('model', TextType::class)
            ->add('type', TextType::class)
            ->add('serial', TextType::class)
            ->add('status', ChoiceType::class, [
                    'choices'      => $statussen,
                    'choice_label' => function ($keuze, $key, $index) {
                        /** @var Status $keuze */
                        return strtoupper($keuze->getName());
                    },
                    'choice_attr'  => function ($keuze, $key, $index) {
                        /** @var Status $keuze */
                        return ['class' => 'category_' . strtolower($keuze->getName())];
                    },
                ]
            )
            ->add('productNumber', TextType::class)
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Asset opslaan',
            ])
            ->getForm();

        /**
         * @var Verzoek $verzoek
         */
        $verzoek = $app['request_stack']->getCurrentRequest();

        $formulier->handleRequest($verzoek);
        if ($formulier->isSubmitted() && $formulier->isValid()) {
            /** @var EntityManager $em */
            $em = $app['orm.em'];

            $data = $formulier->getData();

            if (!isset($asset)) {
                $asset = new Asset();
            }

            $asset
                ->setName($data['name'])
                ->setTag($data['tag'])
                ->setDevice($data['device'])
                ->setBrand($data['brand'])
                ->setModel($data['model'])
                ->setType($data['type'])
                ->setSerial($data['serial'])
                ->setProductNumber($data['productNumber'])
                ->setDescription($data['description']);

            $status = $em->createQuery('SELECT s FROM ' . Status::class . ' s WHERE s.id = :id')->setParameter(':id', $data['status'])->getResult();

            if (!empty($status)) {
                $asset->setStatus($status[0]);
            } else {
            }

            $app['orm.em']->persist($asset);
            $app['orm.em']->flush();

            return $app->redirect('/asset/list');
        } else {
            $data = [
                'name'          => $asset->getName(),
                'tag'           => $asset->getTag(),
                'device'        => $asset->getDevice(),
                'brand'         => $asset->getBrand(),
                'model'         => $asset->getModel(),
                'type'          => $asset->getType(),
                'serial'        => $asset->getSerial(),
                'productNumber' => $asset->getProductNumber(),
                'description'   => $asset->getDescription(),
                'status'        => $asset->getStatus()->getId(),
            ];
            $formulier->setData($data);
        }

        return $formulier->createView();
    }
}
