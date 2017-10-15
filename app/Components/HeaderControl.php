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
}