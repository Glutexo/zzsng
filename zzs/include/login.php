<?php
class Login {
	private $db;

	public function __construct() {
		$this->db = new Db;
	}

	/**
	 * Ensures that there is always a user signed in. If no user is signed in,
	 * the login credentials are checked. If no login token is provided, the
	 * default (demo) user is signed in automatically.
	 * Called whenever a page loads from the Application class constructor.
	 *
	 */
	public function ensure() {
		$active_user = null;

		if(!empty($_REQUEST['login_token'])) {
			$this->login_with_token($_REQUEST['login_token']);
		}

		if(empty($_SESSION[master_config::APPLICATION]['active_user'])) {
			$this->login_demo();
		}
	}

	private function login_with_id($id) {
		if(!$id) {
			throw new Exception(lang::user_id_not_provided);
		}

		$id = $this->db->select('users',$id,'id')->fetch_single_field();
		if(!$id) {
			throw new Exception(lang::invalid_user_id);
		}

		if(empty($_SESSION[master_config::APPLICATION])) {
			$_SESSION[master_config::APPLICATION] = array();
		}
		$_SESSION[master_config::APPLICATION]['active_user'] = intval($id);

	}

	private function login_with_token($token) {
		$cond = $this->db->escape_column("token");
		$cond .= " = ";
		$cond .= "'".$this->db->escape($token)."'";

		$id = $this->db->select_where('users', $cond, 'id')->fetch_single_field();
		if($id) {
			$this->login_with_id($id);
		}
	}

	/**
	 * Logs in the demo user. Called whenever a page loads unless a user is
	 * already signed in. The demo user ID is picked from the configuration.
	 *
	 */
	private function login_demo() {
		$this->login_with_id(config::DEMO_USER_ID);
	}

	public function get_active_user_id() {
		if(empty($_SESSION[master_config::APPLICATION]) || empty($_SESSION[master_config::APPLICATION]['active_user'])) {
			return null;
		}
		return $_SESSION[master_config::APPLICATION]['active_user'];
	}

	/**
	 * Returns a login (user name) of the active user. Null if there is no
	 * active user, but that should never happen.
	 *
	 */
	public function get_active_user_login() {
		if(empty($_SESSION[master_config::APPLICATION]) || empty($_SESSION[master_config::APPLICATION]['active_user'])) {
			return null;
		}

		$login = $this->db->select('users',$_SESSION[master_config::APPLICATION]['active_user'],array('login'))->fetch_single_field();
		return $login;
	}
}
?>