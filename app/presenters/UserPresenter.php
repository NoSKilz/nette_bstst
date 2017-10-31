<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Presenters;
use Nette;
use App\Model\AppModel;
use Nette\Application\UI\Form;
class UserPresenter extends Nette\Application\UI\Presenter
{
    private $appmodel,$best_games,$newest_games,$platforms,$genres;
    public function __construct(AppModel $appmodel)
    {
        $this->appmodel = $appmodel;
        $this->best_games = $this->appmodel->getBestGames();
        $this->newest_games = $this->appmodel->getNewGames(0);
        $this->platforms = $this->appmodel->getPlatforms();
        $this->genres = $this->appmodel->getGenres();
    }
    public function renderDefault()
    {
        if(!$this->user->isLoggedIn())
        {
            $this->flashMessage('Tam nemáte přístup','errors');
            $this->redirect('Homepage:');
        }
    }
    protected function createComponentHeader() 
    {
        $header = new \HeaderControl($this->appmodel,$this->platforms,$this->genres);
        return $header;
    }
    protected function createComponentBestgames() 
    {
        $bestgames = new \BestgamesControl($this->best_games);
        return $bestgames;
    }
    protected function createComponentNewgames() 
    {
        $newgames = new \NewgamesControl($this->newest_games);
        return $newgames;
    }
    protected function createComponentChangeEmailForm()
    {
        $form = new Form();
        $form->addEmail('nemail')
                ->addRule(Form::MIN_LENGTH, 'Nový e-mail musí mít alespoň %d znaků.',6)
                ->addRule(Form::MAX_LENGTH, 'Nový e-mail může mít maximálně %d znaků.',40)
                ->addRule(Form::EMAIL, 'Musíte zadat platnou emailovou adresu.')
                ->setRequired('Vyplňte svůj nový e-mail.');
        $form->addEmail('nemailc')
                ->addRule(Form::MIN_LENGTH, 'Nový e-mail musí mít alespoň %d znaků.',6)
                ->addRule(Form::MAX_LENGTH, 'Nový e-mail může mít maximálně %d znaků.',40)
                ->addRule(Form::EMAIL, 'Musíte zadat platnou emailovou adresu.')
                ->setRequired('Vyplňte svůj nový e-mail.');
        $form->addPassword('epassword')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky.',4)
                ->setRequired('Vyplňte své heslo.');
        $form->addSubmit('esubmit', 'Změnit e-mail');
        $form->onError[] = function($form)
        {
            foreach($form->errors as $error)
            {
                $this->flashMessage($error,'errors');
            }
            $this->redirect('this');
        };
        $form->onValidate[] = function ($form) {
            $error = FALSE;
            $mail = $this->appmodel->checkMail($form['nemail']->value);
            $cmail = $this->appmodel->checkMail($form['nemailc']->value);
            if($form['nemail']->value != $form['nemailc']->value)
            {
                $error=TRUE;
                $this->flashMessage('Emaily se musí shodovat.','errors');
            }
            if($mail || $cmail)
            {
                $error=TRUE;
                $this->flashMessage('Účet se zadanou emailovou adresou již existuje.','errors');
            }
            if($error)
            {
                $this->redirect('this');
            }
        };
        $form->onSuccess[] = [$this, 'changeEmailFormSucceeded'];
        return $form;
    }
    protected function createComponentChangePassForm()
    {
        $form = new Form();
        $form->addPassword('npassword')
                ->addRule(Form::MIN_LENGTH, 'Nové heslo musí mít alespoň %d znaky.',4)
                ->setRequired('Vyplňte své nové heslo.');
        $form->addPassword('npasswordc')
                ->addRule(Form::MIN_LENGTH, 'Nové heslo musí mít alespoň %d znaky.',4)
                ->setRequired('Vyplňte své nové heslo.');
        $form->addPassword('opassword')
                ->addRule(Form::MIN_LENGTH, 'Staré heslo musí mít alespoň %d znaky.',4)
                ->setRequired('Vyplňte své staré heslo.');
        $form->addSubmit('psubmit', 'Registrovat');
        $form->onError[] = function($form)
        {
            foreach($form->errors as $error)
            {
                $this->flashMessage($error,'errors');
            }
            $this->redirect('this');
        };
        $form->onValidate[] = function ($form) {
            $error = FALSE;
            if($form['npassword']->value != $form['npasswordc']->value)
            {
                $error = TRUE;
                $this->flashMessage('Hesla se musí shodovat.','errors');
            }
            if($error)
            {
                $this->redirect('this');
            }
        };
        $form->onSuccess[] = [$this, 'changePassFormSucceeded'];
        return $form;
    }
    public function changeEmailFormSucceeded($form, $values)
    {
        $result = $this->appmodel->changeEmail($values);
        if($result == 'errorpass')
        {
            $this->flashMessage('Zadali jste špatné heslo.','errors');
        }
        else if($result == 'error')
        {
            $this->flashMessage('Došlo k neznámé chybě, zkuste to později.','errors');
        }
        else
        {
            $this->flashMessage('Email byl úspěšně změněn.','success');
        }
        $this->redirect('User:');
    }
    public function changePassFormSucceeded($form, $values)
    {
        $result = $this->appmodel->changePass($values);
        if($result == 'errorpass')
        {
            $this->flashMessage('Zadali jste špatné heslo.','errors');
        }
        else if($result == 'error')
        {
            $this->flashMessage('Došlo k neznámé chybě, zkuste to později.','errors');
        }
        else
        {
            $this->flashMessage('Heslo bylo úspěšně změněno.','success');
        }
        $this->redirect('User:');
    }
    public function actionOut()
    {
        $this->user->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.','success');
        $this->redirect('Homepage:');
    }
}
