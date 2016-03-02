<?php

class main extends CI_Controller{
	
	public function __construct()
	{
		parent::__construct();
		
		if( !$this->session->userdata('isLoggedIn') ) {
			redirect('');
		}
	}
	
	function show_main() {
		if (($this->input->get('add'))!= "" && is_numeric($this->input->get('add'))){
			$getId = $this->input->get('add');
			$this->add_friend ($getId);
		} 
		if (($this->input->get('del'))!= "" && is_numeric($this->input->get('del'))){
			$getId = $this->input->get('del');
			$this->remove_friend ($getId);
		} 
		if (($this->input->get('accreq'))!= "" && is_numeric($this->input->get('accreq'))){
			$getId = $this->input->get('accreq');
			$this->accept_request ($getId);
		} 
		if (($this->input->get('delreq'))!= "" && is_numeric($this->input->get('delreq'))){
			$getId = $this->input->get('delreq');
			$this->reject_request ($getId);
		} 
		
		$this->load->model('post_m');
		
		// Informação guardada na sessão
		$user_id = $this->session->userdata('id');
		$user_username = $this->session->userdata('username');
		
		// Faz load de todos os posts e dos seus amigos
		$posts = $this->post_m->get_all_posts( $user_id, 5 );
		
		// Se existem posts, guarda-os para posteriormente os enviar para a vista
		if ($posts) {
			$data['posts'] = $posts;
		}
		
		$other_users_posts = $this->post_m->get_all_other_posts( $user_id );
		if( $other_users_posts ) {
			$data['other_posts'] = $other_users_posts;
		}
		
		$data['max_posts'] = $posts ? count($posts) : 0;
		$data['post_count'] = $this->post_m->get_post_count_for_user( $user_id );
		$data['nickname'] = $this->session->userdata('nickname');
		$this->load->view('main',$data);
	}
	
	function posts() {
		$this->load->model('post_m');
		$this->load->model('user_m');
		
		
		$user_id = $this->session->userdata('id');
		$user_username = $this->session->userdata('username');
		
		
		$posts = $this->post_m->get_all_posts( $user_id);
		
		if ($posts) {
			
			foreach($posts as $val) {
				echo '<p>';
				$userdata = $this->user_m->get_user($val['userId']);
				echo '<img src="data:image/jpeg;base64,'.base64_encode( $userdata['foto']).'" style="width:70px;height:70px;"/>';
				echo '<div class = "bubble" style="width:98%;">';
				echo '<strong>';
				echo $userdata['nickname'];
				echo '<div style="float:right;">';
				echo $val['createdDate'];
				echo '</div>';
				echo '</strong>';
				echo '<br>';
				echo '<br>';
				echo $val['body'];
				echo '</div>';
				echo '</p>';
			}
			
		}
		
	}
	function post_message() {
		$message = $this->input->post('message');
		
		if ( $message ) {
			$this->load->model('post_m');
			$saved = $this->post_m->save_post($message);
		}
		
		if ( isset($saved) && $saved ) {
			echo "<tr><td>". $saved['body'] ."</td><td>". $saved['createdDate'] ."</td></tr>";
		} 
		
	}
	
	function get_friends() {
		$user_id = $this->session->userdata('id');
		$this->load->model('user_m');
		$friends = $this->user_m->get_all_friends($user_id);
		
		if ($friends == false) {
			echo '<p>You have no friends</p>';
		}else{
			foreach($friends as $val) {
				echo '<p>';
				echo '<img src="data:image/jpeg;base64,'.base64_encode( $val['foto']).'" style="width:70px;height:70px;float:left;"/>';
				echo '<div>';
				echo '<br>';
				echo '<strong>'.$val['nickname'].'</strong>';
				if ( $this->user_m->isonline_user($val['secondFriend'])){
					echo '<div style="float:right;"><img src="/assets/img/online.png" style="width:13px; height:13px;"/><strong>Online</strong></div>';
				}else{
					echo '<div style="float:right;"><img src="/assets/img/offline.png" style="width:13px; height:13px;"/>Offline</div>';
				}
				echo '</div>';
				echo '</p>';
				echo '<br>';
				
				echo '<hr/>';
			}
			
		}
		
	}
	
