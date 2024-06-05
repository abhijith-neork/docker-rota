<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Auth
{

	/**
	 * An array of errors generated.
	 *
	 * @access public
	 *
	 * @var array
	 */
	public $errors = array();

	/**
	 * Stores the logged in value after the first test to improve performance.
	 *
	 * @access private
	 *
	 * @var NULL
	 */
	private $logged_in = NULL;

	/**
	 * Stores the ip_address of the current user for performance reasons.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $ip_address;

	/**
	 * Stores permissions by role so we don't have to scour the database more than once.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $perms = array();

	/**
	 * A pointer to the CodeIgniter instance.
	 *
	 * @access private
	 *
	 * @var object
	 */
	private $ci;
	
	private $header;

	//--------------------------------------------------------------------

	/**
	 * Grabs a pointer to the CI instance, gets the user's IP address,
	 * and attempts to automatically log in the user.
	 *
	 * @return void
	 */
	public function __construct(){
		$this->ci =& get_instance();

		$this->ip_address = $this->ci->input->ip_address();

		log_message('debug', 'Auth class initialized.');
		$this->ci->load->model('User_model');
		$this->ci->load->model('Session_model');
		$this->ci->load->helper('user');

		$this->ci->load->library('session');
		$user_id=getId();  
		$css_new=$this->ci->User_model->getvalueforcss($user_id);
		if($css_new=='')
		{
          $_SESSION['css_value'] = 2;
		}
		else
		{
          $_SESSION['css_value'] = $css_new;
		}
	
	}//end __construct()

	/**
	 * 
	 * user login
	 */
	public function login($params = array()){
		$userInfo = array();
		$session_id ='';
		if(!empty($params['email']) && !empty($params['password'])){
			$params['personal_details'] = 1;
			$params['status'] = 1;
			$userInfo = $this->ci->User_model->getUserInfo($params); //print_r($userInfo);exit();
			if(count($userInfo)> 0){



				$ipad=$this->get_client_ip();

				$datas=array('user_id'=>$userInfo[0]['id'],'IPaddress'=>$ipad,'creation_date'=>date('Y-m-d H:i:s')

				); 

				$this->ci->User_model->insertlogindata($datas); // insert login details

				// set session
			    $rmString="1";
			    $rmString = $this->ci->User_model->updatelastlogin($userInfo[0]['id']);
			/* 	if($this->ci->input->cookie('session_stm',TRUE))
                    delete_cookie('session_isq', 'localhost', '/');
                $cookie = array(
                        'name'   => 'session_stm',
                        'value'  => $rmString,                            
                        'expire' => 86400,                
                        'path' => '/'
                        );
                $this->ci->input->set_cookie($cookie); */
		 	      
			 
				// $this->ci->Session_model->update_userid( $userInfo[0]['id'],$session_id );
				$this->setSession($userInfo[0]);

				//echo $this->ci->db->last_query();
				//exit;
				return true;
			}
		}
		// retrun default invalid login
		return false;
	}
	
	function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
	
	/**
	*
	* protected function to set session
	* @param (array) $userInfo
	*/
	protected function setSession($userInfo = array()){
		// store this to session
		//print_r($userInfo);exit();
		if($userInfo['id']==1)
		{
			$user = array(
					'email'       => $userInfo['email'],
					'user_id'     => $userInfo['id'],
					'user_type'   => $userInfo['group_id'],
					'islogin'     => true,
		    		'full_name' => $userInfo['fname']." ".$userInfo['lname'],
		    		'subunit_access' => 1
		);

		}
		else
		{
			$user = array(
					'email'  => $userInfo['email'],
					'user_id'     => $userInfo['id'],
					'user_type'     => $userInfo['group_id'],
					'islogin'     => true,
				    'full_name' => $userInfo['fname']." ".$userInfo['lname'],
				    'unit_id'=>$userInfo['unit_id'],
				    'subunit_access' => $userInfo['subunit_access']
		);

		}
		
		 //print_r($user);exit();
		// store customer dumpster data to session
		$this->ci->session->set_userdata($user);
	 //print_r($this->ci->session->userdata());exit;
		return true;
	}
	/**
	 * 
	 * signup
	 * @param unknown_type $params
	 */
	public function signup($params = array())
	{
		$user_id = EMAIL_EXISTS; // considered as status
		$userInfo = array();
		if(!empty($params['email']))
		{
			$userInfo = $this->ci->User_model->getUserInfo(array('email'=>$params['email'],'status'=>0));
			
			// email already exists
			if(count($userInfo)> 0 && $userInfo[0]['status']==1)
			{
				return $user_id;
			}
			else if(count($userInfo)> 0 && $userInfo[0]['status']==2)
			{
				$params['id'] = $userInfo[0]['id'];
				unset($userInfo);
				$user_id = $params['id'];
				if($this->ci->User_model->updateAccount($params))
				{
					$userInfo = $this->ci->User_model->getUserInfo(array('id'=>$user_id));
					if(count($userInfo)> 0){
						$this->setSession($userInfo[0]);
					}
				}
			}
			else
			{
				unset($userInfo);
				$userInfo = $this->ci->User_model->getUserInfo(array('temp_email'=>$params['email'],'status'=>0));
				if(count($userInfo)> 0)
				{
					$res['user_id'] = EMAIL_EXISTS_IN_TEMP;
					$res['user_info'] = $userInfo;
					return $res;
				}
				else
				{
					


					unset($userInfo);
					// signp user
					$user_id = $this->ci->User_model->save($params);
					$userInfo = $this->ci->User_model->getUserInfo(array('id'=>$user_id));
					if(count($userInfo)> 0){
						$this->setSession($userInfo[0]);
					}
				}
			}
		}
		// default
		return $user_id;
	}
	
	
	/**
	*
	* save personal details
	*/
	public function savedetails($params = array()){
		$this->ci->load->model('personaldetails_model');
		$this->ci->personaldetails_model->update($params);
		return true;
	}
	/**
	 * 
	 * logout
	 */
	public function logout(){
		$this->ci->session->sess_destroy();
		redirect('adminlogin');
	}
	//--------------------------------------------------------------------


	//--------------------------------------------------------------------

	/**
	 * Checks the session for the required info, then verifies against the database.
	 *
	 * @access public
	 *
	 * @return bool|NULL
	 */
	public function is_logged_in(){

		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}
		
		if($this->ci->session->userdata('islogin')){
			$params['id'] = $this->ci->session->userdata('user_id');
			$params['email'] = $this->ci->session->userdata('email');
			$userInfo = $this->ci->User_model->getUserInfo($params);
			 //print_r($userInfo);exit;
			if(count($userInfo) > 0 && $userInfo[0]['email'] == $this->ci->session->userdata('email')){
				// echo $this->ci->input->cookie('session_isq',TRUE);exit;
				if($this->ci->input->cookie('session_isq',TRUE) == $userInfo[0]['user_session'])
					return TRUE;
				else
					return FALSE;
			}else
				return FALSE;
		}
		else{
			// die("gggggg");
			return FALSE;
		}

	}//end is_logged_in()

	//--------------------------------------------------------------------

	/**
	 * Checks that a user is logged in (and, optionally of the correct role)
	 * and, if not, send them to the login screen.
	 *
	 * If no permission is checked, will simply verify that the user is logged in.
	 * If a permission is passed in to the first parameter, will check the user's role
	 * and verify that role has the appropriate permission.
	 *
	 * @access public
	 *
	 * @param string $permission (Optional) A string representing the permission to check for.
	 * @param string $uri        (Optional) A string representing an URI to redirect, if FALSE
	 *
	 * @return bool TRUE if the user has the appropriate access permissions. Redirect to the previous page if the user doesn't have permissions. Redirect '/login' page if the user is not logged in.
	 */
	public function restrict($permission=NULL){
		// If user isn't logged in, don't need to check permissions
		if ($this->is_logged_in() === FALSE)
		{
			redirect('adminlogin');
		}

		// Check to see if the user has the proper permissions
		if (!empty($permission) && !$this->has_permission($permission,$this->ci->session->userdata('user_type')))
		{
			// no permission 
			redirect('admin/user/error');
		}
		return TRUE;

	}//end restrict()


	public function restrictbutton($permission=NULL){
		 

		// Check to see if the user has the proper permissions
		if (!empty($permission) && !$this->has_permission($permission,$this->ci->session->userdata('user_type')))
		{
			// no permission 
			return false; 
		}else{
			return true; 
		}
		return false;

	}//end restrict()

	/**
	 * 
	 * return (booleagn) true/false
	 * @param unknown_type $permission
	 */
	public function isallowed($permission=NULL){
	
		// Check to see if the user has the proper permissions
		if (!empty($permission) && !$this->has_permission($permission)){
			return FALSE;
		}
		return TRUE;
	
	}//end restrict()

	//--------------------------------------------------------------------



	//--------------------------------------------------------------------
	// !UTILITY METHODS
	//--------------------------------------------------------------------

	/**
	 * Retrieves the user_id from the current session.
	 *
	 * @access public
	 *
	 * @return int
	 */
	public function user_id()
	{
		return (int) $this->ci->session->userdata('user_id');

	}//end user_id()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------

	/**
	 * Retrieves the role_id from the current session.
	 *
	 * @return int The user's role_id.
	 */
	public function role_id()
	{
		return (int) $this->ci->session->userdata('user_type');

	}//end role_id()

	//--------------------------------------------------------------------

	/**
	 * Verifies that the user is logged in and has the appropriate access permissions.
	 *
	 * @access public
	 *
	 * @param string $permission A string with the permission to check for, ie 'Site.Signin.Allow'
	 * @param int    $role_id    The id of the role to check the permission against. If role_id is not passed into the method, then it assumes it to be the current user's role_id.
	 * @param bool   $override   Whether or not access is granted if this permission doesn't exist in the database
	 *
	 * @return bool TRUE/FALSE
	 */
	public function has_permission($permission = NULL, $role_id=NULL, $override = FALSE)
	{ 
		if (empty($permission))
		{  
			return FALSE;
		}
		// move permission to lowercase for easier checking.
		else
		{    
			$permission = strtolower($permission); 
		}

		// If no role is being provided, assume it's for the current
		// logged in user.
		if (empty($role_id))
		{
			$role_id = $this->role_id();
		}

		if (empty($this->perms)) 
		{
			$this->load_permissions($role_id);
		}
		$perms = (object)$this->perms; 

		// Did we pass?
		if ((isset($perms->$permission) && $perms->$permission == 1) || (!in_array($permission, $this->perm_desc) && $override)){
			return TRUE;
		}

		return FALSE;

	}//end has_permission()

	//--------------------------------------------------------------------

	/**
	 * Checks to see whether a permission is in the system or not.
	 *
	 * @access public
	 *
	 * @param string $permission The name of the permission to check for. NOT case sensitive.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function permission_exists($permission=NULL)
	{
		if (empty($permission))
		{
			return FALSE;
		}
		// move permission to lowercase for easier checking.
		else
		{
			$permission = strtolower($permission);
		}

		if (!isset($this->all_perms)) {
			if (!class_exists('Permissions_model'))
			{
				$this->ci->load->model('Permission_model');
				$this->ci->load->model('Role_Permission_model');
			}

			$perms = $this->ci->permission_model->find_all();

			$this->all_perms = array();

			foreach ($perms as $perm)
			{
				$this->all_perms[] = strtolower($perm->name);
			}
		}

		 return in_array($permission, $this->all_perms);

	}//end permission_exists()

	//--------------------------------------------------------------------


	/**
	 * Load the permission details from the database into class properties
	 *
	 * @access public
	 *
	 * @param int $role_id An INT with the role id to grab permissions for.
	 *
	 * @return void
	 */
	public function load_permissions($role_id=NULL)
	{
		if (!class_exists('Permissions_model'))
		{
			$this->ci->load->model('Permission_model');
			$this->ci->load->model('Role_Permission_model');
		}
		$perms = array();
		$perms_all = $this->ci->Permission_model->getPermission($role_id);
		 //print_r($perms_all);exit();
		
		foreach($perms_all as $perm_details){
			$perms[$perm_details['id']] = $perm_details['name']; 
		}
        
		$this->perm_desc = $perms;

		
		$role_id = !is_null($role_id) ? $role_id : $this->role_id(); 

		$role_perms = $this->ci->Role_Permission_model->findRole($role_id);

		if (is_array($role_perms)){
			foreach($role_perms as $permission){
				$this->perms[strtolower($perms[$permission['permission_id']])] = 1;
			}
		}

	}//end load_permissions()

	//--------------------------------------------------------------------


	/**
	 * Retrieves the role_name for the request role.
	 *
	 * @access public
	 *
	 * @param int $role_id An int representing the role_id.
	 *
	 * @return string A string with the name of the matched role.
	 */
	public function role_name_by_id($role_id=0)
	{
		if (empty($role_id) || !is_numeric($role_id))
		{
			return '';
		}

		$roles = array();

		// If we already stored the role names, use those...
		if (isset($this->role_names))
		{
			$roles = $this->role_names;
		}
		else
		{
			if (! class_exists('Role_model'))
			{
				$this->ci->load->model('roles/role_model');
			}
			$results = $this->ci->role_model->select('role_id, role_name')->find_all();

			foreach ($results as $role)
			{
				$roles[$role->role_id] = $role->role_name;
			}

			unset($results);
		}

		// Try to return the role name
		if (isset($roles[$role_id]))
		{
			return $roles[$role_id];
		}

		return '';

	}//end role_name_by_id()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !LOGIN ATTEMPTS
	//--------------------------------------------------------------------

	/**
	 * Records a login attempt into the database.
	 *
	 * @access protected
	 *
	 * @param string $login The login id used (typically email or username)
	 *
	 * @return void
	 */
	protected function increase_login_attempts($login=NULL)
	{
		if (empty($this->ip_address) || empty($login))
		{
			return;
		}

		$this->ci->db->insert('login_attempts', array('ip_address' => $this->ip_address, 'login' => $login));

	}//end increase_login_attempts()

	//--------------------------------------------------------------------

	/**
	 * Clears all login attempts for this user, as well as cleans out old logins.
	 *
	 * @access protected
	 *
	 * @param string $login   The login credentials (typically email)
	 * @param int    $expires The time (in seconds) that attempts older than will be deleted
	 *
	 * @return void
	 */
	protected function clear_login_attempts($login=NULL, $expires = 86400)
	{
		if (empty($this->ip_address) || empty($login))
		{
			return;
		}

		$this->ci->db->where(array('ip_address' => $this->ip_address, 'login' => $login));

		// Purge obsolete login attempts
		$this->ci->db->or_where('UNIX_TIMESTAMP(time) <', time() - $expires);

		$this->ci->db->delete('login_attempts');

	}//end clear_login_attempts()


	//--------------------------------------------------------------------

	//--------------------------------------------------------------------

	/**
	 * Deletes the autologin cookie for the current user.
	 *
	 * @access private
	 *
	 * @return void
	 */
	private function delete_autologin()
	{
		if ($this->ci->settings_lib->item('auth.allow_remember') == FALSE)
		{
			return;
		}

		// First things first.. grab the cookie so we know what row
		// in the user_cookies table to delete.
		if (!function_exists('delete_cookie'))
		{
			$this->ci->load->helper('cookie');
		}

		$cookie = get_cookie('autologin');
		if ($cookie)
		{
			list($user_id, $token) = explode('~', $cookie);

			// Now we can delete the cookie
			delete_cookie('autologin');

			// And clean up the database
			$this->ci->db->where('user_id', $user_id);
			$this->ci->db->where('token', $token);
			$this->ci->db->delete('user_cookies');
		}

		// Also perform a clean up of any autologins older than 2 months
		$this->ci->db->where('created_on', '< DATE_SUB(CURDATE(), INTERVAL 2 MONTH)');
		$this->ci->db->delete('user_cookies');

	}//end delete_autologin()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------


	//--------------------------------------------------------------------

}//end Auth

//--------------------------------------------------------------------

if (!function_exists('has_permission'))
{
	/**
	 * A convenient shorthand for checking user permissions.
	 *
	 * @access public
	 *
	 * @param string $permission The permission to check for, ie 'Site.Signin.Allow'
	 * @param bool   $override   Whether or not access is granted if this permission doesn't exist in the database
	 *
	 * @return bool TRUE/FALSE
	 */
	function has_permission($permission=NULL, $override = FALSE)
	{
		$ci =& get_instance();

		if (class_exists('Auth'))
		{
			return $ci->auth->has_permission($permission, NULL, $override);
		}

		return FALSE;

	}//end has_permission()
}
