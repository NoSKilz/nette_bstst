<?php

namespace App\Presenters;
use Nette;
use App\Model\AppModel;
class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private $appmodel;
    public function __construct(AppModel $appmodel)
    {
        $this->appmodel = $appmodel;
    }
    public function renderDefault()
    {
        $this->template->bests = $this->appmodel->getBestGames();
        $this->template->news = $this->appmodel->getNewGames(8);
        $this->template->platforms = $this->appmodel->getPlatforms();
        $this->template->genres = $this->appmodel->getGenres();
    }
    protected function createComponentHeader() 
    {
        $header = new \HeaderControl($this->appmodel);
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