<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	  public function __construct()
        {		parent::__construct();
				// Load form helper library
				$this->load->helper('form');
				$this->load->helper('security');	
				// Load form validation library
				$this->load->library('form_validation');

				// Load session library
				$this->load->library('session');
               $this->load->helper('url_helper');
			   $this->load->model('user_database');
        }  
	public function index()
	{
		
		$this->load->view('login_page');
	}
	public function login()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			if(isset($this->session->userdata['logged_in'])){
				redirect('/sources');
			}else{
				$this->load->view('login_page');
			}
		} else {
			
			$data = array(
			'username' => $this->input->post('email'),
			'password' => $this->input->post('password')
			);
			$result = $this->user_database->login($data);
			if ($result == TRUE) {
				$username = $this->input->post('email');
				/*$result = $this->user->read_user_information($username);
				if ($result != false) {
					$session_data = array(
					'username' => $result[0]->user_name,
					'email' => $result[0]->user_email,
					);
					// Add user data in session
					$this->session->set_userdata('logged_in', $session_data);
					$this->load->view('admin_page');
				}*/
				$session_data = array(
					'username' => $username
				);
				$this->session->set_userdata('logged_in', $session_data);
				redirect('/sources');
			} else {
				$data = array(
				'error_message' => 'Invalid Username or Password'
				);
				$this->load->view('login_page', $data);
			}
		}
	}	
	public function logout() {

	// Removing session data
	$sess_array = array(
	'username' => ''
	);
	$this->session->unset_userdata('logged_in', $sess_array);
	$this->load->view('login_page');
	}

	
}
