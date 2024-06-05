<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Device
 *
 * This is  user interaction methods you could use
 * for get supervisor login 
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Sivaprasad, Neork Technologies
 * @link		 
 */

 

class Userexport extends CI_Controller {

    public function __construct() {
        Parent::__construct();
      
        $this->load->helper('form');
        $this->load->model('User_model');
        $this->load->model('Shift_model');
        
    }
    
    public function index()
    {
        $file = './Employees_Nov_28.xlsx';
         
        //load the excel library
        $this->load->library('excel');
         
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
         
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
         //print_r('Userexport');
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
         
            //The header will/should be in row 1 only. of course, this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }
         print "<pre>";
         //print_r($arr_data);exit();
        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data; 
        $i=0;
        $count=0;
        foreach ($arr_data as $key => $value) {  
        //print $i.'<br>';
        $start = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($value['W'])); 
        $dob= date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($value['F'])); 
        // if($value['I']!='')
        // {

        //    $end = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP());
        // } 
        // else
        // {

        //   $end = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($value['I']));
        //   //$count=$count+1;
        // }
         
       // print_r($end); print_r("<br>");exit();
        //    echo date('m/d/Y H:i:s', $value['H']); 
           if($value['G']=='Female') { $gender='F';}else{$gender='M';}
           if($value['Y']=='Hourly') { $type=2;}else{$type=1;}
     
            $jobrole=$this->User_model->findjobrole($value['U']);  
            $unit=$this->User_model->findUnitid($value['AA']);   
            $payment=$this->User_model->findPaymentid($value['Y']);
            $group=$this->User_model->findGroupid($value['Q']);  
            $defaultshift=$this->User_model->findDefaultshift($value['V']);
            // if($value['A']=='')
            // {
            //       print $value['B']."<br>";
            // }
            // else
            // {
            //     print $value['A']."<br>";
              
            // }
            $tmp['user_id'] = $value['A'];
            $tmp['firstname'] = $value['B']; 
            $tmp['lastname'] =  $value['C'];
            if($value['D']=='') 
            {
                $tmp['email'] = 'No email';
            }
            else
            {
                $tmp['email'] = $value['D'];
            } 
            $chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            $str = substr( str_shuffle( $chars ), 0, 8 );   
            $tmp['password']= md5($str);  
            $tmp['designation'] =$jobrole[0]['id'];
            $tmp['group_id'] =$group[0]['id'];
// print "<pre>";
//             print_r($jobrole[0]);
            // if($unit[0]['id']==''){

            //     print $value['AA']."<br>";
            // }
            // else
            // {
            //     print $unit[0]['id']."<br>";
            // }

            // if($group[0]['id']==''){

            //     print $value['Q']."<br>";
            // }
            // else
            // {
            //     print $group[0]['id']."<br>";
            // }
            // if($defaultshift[0]['id']==''){

            //     print $value['V']."<br>";
            // }
            // else
            // {
            //     print $defaultshift[0]['id']."<br>";
            // }
            $tmp['annual_allowance_type'] =$type;
            $tmp['gender'] =$gender;
            $tmp['default_shift']=$defaultshift[0]['id'];


            $tmp['unit'] = $unit[0]['id'];
 
            $tmp['status'] = 1;
            $tmp['address1']= $value['H'];
            $tmp['address2']= $value['I'];

   

       
            $tmp['mobile_number']= $value['E'];
            $tmp['telephone']=NULL;
            $tmp['NINnumbers']=NULL;
            $tmp['dob']= $dob;
            $tmp['country']=$value['J'];
            $tmp['city']= $value['K'];
            $tmp['postcode']=$value['L'];
            $tmp['kin_name']=$value['N'];
            $tmp['kin_phone']=$value['O'];
            $tmp['kin_telephone']=NULL; 
            $tmp['kin_address']= $value['P'];
            $tmp['kin_postcode']=NULL;
            $tmp['kin_relationship']=NULL;
            $tmp['kin_email']=NULL;
            $tmp['bank_account']=NULL;
            $tmp['bank_sortcode']=NULL;
            $tmp['jobcode']=NULL;
            $tmp['title']=NULL;
            $tmp['taxcode']=NULL;
            $tmp['payroll_id'] = $value['X'];
            if(trim($value['y'])=='1'){

                $tmp['paymenttype']=9; 
            }
            else
            {
                $tmp['paymenttype']=$payment[0]['id']; 
            } 
            $tmp['weekly_hours']=$value['R'];
            $tmp['annual_holliday_allowance']=number_format($value['S'], 2);
            $tmp['actual_holliday_allowance']=number_format($value['T'], 2); 
            $tmp['start_date']=$start;
            $tmp['final_date']=NULL;
            if($values['Z']=='')
            {
                $day_rate='0.00';
            }
            else
            {
                $day_rate=$values['Z'];
            }
            $tmp['day_rate']=$day_rate;

