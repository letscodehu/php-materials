<?php

class DummyRenderer
{
    public function render(ModelAndView $modelAndView)
    {
        extract($modelAndView->getData());
        echo json_encode($modelAndView->getData());
        // require "templates". DIRECTORY_SEPARATOR. $modelAndView->getViewName(). ".phtml";
    }
}
