<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of ProductPresenter
 *
 * @author NoSkilz
 */
namespace App\Presenters;
use Nette;
use App\Model\AppModel;
use Nette\Application\UI\Form;
class ProductPresenter extends Nette\Application\UI\Presenter
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
    public function renderShowGames($platform,$genre)
    {
        $this->template->games = $this->appmodel->getGames($platform, $genre);
    }
    public function renderShow($id)
    {
        $this->template->game = $this->appmodel->getGame($id);
        $this->template->comments = $this->appmodel->getComments($id);
        var_dump($this->getSession('cart'));
    }
    public function renderSearch($q)
    {
        $this->template->results = $this->appmodel->getSearch('%'.$q.'%');
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
    protected function createComponentBuyForm()
    {
        $form = new Form;
        $form->addHidden('productid')
                ->addRule(Form::MIN_LENGTH,'Nastala chyba, zkuste to znovu.',1)
                ->addRule(Form::INTEGER,'Nastala chyba, zkuste to znovu.')
                ->setRequired(TRUE);
        $form->addSubmit('pst','Přidat do košíku');
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
            $game = $this->appmodel->getGame($form['productid']->value);
            if($this->getParameter('id')!= $form['productid']->value)
            {
                $this->flashMessage('Nastala chyba, zkuste to znovu.','errors');
                $error = TRUE;
            }
            if($game['in_stock'] <= 0)
            {
                $this->flashMessage('Nastala chyba, zkuste to znovu.','errors');
                $error = TRUE;
            }
            if($error)
            {
                $this->redirect('this');
            }
        };
        $form->onSuccess[] = [$this, 'buyFormSucceeded'];
        return $form;
    }
    protected function createComponentCommentForm()
    {
        $form = new Form;
        $form->addtextArea('comment')
                ->addRule(Form::MIN_LENGTH,'Nastala chyba, zkuste to znovu.',1)
                ->addRule(Form::MAX_LENGTH,'Komentář může mít maximálně %d znaků.',500)
                ->setRequired(TRUE);
        $form->addSubmit('csubmit','Odeslat komentář');
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
            if(!$this->user->isLoggedIn())
            {
                $this->flashMessage('Nastala chyba, zkuste to znovu.','errors');
                $error = TRUE;
            }                           
            if($error)
            {
                $this->redirect('this');
            }
        };
        $form->onSuccess[] = [$this, 'commentFormSucceeded'];
        return $form;
    }
    public function buyFormSucceeded($form, $values)
    {
        /*not wokring :/ */
        $this->redirect('this');
    }
    public function commentFormSucceeded($form, $values)
    {
        $com = $this->appmodel->addComment($values,$this->getParameter('id'));
        if($com == 'error')
        {
            $this->flashMessage('Nastala chyba, zkuste to znovu.','errors');
            $this->redirect('this');
        }
        else
        {
            $this->redirect('this');
        }
    }
    public function actionOut()
    {
        $this->user->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.','success');
        $this->redirect('Homepage:');
    }
}