            if($value['AB']=='Dayoff' || $value['AB']=='DayOff') { $sunday=1;}else{$sunday=0;}
            if($value['AC']=='Dayoff' || $value['AC']=='DayOff') { $monday=1;}else{$monday=0;}
            if($value['AD']=='Dayoff' || $value['AD']=='DayOff') { $tuesday=1;}else{$tuesday=0;}
            if($value['AE']=='Dayoff' || $value['AE']=='DayOff') { $wednesday=1;}else{$wednesday=0;}
            if($value['AF']=='Dayoff' || $value['AF']=='DayOff') { $thursday=1;}else{$thursday=0;}
            if($value['AG']=='Dayoff' || $value['AG']=='DayOff') { $friday=1;}else{$friday=0;}
            if($value['AH']=='Dayoff' || $value['AH']=='DayOff') { $saturday=1;}else{$saturday=0;}

            $tmp['sunday']=$sunday;
            $tmp['monday']=$monday;
            $tmp['tuesday']=$tuesday;
            $tmp['wednesday']=$wednesday;
            $tmp['thursday']=$thursday;
            $tmp['friday']=$friday;
            $tmp['saturday']=$saturday;


            print_r($tmp['user_id'].'_'.$tmp['weekly_hours']."<br>");  

            $i++;
            //$status = $this->User_model->saveimporteddata($tmp);
             //print($status)."<br>"; 
        }
//exit();
        //print_r('count'.'-'.$count);
    }

    public function defaultshift()
    {
        $file = './StMatthewsHealthcareSMHRota_FromclientNov12.xlsx';
        
        //load the excel library
        $this->load->library('excel');
        
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        //print_r('Userexport');
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
            
            //The header will/should be in row 1 only. of course, this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }
       // print "<pre>";
        // print_r('arr_data');
        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data;
        $i=0;$ncount=0;$ccount=0;$j=0;
        $sdata = array();
        foreach ($arr_data as $key => $value) { 
            
           // $user=$this->User_model->getuserIdbyName($value['B'],$value['C']);
            $user=$this->User_model->getuserIdbyPayrolid($value['A'],7);
            
            if(count($user) > 0){
             
               // print $value['D']."<br>----";
                
             $shidfDetails=$this->Shift_model->findshiftbyName($value['D']);
              //  print_r($shidfDetails);
                if(count($shidfDetails) > 0){
                    $shiftid =  $shidfDetails[0]['id'];
                    $j++;
                    print "SHIFT #: ".$j."::".$shiftid."==".$user[0]['id']."<br>";
                   
                    $sdata = array("default_shift"=>$shiftid);
               $statusupdate = $this->User_model->updatedefaultshift($user[0]['id'],$sdata);
                    print $statusupdate."----<br>";
                }
                else{
                    print "<br>----";
                    print "User#: ".$user[0]['id']." ".$value['B'] ."<br>";
                    print '<div style="color: red;">'.$value['D'].' Shift Not found</div>';
                    print "----<br>";
                    
                    $ccount++;
                }
            }
            else{
                print "<br>----";
                //print $value['B']." ".$value['C']."<br>";
              //  print '<div style="color: red;">'.$value['B'].' '.$value['C'].' User Not found</div>';
                print '<div style="color: red;">'.$value['B'] .' User Not found</div>';
                
               print "----<br>";
               $ncount++;
            }
           
        }
        print $ncount." users not found<br>";
        print $ccount." shift not found";
    }
}
?>
