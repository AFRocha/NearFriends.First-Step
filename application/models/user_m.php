<?php


class user_m extends CI_Model {
	
	
	function validate_user( $nickname, $password ) {
		// Construir query
		$this->db->from('clients');
		$this->db->where('nickname',$nickname );
		$this->db->where( 'password', sha1($password ));
		$login = $this->db->get()->result();
		
		// Os resultados da query são armazenados em $login
		// Se é um array com apenas um valor, então o user existe e valida
		
		if ( is_array($login) && count($login) == 1 ) {
			// Call set_session to set the user's session vars via CodeIgniter
			$this->set_session($login[0]);
			//buscar o hash no cookie corresponde ao id da sessao
			$session_id = $this->session->userdata('session_id');
			//Fazer update do id do user nas sessoes em BD
			$this->db->where('session_id', $session_id);
			$userid = $login[0]->id;
			$this->db->update('ci_sessions',array('userId' => $userid));
			return true;
		}
		return false;
	}
	
	function check_user( $nickname, $email ) {
		// Construir query
		$this->db->from('clients');
		$this->db->where('nickname',$nickname );
		$login = $this->db->get()->result();
		if ( is_array($login) && count($login) == 1 ) {
			return true;
		}else{
			$this->db->from('clients');
			$this->db->where('email',$email );
			$login = $this->db->get()->result();
			if ( is_array($login) && count($login) == 1 ) {
				return true;
			}else{return false;}
		}
	}
	
	function set_session($arr) {
		// session->set_userdata é uma função do CI que guarda informação
		// em CodeIgniter's session storage.
		$this->session->set_userdata( array(
		'id'=>$arr->id,
		//'name'=> $this->details->firstName . ' ' . $this->details->lastName,
		'nickname'=>$arr->nickname,
		'isLoggedIn'=>true
		)
		);
	}
	
	function  create_new_user( $userData ) {
		$data['nickname'] = $userData['nickname'];
		$data['password'] = $userData['password'];
		$data['email'] = $userData['email'];
		$data['foto'] = $userData['foto'];
		return $this->db->insert('clients',$data);
	}
	
	public function  get_user( $user_id ) {
		$this->db->from('clients');
		$this->db->where('id',$user_id );
		
		$user = $this->db->get()->result_array();
		
		if( is_array($user) && count($user) > 0 ) {
			return $user[0];
		}else{
			
			return false;
		}
	}
	
	function  get_all_friends( $user_id ) {
		$this->db->from('clients');
		$this->db->join('friends','clients.id = friends.secondFriend','INNER');
		$this->db->where('friends.firstFriend', $user_id);
		$friends = $this->db->get()->result_array();
		if( is_array($friends) && count($friends) > 0 ) {
			return $friends;
		}else{
			return false;
		}
	}
	
	function  search_friends( $user_id, $friend_nickname ) {
		$query = "SELECT clients.id, clients.nickname, clients.email, clients.foto  FROM clients INNER JOIN friends ON (clients.id = friends.secondFriend) AND (friends.firstFriend = ".$user_id.")
		WHERE clients.nickname LIKE '".$friend_nickname."%' " ;
		$friends = $this->db->query($query)->result_array();
		if( is_array($friends) && count($friends) > 0 ) {
			return $friends;
		}else{
			return false;
		}
	}
	public function isonline_user( $user_id ) {
		$this->db->from('ci_sessions');
		$this->db->where('userId', $user_id);
		$array = $this->db->get()->result_array();
		if( is_array($array) && count($array) > 0 ) {
			return true;
		}else{
			return false;
		}
	}
	
	//procura todos os users que não estão pendentes de aceitação
	function  search_user( $user_id, $nickname ) {
		$this->db->from('clients');
		$query = "SELECT  *
		FROM    clients
		WHERE   (id NOT IN (SELECT second_friend FROM friend_request WHERE first_friend = ".$user_id."))
		AND  (id NOT IN (SELECT first_friend FROM friend_request WHERE second_friend = ".$user_id."))
		AND clients.nickname LIKE '".$nickname."%' " ;
		$friends = $this->db->query($query)->result_array();
		if( is_array($friends) && count($friends) > 0 ) {
			return $friends;
		}else{
			return false;
		}
	}
	
	function get_requests( $user_id ) {
		$query = "SELECT * FROM clients INNER JOIN friend_request ON friend_request.second_friend = ".$user_id."
		AND clients.id = friend_request.first_friend";
		$requests = $this->db->query($query)->result_array();
		if( is_array($requests) && count($requests) > 0 ) {
			return $requests;
		}else{
			return false;
		}
	}
	
	public function add_friend( $user_id, $friend_id ) {
		$data = array(
		'first_friend' => $user_id ,
		'second_friend' => $friend_id
		);
		$result = $this->db->insert('friend_request', $data);
		return $result;
	}
	public function remove_friend( $user_id, $friend_id ) {
		$result = $this->db->delete('friends', '(firstFriend = '.$user_id.' AND secondFriend = '.$friend_id.' )OR
		(firstFriend = '.$friend_id.' AND secondFriend = '.$user_id.')');
		return $result;
	}
	public function update_tagline( $user_id, $tagline ) {
		$data = array('tagline'=>$tagline);
		$result = $this->db->update('user', $data, array('id'=>$user_id));
		return $result;
	}
	function accept_request( $user_id, $acceptId ) {
		$data = array(
		'first_friend' => $acceptId ,
		'second_friend' => $user_id
		);
		$result = $this->db->delete('friend_request', $data);
		$data = array(
		'firstFriend' => $user_id ,
		'secondFriend' => $acceptId
		);
		$result = $this->db->insert('friends', $data);
		$data = array(
		'firstFriend' => $acceptId ,
		'secondFriend' => $user_id
		);
		$result = $this->db->insert('friends', $data);
		if( is_array($result) && count($result) > 0 ) {
			return true;
		}else{
			return false;
		}
	}
	
	function reject_request( $user_id, $rejectId ) {
		$result = $this->db->delete('friend_request', 'first_friend = '.$rejectId.' AND second_friend = '.$user_id.'');
		if( is_array($result) && count($result) > 0 ) {
			return true;
		}else{
			return false;
		}
	}
}
