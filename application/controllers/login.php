<?php

class login extends CI_Controller {

    function index() {
        if( $this->session->userdata('isLoggedIn') ) {
            redirect('/main/show_main');
        } else {
            $this->show_login(false);
        }
    }

    function login_user() {
        //Cria a instancia do modelo model
        $this->load->model('user_m');

        // nickname e password via POST
        $nickname = $this->input->post('nickname');
        $password  = $this->input->post('password');
		$nickname = mysql_real_escape_string($nickname);
		$password = mysql_real_escape_string($password);
        //Verifica que o utilizador existe 
        if( $nickname && $password && $this->user_m->validate_user($nickname,$password)) {
            // Se é valido entra
            redirect('/main/show_main');
        } else {
            // Se não mostra mensagem de erro na página principal
            $this->show_login(true);
        }
    }
	
	function criar_conta() {
        //Cria a instancia do modelo model
        $this->load->model('user_m');

        // nickname, password e email via POST
        $nickname = $this->input->post('nickname');
		$email = $this->input->post('email');
        $password  = $this->input->post('password');
		$nickname = mysql_real_escape_string($nickname);
		$password = mysql_real_escape_string($password);
		$email = mysql_real_escape_string($email);
        if( $nickname && $password && 
			(strlen($nickname) > 10
			|| strlen($nickname) < 3 || strlen($password) > 10
				|| strlen($password) < 3) || $this->user_m->check_user($nickname,$password)) {
            // Já existe um utilizador com este email ou nickname ou dados incorrectos
			$data['newusersuccess'] = 2 ;
			$this->load->helper('form');
			$this->load->view('login', $data);
        } else {
            // Se não cria user
			// upload da foto default
			$img = base_url()."assets/img/avatars/foto_default.jpg";
			$defaultImage = file_get_contents($img);
		   $userdata = array ("nickname" => $nickname, "password" => sha1($password), "email" => $email, "foto" =>$defaultImage);
			if ( $this->user_m->create_new_user($userdata) == 1){
			$data['newusersuccess'] = 1;
			$this->load->helper('form');
			$this->load->view('login', $data);
			}else{
			$data['newusersuccess'] = 0;
			$this->load->helper('form');
			$this->load->view('login', $data);
			}
        }
    }

    function show_login( $show_error = false ) {
        $data['error'] = $show_error;

        $this->load->helper('form');
        $this->load->view('login',$data);
    }

    function logout_user() {
      $this->session->sess_destroy();
      $this->index();
    }

    function showphpinfo() {
        echo phpinfo();
    }


}
