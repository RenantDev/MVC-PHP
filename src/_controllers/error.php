<?php
/**
 *  @author Renant Bernabé <contato@renant.com.br>
 */
class Error extends Controller {

    function __construct() {
        parent::__construct(); 
    }
    
    function index() {
        $this->view->title = 'Erro 404';
        $this->view->render('error/index', FALSE);
    }

} 