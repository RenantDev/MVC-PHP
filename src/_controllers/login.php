<?php

/**
 *  @author Renant Bernabé <contato@renant.com.br>
 */
class Login extends Controller {

    function __construct() {
        parent::__construct();
        Session::init();
    }

    function index() {
        $this->view->render('login/index', TRUE);
    }

    function logar() {
        try {
            // Recebe e valida o metodo POST 
            $this->form->post('csrf')->post('email')->post('senha');

            // Conclui o submit
            $this->form->submit();

            // Recupera as informações do submit em uma array
            $data = $this->form->fetch();

            // Faz uma verificação se o formulario é do proprio sistema
            if (Auth::check($data['csrf']) == TRUE) {
                echo $this->validaLogin($data);
            } else {
                echo 'erro_csrf';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function validaLogin($data = NULL) {
        // Criptografa senha
        $data['senha'] = Hash::create('sha256', $data['senha'], HASH_PASSWORD_KEY);
        $login = $this->model->verificaDadosLogin($data)[0];
        if ($login) {
            // Verifica se o usuário confirmou a conta
            if ($login['status'] == 0) {
                throw new Exception('Confirme sua conta para logar.');
            }
            // Define as informações de login
            $this->defineLogin($login);
            throw new Exception('sucesso');
        } else {
            throw new Exception('Não foi possivel validar seu acesso!');
        }
    }

    protected function defineLogin($data = NULL) {
        // login
        Session::set('id', $data['id']);
        Session::set('tipo', $data['tipo']);
        Session::set('loggedIn', true);
    }

    function criar() {
        try {
            // Recebe e valida o metodo POST 
            $this->form->post('csrf')->post('g-recaptcha-response')
                    ->post('nome')->val('minlength', 5)
                    ->post('email')->val('minlength', 6)
                    ->post('senha')->val('entreDig', 6, 12)
                    ->post('repsenha')->val('entreDig', 6, 12);

            // Conclui o submit
            $this->form->submit();

            // Recupera as informações do submit em uma array
            $data = $this->form->fetch();

            // Faz uma verificação se o formulario é do proprio sistema
            if (Auth::check($data['csrf']) == TRUE) {
                echo $this->verificaCadastro($data);
            } else {
                echo "erro_csrf";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    // Verifica e conclui cadastro
    protected function verificaCadastro($data = NULL) {
        // Pega apenas a informação relevante do $data
        $email = array();
        $email['email'] = $data['email'];
        // Verifica se o email existe e retorna uma mensagem;
        if ($this->model->verificaEmail($email)) {
            return 'Este email já esta cadastrado em nosso sistema.';
        } elseif ($data['senha'] != $data['repsenha']) {
            return 'Repita sua senha corretamente.';
        } elseif (!Auth::validaRecaptcha($data['g-recaptcha-response'])) {
            return 'Código de verificação invalido.';
        } else {
            // Criptografa senha
            $data['senha'] = Hash::create('sha256', $data['senha'], HASH_PASSWORD_KEY);

            // Registra no banco 
            $idConta = $this->model->criarConta($data);

            // Geratoken com HASH geral e com uniqID
            $data['token'] = Hash::create('sha256', $email['email'] . uniqid(), HASH_GENERAL_KEY);

            // Trata a array
            $tokenData = array('id_login' => $idConta, 'token' => $data['token'], 'ip' => getenv("REMOTE_ADDR"));

            // Registra validação da conta
            $this->model->registraTokenConfirma($tokenData);

            // Envia email de confirmação
            $this->confirmacaoEmail($data);

            return 'sucesso';
        }
    }

    protected function confirmacaoEmail($data) {
        $conteudo = '<h4>Clique no link abaixo para confirmar sua conta.</h4>'
                . '<a href="' . URL . 'login/confirmar/' . $data['token'] . '">' . URL . 'login/confirmar/' . $data['token'] . '</a>';

        Util::enviaEmail($data['email'], 'Confirmação de Cadastro', $conteudo);
    }

    function recuperar() {
        try {
            // Recebe e valida o metodo POST 
            $this->form->post('csrf')->post('g-recaptcha-response')
                    ->post('email')->val('minlength', 6);

            // Conclui o submit
            $this->form->submit();

            // Recupera as informações do submit em uma array
            $data = $this->form->fetch();

            // Faz uma verificação se o formulario é do proprio sistema
            if (Auth::check($data['csrf']) == TRUE) {
                echo $this->verificaEmail($data);
            } else {
                echo 'erro_csrf';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function verificaEmail($data) {
        // Pega apenas a informação relevante do $data
        $email = array();
        $email['email'] = $data['email'];

        $resultVerEmail = $this->model->verificaEmail($email);
        $data['id'] = $resultVerEmail[0]['id'];

        // Verifica se o email existe e retorna uma mensagem;
        if (!Auth::validaRecaptcha($data['g-recaptcha-response'])) {
            return 'Código de verificação invalido.';
        } elseif ($resultVerEmail) {
            // Geratoken com HASH geral e com uniqID
            $data['token'] = Hash::create('sha256', $email['email'] . uniqid(), HASH_GENERAL_KEY);

            // Define o IP que esta solicitando a redefinição de senha
            $data['ip'] = getenv("REMOTE_ADDR");

            // Registra token
            $this->model->registraToken($data);

            // Envio de email para recuperar senha
            $this->sendMail($data);
            echo 'sucesso';
        } else {
            return 'Este email não esta cadastrado em nosso sistema.';
        }
    }

    protected function sendMail($data) {
        $message = "<p>Clique no link abaixo para redefinir sua senha.</p>"
                . "<a href='" . URL . "login/redefinir/" . $data['token'] . "'>"
                . URL . "login/redefinir/" . $data['token'] . "</a>";

        Util::enviaEmail($data['email'], "Esqueci minha senha", $message);
    }

    protected function sendMailConfirma($data) {
        $to = $data['email'];
        $subject = "Confirme sua conta ";

        $message = "<p>Clique no link abaixo para confirmar sua conta.</p>
            <a href='" . URL . "login/confirmar/" . $data['token'] . "'>" . URL . "login/confirmar/" . $data['token'] . "</a>";

        Util::enviaEmail($to, $subject, $message);
    }

    function redefinesenha() {
        try {
            // Recebe e valida o metodo POST 
            $this->form->post('csrf')->post('token')->post('id')->post('senha')->val('minlength', 6);

            // Conclui o submit
            $this->form->submit();

            // Recupera as informações do submit em uma array
            $data = $this->form->fetch();

            // Faz uma verificação se o formulario é do proprio sistema
            if (Auth::check($data['csrf']) == TRUE) {
                echo $this->verificaToken($data);
            } else {
                echo 'erro_csrf';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function verificaToken($data) {
        // Verifica se o token e o ID sao verdadeiros
        if ($this->model->verificaTokenId($data)) {

            // Criptografa senha
            $data['senha'] = Hash::create('sha256', $data['senha'], HASH_PASSWORD_KEY);

            // Filtra informações da array $data
            $dataFilter = array();
            $dataFilter['id'] = $data['id'];
            $dataFilter['senha'] = $data['senha'];

            // Registra a nova senha
            $this->model->redefineSenhaRec($dataFilter);

            // Anular token
            $this->model->anulaToken($data['token']);

            return 'sucesso';
        } else {
            return 'erro_token';
        }
    }

    function redefinir($token = NULL) {
        if ($token != NULL and $this->model->verificaToken($token)) {

            // Define informações do form
            $this->view->token = $token;
            $dataID = $this->model->verificaToken($token);
            $this->view->idLogin = $dataID[0]['id_login'];

            // Chama o javascript
            $js = array('login/js/resetaSenha.js');
            $this->view->js = $this->view->renderJs($js);

            $this->view->render('login/redefinir', TRUE);
        } else {
            $this->view->redirection(URL);
        }
    }

    function confirmar($token = null) {
        if ($token != NULL) {
            // Valida token
            $valida = $this->model->verificaTokenConfirma($token);
            if ($valida) {
                $valida = $valida[0];
                // Confirma conta
                $this->model->confirmaConta($valida);
                // Efetua o login
                $this->defineLogin($valida);

                // Redireciona para o painel
                header("HTTP/1.1 301 Moved Permanently");
                header('location:' . URL . 'painel');
                die();
            } else {
                // Redireciona para tela inicial
                header("HTTP/1.1 301 Moved Permanently");
                header('location:' . URL);
                die();
            }
        }
    }

    function confirmaConta() {
        try {
            // Recebe e valida o metodo POST 
            $this->form->post('g-recaptcha-response')->post('email')->val('required')->post('csrf');

            // Conclui o submit
            $this->form->submit();

            // Recupera as informações do submit em uma array
            $data = $this->form->fetch();

            // Faz uma verificação se o formulario é do proprio sistema
            if (!Auth::check($data['csrf']) or ! Auth::validaRecaptcha($data['g-recaptcha-response'])) {
                throw new Exception('Houve um erro na autenticação de envio');
            }
            // verifica se o e-mail existe
            $result = $this->model->verificaEmail($data);
            if (!$result or $result[0]['status'] == 1) {
                throw new Exception('Este e-mail não existe ou já está confirmado.');
            }

            // Geratoken com HASH geral e com uniqID
            $data['token'] = Hash::create('sha256', $data['email'] . uniqid(), HASH_GENERAL_KEY);

            // Define o IP que esta criando o acesso
            $data['ip'] = getenv("REMOTE_ADDR");

            // Insere o id do usuário logado
            $data['id_login'] = $result[0]['id'];

            // Registra token
            $this->model->registraTokenConfirma($data);

            // Envio de email para confirmar conta
            $this->sendMailConfirma($data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function sair() {
        Session::destroy();
        $this->view->redirection(URL);
    }

}
