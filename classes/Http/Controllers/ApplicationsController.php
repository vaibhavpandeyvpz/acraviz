<?php

namespace Acraviz\Http\Controllers;

use Acraviz\Support\Random;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Validator\Constraints as Assert;

class ApplicationsController extends BaseController
{
    public function index()
    {
        /** @var $factory \Symfony\Component\Form\FormFactoryInterface */
        $factory = $this->container['form.factory'];
        /** @var $random \RandomLib\Generator */
        $random = $this->container['random'];
        $form = $factory->createNamedBuilder('add_application', 'form')
            ->add('title', 'text', array(
                'attr' => array(
                    'placeholder' => 'fields.title.placeholder'
                ),
                'constraints' => array(
                    new Assert\Required(),
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 1,
                        'max' => 128
                    ))
                ),
                'label' => 'fields.title.label'
            ))
            ->add('package_name', 'text', array(
                'attr' => array(
                    'placeholder' => 'fields.package_name.placeholder'
                ),
                'constraints' => array(
                    new Assert\Required(),
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 1,
                        'max' => 255
                    ))
                ),
                'label' => 'fields.package_name.label'
            ))
            ->add('token', 'text', array(
                'attr' => array(
                    'readonly' => 'readonly',
                    'value' => $random->generateString(24)
                ),
                'constraints' => array(
                    new Assert\Required(),
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 24,
                        'max' => 24
                    ))
                ),
                'label' => 'fields.token.label'
            ))
            ->add('submit', 'submit', array(
                'attr' => array(
                    'class' => 'btn btn-primary',
                    'value' => 'add'
                ),
                'label' => 'buttons.add'
            ))
            ->getForm();
        $form->handleRequest($this->container['request']);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
            $session = $this->container['session'];
            $data = $form->getData();
            /** @var $qb \Doctrine\DBAL\Query\QueryBuilder */
            $qb = $this->container['db']->createQueryBuilder();
            $qb->insert('applications')
                ->values(array(
                    'created_at' => '?',
                    'package_name' => '?',
                    'title' => '?',
                    'token' => '?'
                ))
                ->setParameter(0, new \Datetime(), 'datetime')
                ->setParameter(1, $data['package_name'])
                ->setParameter(2, $data['title'])
                ->setParameter(3, $data['token']);
            try {
                if ($qb->execute() == 1) {
                    $session->getFlashBag()->add('success', 'alerts.applications.add_success');
                } else {
                    $session->getFlashBag()->add('danger', 'alerts.applications.add_failure');
                }
            } catch (UniqueConstraintViolationException $e) {
                $session->getFlashBag()->add('warning', 'alerts.applications.add_exists');
            }
        }
        /** @var $twig \Twig_Environment */
        $twig = $this->container['twig'];
        return $twig->render('applications.twig', array(
            'form' => $form->createView(),
            'routes' => array(
                'data' => 'datatables_applications',
                'delete' => 'delete_applications'
            ),
            'title' => 'titles.applications'
        ));
    }
}
