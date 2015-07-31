<?php
/**
 *  @author Renant Bernabé <contato@renant.com.br>
 */

// Ativa exibição de erro
error_reporting(1);

//Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Inicia as configurações globais do sistema
require '_libs/config.php';

// Autoload para classes 
require './_libs/autoload.php';

// Carregamento do Bootstrap(PHP)!
$bootstrap = new Bootstrap();
$bootstrap->init();

