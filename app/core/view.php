<?php

class View {

    private $path = null;

    public function __construct() {
        $this->path = 'http://' . $_SERVER['HTTP_HOST'] . '/';
    }

    public $title = "Тестовое задние Склярова Петра";

    function render($content_view, $template_view, $data = null) {

        if (is_array($data)) {
            extract($data);
        }
        include __DIR__ . '/../views/templates/' . $template_view;
    }


}

?>
