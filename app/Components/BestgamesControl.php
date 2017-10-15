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
    public function render($bests)
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/Components_templates/bestgames.latte');
        $template->bests = $bests;
        $template->render();
    }
}