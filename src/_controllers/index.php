<?php

/**
 *  @author Renant Bernabé <contato@renant.com.br>
 */
class Index extends Controller {

    function __construct() {
        parent::__construct();
        Session::init();
    }

    function index() {
        $this->view->title = 'MVC PHP';
        $this->view->render('index/index', TRUE);
    }
}
