<?php

namespace Acraviz\Http\Controllers;

use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints as Assert;

class SettingsController extends BaseController
{
    public function index()
    {
        /** @var $factory \Symfony\Component\Form\FormFactoryInterface */
        $factory = $this->container['form.factory'];
        $form = $factory->createNamedBuilder('settings', 'form')
            ->add('password_new', 'password', array(
                'attr' => array(
                    'placeholder' => 'fields.password.placeholder'
                ),
                'constraints' => array(
                    new Assert\Required(),
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 8,
                        'max' => 32
                    ))
                ),
                'label' => 'fields.password_new'
            ))
            ->add('password_repeat', 'password', array(
                'attr' => array(
                    'placeholder' => 'fields.password.placeholder'
                ),
                'constraints' => array(
                    new Assert\Required(),
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 8,
                        'max' => 32
                    ))
                ),
                'label' => 'fields.password_repeat'
            ))
            ->add('submit', 'submit', array(
                'attr' => array(
                    'class' => 'btn btn-primary',
                    'value' => 'update'
                ),
                'label' => 'buttons.update'
            ))
            ->getForm();
        $form->handleRequest($this->container['request']);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
            $session = $this->container['session'];
            $data = $form->getData();
            $password = $data['password_new'];
            if ($password !== $data['password_repeat']) {
                $form->get('password_repeat')
                    ->addError(new FormError('errors.settings.password_mismatch'));
            } else {
                /** @var $user \Symfony\Component\Security\Core\User\UserInterface */
                $user = $this->container['security.token_storage']->getToken()->getUser();
                /** @var $factory \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface */
                $factory = $this->container['security.encoder_factory'];
                /** @var $qb \Doctrine\DBAL\Query\QueryBuilder */
                $qb = $this->container['db']->createQueryBuilder();
                $qb->update('users')
                    ->set('password', '?')
                    ->where($qb->expr()->eq('username', '?'))
                    ->setParameter(0, $factory->getEncoder($user)->encodePassword($password, null))
                    ->setParameter(1, $user->getUsername());
                if ($qb->execute() == 1) {
                    $session->getFlashBag()->add('success', 'alerts.settings.update_success');
                } else {
                    $session->getFlashBag()->add('danger', 'alerts.settings.update_failure');
                }
            }
        }
        /** @var $twig \Twig_Environment */
        $twig = $this->container['twig'];
        return $twig->render('settings.twig', array(
            'form' => $form->createView(),
            'title' => 'titles.settings'
        ));
    }
}
