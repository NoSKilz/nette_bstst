<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HeaderControl
 *
 * @author NoSkilz
 */
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
class HeaderControl extends Control
{
    public function render($platforms,$genres)
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/Components_templates/header.latte');
        $template->platforms = $platforms;
        $template->genres = $genres;
        $template->render();
    }
    protected function createComponentSignInForm()
    {
        $form = new Form;
        $form->getElementPrototype()->id='login';
        $form->getElementPrototype()->style=['display'=>'none'];
        $form->addText('lusername', 'Uživatelské jméno')->setRequired('Prosím vyplňte své uživatelské jméno.');
        $form->addPassword('lpassword', 'Heslo')->setRequired('Prosím vyplňte své heslo.');
        $form->addSubmit('lsubmit', 'Přihlásit');
        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }
    protected function createComponentRegisterForm()
    {
        $form = new Form;
        $form->getElementPrototype()->id='register';
        $form->getElementPrototype()->style=['display'=>'none'];
        $form->addText('rusername', 'Uživatelské jméno')->setRequired('Prosím vyplňte své uživatelské jméno.');
        $form->addPassword('rpassword', 'Heslo')->setRequired('Prosím vyplňte své heslo.');
        $form->addPassword('checkpassword', 'Heslo znovu')->setRequired('Prosím vyplňte své heslo.');
        $form->addEmail('email', 'Email')->setRequired('Prosím vyplňte svůj email.');
        $form->addEmail('checkemail', 'Email znovu')->setRequired('Prosím vyplňte svůj email.');
        $form->addSubmit('rsubmit', 'Registrovat');
        $form->onSuccess[] = [$this, 'signRegisterSucceeded'];
        return $form;
    }
}