<?php

/**
 *  @author Renant Bernabé  
 */
class Controller {

    function __construct() {
        session_start();
        $this->view = new View();
        $this->form = new Form();
    }
    
    /**
     * 
     * @param string $name Name of the model
     * @param string $path Location of the models
     */
    public function loadModel($name, $modelPath = '_models/') {

        $path = $modelPath . $name . '_model.php';

        if (file_exists($path)) {
            require $modelPath . $name . '_model.php';

            $modelName = $name . '_Model';
            $this->model = new $modelName();
        } 
    }

}
