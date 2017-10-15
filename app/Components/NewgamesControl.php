<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewControl
 *
 * @author NoSkilz
 */
use Nette\Application\UI\Control;
class NewgamesControl extends Control
{
    public function render($news)
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/Components_templates/newgames.latte');
        $template->news = $news;
        $template->render();
    }
}