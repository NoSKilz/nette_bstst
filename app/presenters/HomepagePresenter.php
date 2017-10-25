<?php

namespace App\Presenters;
use Nette;
use App\Model\AppModel;
class HomepagePresenter extends Nette\Application\UI\Presenter
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
    public function actionOut()
    {
        $this->user->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.','success');
        $this->redirect('Homepage:');
    }
}