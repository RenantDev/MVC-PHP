<?php
/**
 *  @author Renant Bernabé  
 */
class Val 
{
    public function __construct()
    {
        
    }
    
    public function minlength($data, $arg)
    {
        if (strlen($data) < $arg) {
            return "Este campo deve conter no minimo $arg digitos.<br><br>";
        }
    }
    
    public function maxlength($data, $arg)
    {
        if (strlen($data) > $arg) {
            return "Este campo deve conter no maximo $arg digitos.<br><br>";
        }
    }
    
    public function entreDig($data, $arg1, $arg2) {
        if (strlen($data) < $arg1 or strlen($data) > $arg2) {
            return "Este campo deve conter entre $arg1 e $arg2 digitos.<br><br>";
        }
    }
    
    public function email($data)
    {
        if (!strpos($data,'@') && !strpos($data,'.')) {
            return "Informe um e-mail válido.<br><br>";
        }
    }
    
    public function required($data)
    {
        if (empty($data)) {
            return "Este campo é obrigatório.<br><br>";
        }
    }
    
    public function digit($data)
    {
        if (ctype_digit($data) == false) {
            return "Este campo é obrigatório.<br><br>";
        }
    }
    
    public function __call($name, $arguments) 
    {
        throw new Exception("A function ( $name ) não existe dentro da class: " . __CLASS__);
    }
    
}