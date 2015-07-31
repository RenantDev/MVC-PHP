<?php

// Configurações Gerais
define('NOME_PROJETO', 'MVC-PHP'); // Nome do Projeto
define('URL', 'http://www.seudominio.com.br/'); // Link do Projeto
define('LIBS', '_libs/'); 
define('SESSIONNAME', 'MVCPHP'); // Nome da Session

// Configurações envio de Mail
define('MAIL_NOME', 'MVC-PHP');
define('MAIL_REMETENTE', 'contato@seudominio.com.br');

// Configurações do reCAPTCHA google - Gere as chaves no site: www.google.com/recaptcha/
define('SITE_KEY', 'SITE-KEY'); 
define('SITE_THEME', 'light');
define('SECRET_KEY', 'SECRET-KEY');
define('RESPONSE', 'g-recaptcha-response');

// Conexão com o Banco de Dados
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'nome_do_banco');
define('DB_USER', 'login');
define('DB_PASS', 'senha');

// HASH para outras funções no sistema
define('HASH_GENERAL_KEY', 'MVC-PHP'); // Gera um HASH unico do sistema

// HASH para criptografar senhas de cadastros no Banco de Dados
define('HASH_PASSWORD_KEY', 'MVC-PHP-DIFERENTE'); // Gera um HASH unico do sistema para senhas
