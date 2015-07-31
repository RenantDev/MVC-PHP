<?php

/**
 *  @author Renant Bernabé  
 */

// Verifica se a versão do php é compativel com o sistema
if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    echo'Para que o sistema funcione corretamente é preciso utilizar a v5.5.0+ do PHP.';
    exit;
}

// Carrega as classes do sistema
spl_autoload_register(function ($class) {
    // Define a string aux da classe
    $aux = $class;
    $pasta = explode("\\", $aux);
    $prefix = $pasta[0] . "\\";

    // Define a pasta do plugin
    $base_dir = LIBS . $pasta[0] . '/';

    // Caso não haja prefixo carrega a classe
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // Define o arquivo que sera carregado
        $file = LIBS . $class . '.php';

        // Carrega o arquivo de classe se ele existir
        if (file_exists($file)) {
            require $file;
        }
    }

    // Nome da classe
    $relative_class = substr($class, $len);

    // Define o arquivo que sera carregado
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Carrega o arquivo de classe se ele existir
    if (file_exists($file)) {
        require $file;
    }
});
