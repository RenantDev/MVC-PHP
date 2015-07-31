<?php

class View {

    function __construct() {
        
    }

    public function render($name, $Include = false) {
        if ($Include == true) {
            // Gera codigo CSRF
            if (!Auth::get()) {
                Auth::gen();
            }
            require '_views/header.phtml';
            require '_views/' . $name . '.phtml';
            require '_views/footer.phtml';
        } else {
            require '_views/' . $name . '.phtml';
        }
    }

    public function renderCss($css) {
        $resultado = '';
        if (isset($css)) {
            foreach ($css as $value) {
                $resultado .= '<link href="' . URL . 'public/view/' . $value . '" rel="stylesheet" type="text/css" />';
            }
        }
        return $resultado;
    }

    public function renderJs($js) {
        $resultado = '';
        if (isset($js)) {
            foreach ($js as $value) {
                $resultado .= '<script type="text/javascript" src="' . URL . 'public/view/' . $value . '"></script>';
            }
        }
        return $resultado;
    }

    public function redirection($url) {
        echo '<meta http-equiv="refresh" content="0; url=' . $url . '" />';
        die();
    }

}
