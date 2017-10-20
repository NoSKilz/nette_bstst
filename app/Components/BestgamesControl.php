<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BestControl
 *
 * @author NoSkilz
 */
use Nette\Application\UI\Control;
class BestgamesControl extends Control
{
    private $best_games;
    public function __construct($best_games)
    {
        $this->best_games = $best_games;
    }
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/Components_templates/bestgames.latte');
        $template->best_games = $this->best_games;
        $template->render();
    }
}