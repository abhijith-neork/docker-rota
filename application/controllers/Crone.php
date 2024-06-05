<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Device
 *
 * This is  user interaction methods you could use
 * for get supervisor login 
 *
 * @package     CodeIgniter
 * @subpackage  Rest Server
 * @category    Controller
 * @author      Sivaprasad, Neork Technologies
 * @link         
 */

 

class Crone extends CI_Controller {

    public function __construct() {
        Parent::__construct();
      
        $this->load->model('Crone_model');
        
    }
    
    public function copy_to_annual_holiday_allowance()
    { 
        $user_holidays=$this->Crone_model->finduserholidays(); //print "<pre>";print_r($user_holidays);exit();
        if($user_holidays)
        {
            foreach ($user_holidays as $holiday) {
                if(date('m')=='09' && date('d')=='01')
                {  // september 1st      
                   $this->Crone_model->update_allowance($holiday['id'], $holiday['actual_holiday_allowance']);
                }
                else
                { // not september

                }      
            }

        }      
    }
}
?>