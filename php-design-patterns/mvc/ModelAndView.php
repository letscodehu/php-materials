<?php

class ModelAndView {

    private $viewName;
    private $data;

    public function __construct($viewName, $data = []) {
        $this->viewName = $viewName;
        $this->data = $data;
    }

    public function getViewName() {
        return $this->viewName;
    }

    public function getData() {
        return $this->data;
    }

}