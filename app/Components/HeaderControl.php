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
use Nette\Security\Passwords;
class HeaderControl extends Control
{
    private $database;
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
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
        $form->addText('lusername', 'Uživatelské jméno')
                ->addRule(Form::MIN_LENGTH, 'Uživatelské jméno musí mít alespoň %d znak.',1)
                ->addRule(Form::MAX_LENGTH, 'Uživatelské jméno může mít maximálně %d znaků.', 20)
                ->setRequired('Vyplňte své uživatelské jméno.');
        $form->addPassword('lpassword', 'Heslo')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky.',4)
                ->setRequired('Vyplňte své heslo.');
        $form->addSubmit('lsubmit', 'Přihlásit');
        $form->onError[]= function($form)
        {
            foreach($form->errors as $error)
            {
                $this->presenter->flashMessage($error,'errors');
            }
            $this->presenter->redirect('Homepage:');
        };
        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }
    protected function createComponentRegisterForm()
    {
        $form = new Form;
        $form->addText('rusername', 'Uživatelské jméno')
                ->addRule(Form::MIN_LENGTH, 'Uživatelské jméno musí mít alespoň %d znak.',1)
                ->addRule(Form::MAX_LENGTH, 'Uživatelské jméno může mít maximálně %d znaků.', 20)
                ->setRequired('Vyplňte své uživatelské jméno.');
        $form->addPassword('rpassword', 'Heslo')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky.',4)
                ->setRequired('Vyplňte své heslo.');
        $form->addPassword('checkpassword', 'Heslo znovu')
                ->addRule(Form::MIN_LENGTH, 'Kontrolní heslo musí mít alespoň %d znaky.',4)
                ->setRequired('Vyplňte kontrolní heslo.');
        $form->addEmail('email', 'Email')
                ->addRule(Form::MIN_LENGTH, 'E-mail musí mít alespoň %d znaků.',6)
                ->addRule(Form::MAX_LENGTH, 'E-mail může mít maximálně %d znaků.', 40)
                ->addRule(Form::EMAIL, 'Musíte zadat platnou emailovou adresu.')
                ->setRequired('Vyplňte svůj e-mail.');
        $form->addEmail('checkemail', 'Email znovu')
                ->addRule(Form::MIN_LENGTH, 'Kontrolní e-mail musí mít alespoň %d znaků.',6)
                ->addRule(Form::MAX_LENGTH, 'Kontrolní e-mail může mít maximálně %d znaků.', 40)
                ->addRule(Form::EMAIL, 'Musíte zadat platnou emailovou adresu.')
                ->setRequired('Vyplňte kontrolní e-mail.');
        $form->addSubmit('rsubmit', 'Registrovat');
        $form->onValidate[] = function ($form) {
            $error=FALSE;
            $name=$this->database->table('user')->get(['user_name'=>$form['rusername']->value]);
            $email=$this->database->table('user')->get(['user_email'=>$form['email']->value]);
            if($name)
            {
                $error=TRUE;
                $this->presenter->flashMessage('Účet se zadaným uživatelským jménem již existuje.','errors');
            }
            if($email)
            {
                $error=TRUE;
                $this->presenter->flashMessage('Účet se zadanou emailovou adresou již existuje.','errors');
            }
            if($form['rpassword']->value != $form['checkpassword']->value)
            {
                $error=TRUE;
                $this->presenter->flashMessage('Hesla se musí shodovat.','errors');
            }
            if($form['email']->value != $form['checkemail']->value)
            {
                $error=TRUE;
                $this->presenter->flashMessage('Emaily se musí shodovat.','errors');
            }
            if($error)
            {
                $this->presenter->redirect('Homepage:');
            }
        };
        $form->onError[]= function($form)
        {
            foreach($form->errors as $error)
            {
                $this->presenter->flashMessage($error,'errors');
            }
            $this->presenter->redirect('Homepage:');
        };
        $form->onSuccess[] = [$this, 'registerFormSucceeded'];
        return $form;
    }
    public function signInFormSucceeded($form, $values)
    {
    }
    public function registerFormSucceeded($form, $values)
    {
        $password=Passwords::hash($values->rpassword,['cost' => 12]);
        try
        {
            $this->database->table('user')->insert([
                'user_name'=>$values->rusername,
                'password'=>$password,
                'joined'=>date('Y-m-d H:i:s'),
                'user_email'=>$values->email,
                'admin'=>0
                ]);
        } 
        catch(Exception $e) 
        {
            $this->presenter->flashMessage('Došlo k neznámé chybě, zkuste to později.','errors');
        }
        $this->presenter->flashMessage('Byl jste úspěšně registrován.','success');
        $this->presenter->redirect('Homepage:');
    }
}