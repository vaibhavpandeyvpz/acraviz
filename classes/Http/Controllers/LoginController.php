<?php

namespace Acraviz\Http\Controllers;

class LoginController extends BaseController
{
    public function index()
    {
        /** @var $factory \Symfony\Component\Form\FormFactoryInterface */
        $factory = $this->container['form.factory'];
        $form = $factory->createNamedBuilder(null, 'form')
            ->add('_username', 'text', array(
                'attr' => array(
                    'placeholder' => 'fields.username.placeholder'
                ),
                'label' => 'fields.username.label'
            ))
            ->add('_password', 'password', array(
                'attr' => array(
                    'placeholder' => 'fields.password.placeholder'
                ),
                'label' => 'fields.password.label'
            ))
            ->add('_remember_me', 'checkbox', array(
                'label' => 'fields.remember_me',
                'required' => false
            ))
            ->add('submit', 'submit', array(
                'attr' => array(
                    'class' => 'btn btn-primary',
                    'value' => 'login'
                ),
                'label' => 'buttons.login'
            ))
            ->getForm();
        $error = $this->container['security.last_error']($this->container['request']);
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $this->container['session'];
        if ($error) {
            $session->getFlashBag()->add('danger', $error);
        }
        /** @var $twig \Twig_Environment */
        $twig = $this->container['twig'];
        return $twig->render('login.twig', array(
            'form' => $form->createView(),
            'title' => 'titles.login',
            'username' => $session->get('_security.last_username')
        ));
    }
}
