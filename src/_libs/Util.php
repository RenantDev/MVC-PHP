<?php
/**
 *  @author Renant Bernabé <contato@renant.com.br>
 */
class Util {

    public static function slug($string, $transform_space = '-') {
        $table = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ú' => 'u', 'û' => 'u',
            'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c', 'ÿ' => 'y', 'Ŕ' => 'R',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'ý' => 'y', 'ý' => 'y',
            'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'ï' => 'i', 'ð' => 'o',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'ñ' => 'n', 'ò' => 'o',
            'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'ó' => 'o', 'ê' => 'e',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'í' => 'i', 'î' => 'i',
            'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'ë' => 'e', 'ì' => 'i',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'ù' => 'u', 'þ' => 'b',
            'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ŕ' => 'r');
        // Traduz os caracteres em $string, baseado no vetor $table
        $tra_string = strtr($string, $table);
        // converte para minúsculo
        $con_string = strtolower($tra_string);
        // remove caracteres indesejáveis (que não estão no padrão)
        $rem_string = preg_replace("/[^a-z0-9_\s-]/", "", $con_string);
        // Remove múltiplas ocorrências de hífens ou espaços
        $reMul_string = preg_replace("/[\s-]+/", " ", $rem_string);
        $esp_string = trim($reMul_string);
        // Transforma espaços e underscores em hífens
        $result_string = preg_replace("/[\s_]/", $transform_space, $esp_string);
        // retorna a string
        return $result_string;
    }

    public static function enviaEmail($para, $titulo, $conteudo) {

        $message = '<html><head><title>' . $titulo . '</title></head><body>'
                . '<table style="margin: 0 auto; border: 1px solid #1AB394;" width="600" cellpadding="0" cellspacing="0"><tr><td></td>'
                . '<td width="600"><div class="content">'
                . '<table class="main" width="100%" cellpadding="0" cellspacing="0"><tr>'
                . '<td style="padding: 15px; background-color: #1AB394; color: white;"><h2 style="padding: 0; margin: 0;">'
                . MAIL_NOME . ' - ' . $titulo
                . '</h2></td></tr><tr><td style="padding: 15px;">'
                . $conteudo
                . '</td></tr></table>'
                . '<div class="footer">'
                . '<table width="100%">'
                . '<tr><td style="text-align: center; border-top: 3px solid #1AB394; padding: 5px;"><a style="color: #1ab394; text-decoration: none;" href="' . URL . '">' . MAIL_NOME . '</a><br><b>Não responda este email.</b></td></tr>'
                . '</table></div></div>'
                . '</td><td></td></tr></table></body></html>';

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <' . MAIL_REMETENTE . '>' . "\r\n";

        mail($para, $titulo, $message, $headers);
    }

}