	function search_friend() {
		$user_id = $this->session->userdata('id');
		$this->load->model('user_m');
		$nickname = $this->input->post('nickname');
		$friends = $this->user_m->search_friends($user_id,$nickname);
		
		if ($friends == false) {
			echo '<p>No results</p>';
		}else{
			foreach($friends as $val) {
				echo '<p>';
				echo '<img src="data:image/jpeg;base64,'.base64_encode( $val['foto']).'" style="width:70px;height:70px;float:left;"/>';
				echo '<div>';
				echo '<br>';
				echo $val['nickname'];
				if ( $this->user_m->isonline_user($val['secondFriend'])){
					echo '<div style="float:right;"><img src="/assets/img/online.png" style="width:13px; height:13px;"/><strong>Online</strong></div>';
				}else{
					echo '<div style="float:right;"><img src="/assets/img/offline.png" style="width:13px; height:13px;"/>Offline</div>';
				}
				echo '</div>';
				echo '</p>';
				echo '<br>';
				
				echo '<hr/>';
			}
			
		}
		
	}
	function search_user() {
		$user_id = $this->session->userdata('id');
		$this->load->model('user_m');
		$nickname = $this->input->post('nickname');
		$users = $this->user_m->search_user($user_id,$nickname);
		
		if ($users == false) {
			echo '<p>No results</p>';
		}else{
			foreach($users as $val) {
				echo '<p>';
				echo '<img src="data:image/jpeg;base64,'.base64_encode( $val['foto']).'" style="width:70px;height:70px;float:left;"/>';
				echo '<div>';
				echo '<br>';
				echo '<br>';
				echo $val['nickname'];
				$friends = $this->user_m->search_friends($user_id,$val['nickname']);
				if ($friends) {
					echo '<div style="float:right;"><img src="/assets/img/rmv.ico" style="width:20px; height:20px;margin-left:60px;"/><strong ><a href="?del='.$friends[0]['id'].'"  style="white-space: nowrap;text-decoration:none;color:green;color:red;" >Remover amigo</a></strong></div>';
				}else{
					echo '<div style="float:right;"><img src="/assets/img/addfriend.png" style="width:20px; height:20px;margin-left:60px;"/><strong style="color:green;"><a href="?add='.$val['id'].'" style="white-space: nowrap;color:green;" >Adicionar amigo</a></strong></div>';
				}
				echo '</div>';
				echo '</p>';
				echo '<br>';
				
				echo '<hr/>';
			}
			
		}
	}
	function friend_requests() {
		$user_id = $this->session->userdata('id');
		$this->load->model('user_m');
		$requests = $this->user_m->get_requests($user_id);
		
		if ($requests == false) {
			return false;
		}else{
			
			foreach($requests as $val) {
				echo'
					<script>
					$(document).ready(function() {
					$("#reject").click(function() {
					window.location = "?delreq='.$val['first_friend'].'";
					});
					$("#accept").click(function() {
					window.location = "?accreq='.$val['first_friend'].'";
					});
					});
					</script>
					';
				echo '<div style="text-align:right;">';
				echo '<img src="data:image/jpeg;base64,'.base64_encode( $val['foto']).'" style="width:22px;height:22px;"/>';
				echo '<strong>'.$val['nickname'].'</strong> Quer ser seu amigo | ';
				echo '<img id="accept" src="'.  base_url() .'assets/img/accept.png" style="width:30px;height:30px;margin-right:10px;"/>';
				echo '<img id="reject" src="'.  base_url() .'assets/img/reject.png" style="width:30px;height:30px;margin-right:10px;"/>';
				
				echo '</div>';
				echo '<br>';
				
				echo '<hr/>';
			}
			
		}
		
	}
	function add_friend($friend_id) {
		$user_id = $this->session->userdata('id');
		$this->load->model('user_m');
		if ($this->user_m->add_friend($user_id,$friend_id)){
			redirect(base_url());
		}else{
			//aqui colocar pg 404
			redirect(base_url());
		}
		
	}
	function remove_friend($friend_id) {
		$user_id = $this->session->userdata('id');
		$this->load->model('user_m');
		if ($this->user_m->remove_friend($user_id,$friend_id)){
			redirect(base_url());
		}else{
			//aqui colocar pg 404
			redirect(base_url());
		}
		
	}
	function reject_request($rejectId) {
		$user_id = $this->session->userdata('id');
		$this->load->model('user_m');
		if($this->user_m->reject_request($user_id,$rejectId)){
			redirect(base_url());
		}else{
			//aqui colocar pg 404
			redirect(base_url());
		}
		
	}
	function accept_request($acceptId) {
		$user_id = $this->session->userdata('id');
		$this->load->model('user_m');
		if($this->user_m->accept_request($user_id,$acceptId)){
			redirect(base_url());
		}else{
			//aqui colocar pg 404
			redirect(base_url());
		}
		
	}
}
