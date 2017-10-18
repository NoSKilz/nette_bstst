<?php

namespace App\Presenters;
use Nette;
class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private $database,$user;
    public function __construct(Nette\Database\Context $database, Nette\Security\User $user)
    {
        $this->database = $database;
        $this->user = $user;
    }
    public function renderDefault()
    {
        $this->template->bests = $this->database->table('product')->select('product_id,product_name,platform_name,price')->order('sold DESC')->limit(20);
        $this->template->news = $this->database->table('product')->select('product_id,product_name,platform_name,price,picture,description')->order('uploaded DESC')->limit(8);
        $this->template->platforms = $this->database->table('platform')->select('platform_name');
        $this->template->genres = $this->database->table('genre')->select('genre_name');
    }
    protected function createComponentHeader() 
    {
        $header = new \HeaderControl($this->database,$this->user);
        return $header;
    }
    protected function createComponentBestgames() 
    {
        $bestgames = new \BestgamesControl;
        return $bestgames;
    }
    protected function createComponentNewgames() 
    {
        $newgames = new \NewgamesControl;
        return $newgames;
    }
    public function actionOut()
    {
        $this->user->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.','success');
        $this->redirect('Homepage:');
    }
}