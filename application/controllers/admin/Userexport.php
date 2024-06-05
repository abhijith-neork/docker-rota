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

 

class Userexport extends CI_Controller {

    public function __construct() {
        Parent::__construct();
      
        $this->load->helper('form');
        $this->load->model('User_model');
        $this->load->model('Shift_model');
        $this->load->model('Rota_model');
        $this->load->model('Rotaschedule_model');
        $this->load->model('Unit_model');
        $this->load->model('Designation_model');
        
    }
    
    public function index()
    {
        $file = './Moreton_Centre.xlsx';
       
         
        //load the excel library
        $this->load->library('excel');
         
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
         
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        //print_r($cell_collection);exit();
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
        //print_r($value['D']);
        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data; 
        $i=0;
        $count=0;
        foreach ($arr_data as $key => $value) {  
        //print $i.'<br>';
        $start = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($value['R'])); 
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
           //if($value['Y']=='Hourly') { $type=2;}else{$type=1;}
     
            $jobrole=$this->User_model->findjobrole($value['V']);  
            $unit=$this->User_model->findUnitid($value['U']);   
            $payment=$this->User_model->findPaymentid($value['T']);
            $group=$this->User_model->findGroupid($value['N']);  
            $defaultshift=$this->User_model->findDefaultshiftbystatus($value['Q']);
            //print_r($defaultshift);
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
             //print "<pre>";
            //print_r($value['D']);
            $email=$this->User_model->finduserdetails($value['D']);
            //print_r($email);exit();
            // if(empty($email))
            // {
            //     print "No user found";
            // }
            // else
            // {
            //     print "User found";

            // }
            // if($value['D']=='') 
            // {
            //     $tmp['email'] = 'No email';
            // }
            // else
            // {
                $tmp['email'] = $value['D'];
            // } 
            $chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            $str = substr( str_shuffle( $chars ), 0, 8 );   
            $tmp['password']= md5($str);  
            $tmp['designation'] =$jobrole[0]['id'];
            $tmp['group_id'] =$group[0]['id'];
            $tmp['payment_type'] =$payment[0]['id'];
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
            $tmp['annual_allowance_type'] =2;
            $tmp['gender'] =$gender;
            $tmp['default_shift']=$defaultshift[0]['id'];


            $tmp['unit'] = 2;
 
            $tmp['status'] = 1;
            $tmp['address1']= $value['I'];
            $tmp['address2']= $value['J'];

   

       
            $tmp['mobile_number']= $value['E'];
            $tmp['telephone']=NULL;
            $tmp['NINnumbers']=NULL;
            $tmp['dob']= $dob;
            $tmp['country']=$value['K'];
            $tmp['city']= $value['L'];
            $tmp['postcode']=$value['M'];
            $tmp['kin_name']=NULL;
            $tmp['kin_phone']=NULL;
            $tmp['kin_telephone']=NULL; 
            $tmp['kin_address']= NULL;
            $tmp['kin_postcode']=NULL;
            $tmp['kin_relationship']=NULL;
            $tmp['kin_email']=NULL;
            $tmp['bank_account']=NULL;
            $tmp['bank_sortcode']=NULL;
            $tmp['jobcode']=NULL;
            $tmp['title']=NULL;
            $tmp['taxcode']=NULL;
            $tmp['payroll_id'] = $value['S'];
            $tmp['Ethnicity'] = $value['H'];
            // if(trim($value['T'])=='1'){

            //     $tmp['paymenttype']=9; 
            // }
            // else
            // {
            //     $tmp['paymenttype']=$payment[0]['id']; 
            // } 
            $tmp['weekly_hours']=$value['O'];
           // date("h:i:sa")
            /* $tt= date('h:i', PHPExcel_Shared_Date::ExcelToPHP($value['R'])); 
 print $value['R'];*/
//$cell_value = PHPExcel_Style_NumberFormat::toFormattedString($value['R'], 'HH:MM');
//print $val = date('Y-m-d', PHPExcel_Shared_DateTime::ExcelToPHP($value['R']));

            $tmp['annual_holliday_allowance']=number_format($value['P'], 2);
            $tmp['actual_holliday_allowance']=number_format($value['P'], 2); 
            $tmp['start_date']=$start;
            $tmp['final_date']=NULL;
            // print($values['Z']);
            // if($values['Z']=='')
            // {
            //     $day_rate='0.00';
            // }
            // else
            // {
            //     $day_rate=$values['Z'];
            // }
            $tmp['day_rate']='0.00';

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
            $tmp['user_id'] = $value['A']; 
            print "<pre>";
            print_r($tmp);
           

            // print_r($email[0]['id']);
           
            // if($email[0]['id']!='')
            // {
            //    print "Found <br>";
            //    $status=$this->User_model->updatestatus1($tmp['user_id']); print_r("update status=".$status."<br>");
            //    $this->User_model->updateimporteddata($tmp);
            // }
            // else
            // {
            //     print_r("not found");
            //     $this->User_model->saveimporteddata($tmp);

            // }
            $stat=$this->User_model->checkStat($tmp['user_id']);
            print_r($stat); 
            if($stat==1)
            {
                 print "Found <br>";
                $status=$this->User_model->updatestatus1($tmp['user_id']); print_r("update status=".$status."<br>");
                $this->User_model->updateimporteddata($tmp);
            }
            else
            {
                 print_r("not found");
                if($tmp['email']!='')
                $this->User_model->saveimporteddata($tmp);
            }

          //  print_r($value['R']." == ".$tmp['user_id'].'_'.$tmp['weekly_hours']."<br>");  
 
           //$status = $this->User_model->saveimporteddata($tmp);
           //print($status)."<br>"; 
           
           // print($status)."<br>"; 
        }  
exit();
         
    }

        public function Hawthorne_House()
    {
        $file = './Hawthorne_House.xlsx';
       
         
        //load the excel library
        $this->load->library('excel');
         
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
         
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        //print_r($cell_collection);exit();
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
        //print_r($value['D']);
        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data; 
        $i=0;
        $count=0;
        foreach ($arr_data as $key => $value) {  
        //print $i.'<br>';
        $start = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($value['R'])); 
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
           //if($value['Y']=='Hourly') { $type=2;}else{$type=1;}
     
            $jobrole=$this->User_model->findjobrole($value['V']);  
            $unit=$this->User_model->findUnitid($value['U']);   
            $payment=$this->User_model->findPaymentid($value['T']);
            $group=$this->User_model->findGroupid($value['N']);  
            $defaultshift=$this->User_model->findDefaultshiftbystatus($value['Q']);
            //print_r($defaultshift);
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
             //print "<pre>";
            //print_r($value['D']);
            $email=$this->User_model->finduserdetails($value['D']);
            //print_r($email);exit();
            // if(empty($email))
            // {
            //     print "No user found";
            // }
            // else
            // {
            //     print "User found";

            // }
            // if($value['D']=='') 
            // {
            //     $tmp['email'] = 'No email';
            // }
            // else
            // {
                $tmp['email'] = $value['D'];
            // } 
            $chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            $str = substr( str_shuffle( $chars ), 0, 8 );   
            $tmp['password']= md5($str);  
            $tmp['designation'] =$jobrole[0]['id'];
            $tmp['group_id'] =$group[0]['id'];
            $tmp['payment_type'] =$payment[0]['id'];
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
            $tmp['annual_allowance_type'] =2;
            $tmp['gender'] =$gender;
            $tmp['default_shift']=$defaultshift[0]['id'];


            $tmp['unit'] = $unit[0]['id'];
 
            $tmp['status'] = 1;
            $tmp['address1']= $value['I'];
            $tmp['address2']= $value['J'];

   

       
            $tmp['mobile_number']= $value['E'];
            $tmp['telephone']=NULL;
            $tmp['NINnumbers']=NULL;
            $tmp['dob']= $dob;
            $tmp['country']=$value['K'];
            $tmp['city']= $value['L'];
            $tmp['postcode']=$value['M'];
            $tmp['kin_name']=NULL;
            $tmp['kin_phone']=NULL;
            $tmp['kin_telephone']=NULL; 
            $tmp['kin_address']= NULL;
            $tmp['kin_postcode']=NULL;
            $tmp['kin_relationship']=NULL;
            $tmp['kin_email']=NULL;
            $tmp['bank_account']=NULL;
            $tmp['bank_sortcode']=NULL;
            $tmp['jobcode']=NULL;
            $tmp['title']=NULL;
            $tmp['taxcode']=NULL;
            $tmp['payroll_id'] = $value['S'];
            $tmp['Ethnicity'] = $value['H'];
            // if(trim($value['T'])=='1'){

            //     $tmp['paymenttype']=9; 
            // }
            // else
            // {
            //     $tmp['paymenttype']=$payment[0]['id']; 
            // } 
            $tmp['weekly_hours']=$value['O'];
           // date("h:i:sa")
            /* $tt= date('h:i', PHPExcel_Shared_Date::ExcelToPHP($value['R'])); 
 print $value['R'];*/
//$cell_value = PHPExcel_Style_NumberFormat::toFormattedString($value['R'], 'HH:MM');
//print $val = date('Y-m-d', PHPExcel_Shared_DateTime::ExcelToPHP($value['R']));

            $tmp['annual_holliday_allowance']=number_format($value['P'], 2);
            $tmp['actual_holliday_allowance']=number_format($value['P'], 2); 
            $tmp['start_date']=$start;
            $tmp['final_date']=NULL;
            // print($values['Z']);
            // if($values['Z']=='')
            // {
            //     $day_rate='0.00';
            // }
            // else
            // {
            //     $day_rate=$values['Z'];
            // }
            $tmp['day_rate']='0.00';

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
            $tmp['user_id'] = $value['A']; 
            print "<pre>";
            print_r($tmp);
           

            // print_r($email[0]['id']);
           
            // if($email[0]['id']!='')
            // {
            //    print "Found <br>";
            //    $status=$this->User_model->updatestatus1($tmp['user_id']); print_r("update status=".$status."<br>");
            //    $this->User_model->updateimporteddata($tmp);
            // }
            // else
            // {
            //     print_r("not found");
            //     $this->User_model->saveimporteddata($tmp);

            // }
            $stat=$this->User_model->checkStat($tmp['user_id']);
            print_r($stat); 
            if($stat==1)
            {
                 print "Found <br>";
                $status=$this->User_model->updatestatus1($tmp['user_id']); print_r("update status=".$status."<br>");
                $this->User_model->updateimporteddata($tmp);
            }
            else
            {
                 print_r("not found");
                if($tmp['email']!='')
                $this->User_model->saveimporteddata($tmp);
            }

          //  print_r($value['R']." == ".$tmp['user_id'].'_'.$tmp['weekly_hours']."<br>");  
 
           //$status = $this->User_model->saveimporteddata($tmp);
           //print($status)."<br>"; 
           
           // print($status)."<br>"; 
        }  
exit();
         
    }

            public function MapleLeafHouse()
    {
        $file = './Maple_Leaf_House.xlsx';
       
         
        //load the excel library
        $this->load->library('excel');
         
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
         
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        //print_r($cell_collection);exit();
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
        //print_r($value['D']);
        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data; 
        $i=0;
        $count=0;
        foreach ($arr_data as $key => $value) {  
        //print $i.'<br>';
        $start = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($value['R'])); 
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
           //if($value['Y']=='Hourly') { $type=2;}else{$type=1;}
     
            $jobrole=$this->User_model->findjobrole($value['V']);  
            $unit=$this->User_model->findUnitid($value['U']);   
            $payment=$this->User_model->findPaymentid($value['T']);
            $group=$this->User_model->findGroupid($value['N']);  
            $defaultshift=$this->User_model->findDefaultshiftbystatus($value['Q']);
            //print_r($defaultshift);
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
             //print "<pre>";
            //print_r($value['D']);
            $email=$this->User_model->finduserdetails($value['D']);
            //print_r($email);exit();
            // if(empty($email))
            // {
            //     print "No user found";
            // }
            // else
            // {
            //     print "User found";

            // }
            // if($value['D']=='') 
            // {
            //     $tmp['email'] = 'No email';
            // }
            // else
            // {
                $tmp['email'] = $value['D'];
            // } 
            $chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            $str = substr( str_shuffle( $chars ), 0, 8 );   
            $tmp['password']= md5($str);  
            $tmp['designation'] =$jobrole[0]['id'];
            $tmp['group_id'] =$group[0]['id'];
            $tmp['payment_type'] =$payment[0]['id'];
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
            $tmp['annual_allowance_type'] =2;
            $tmp['gender'] =$gender;
            $tmp['default_shift']=$defaultshift[0]['id'];


            $tmp['unit'] = $unit[0]['id'];
 
            $tmp['status'] = 1;
            $tmp['address1']= $value['I'];
            $tmp['address2']= $value['J'];

   

       
            $tmp['mobile_number']= $value['E'];
            $tmp['telephone']=NULL;
            $tmp['NINnumbers']=NULL;
            $tmp['dob']= $dob;
            $tmp['country']=$value['K'];
            $tmp['city']= $value['L'];
            $tmp['postcode']=$value['M'];
            $tmp['kin_name']=NULL;
            $tmp['kin_phone']=NULL;
            $tmp['kin_telephone']=NULL; 
            $tmp['kin_address']= NULL;
            $tmp['kin_postcode']=NULL;
            $tmp['kin_relationship']=NULL;
            $tmp['kin_email']=NULL;
            $tmp['bank_account']=NULL;
            $tmp['bank_sortcode']=NULL;
            $tmp['jobcode']=NULL;
            $tmp['title']=NULL;
            $tmp['taxcode']=NULL;
            $tmp['payroll_id'] = $value['S'];
            $tmp['Ethnicity'] = $value['H'];
            // if(trim($value['T'])=='1'){

            //     $tmp['paymenttype']=9; 
            // }
            // else
            // {
            //     $tmp['paymenttype']=$payment[0]['id']; 
            // } 
            $tmp['weekly_hours']=$value['O'];
           // date("h:i:sa")
            /* $tt= date('h:i', PHPExcel_Shared_Date::ExcelToPHP($value['R'])); 
 print $value['R'];*/
//$cell_value = PHPExcel_Style_NumberFormat::toFormattedString($value['R'], 'HH:MM');
//print $val = date('Y-m-d', PHPExcel_Shared_DateTime::ExcelToPHP($value['R']));

            $tmp['annual_holliday_allowance']=number_format($value['P'], 2);
            $tmp['actual_holliday_allowance']=number_format($value['P'], 2); 
            $tmp['start_date']=$start;
            $tmp['final_date']=NULL;
            // print($values['Z']);
            // if($values['Z']=='')
            // {
            //     $day_rate='0.00';
            // }
            // else
            // {
            //     $day_rate=$values['Z'];
            // }
            $tmp['day_rate']='0.00';

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
            $tmp['user_id'] = $value['A']; 
            print "<pre>";
            print_r($tmp);
           

            // print_r($email[0]['id']);
           
            // if($email[0]['id']!='')
            // {
            //    print "Found <br>";
            //    $status=$this->User_model->updatestatus1($tmp['user_id']); print_r("update status=".$status."<br>");
            //    $this->User_model->updateimporteddata($tmp);
            // }
            // else
            // {
            //     print_r("not found");
            //     $this->User_model->saveimporteddata($tmp);

            // }
            $stat=$this->User_model->checkStat($tmp['user_id']);
            print_r($stat); 
            if($stat==1)
            {
                 print "Found <br>";
                $status=$this->User_model->updatestatus1($tmp['user_id']); print_r("update status=".$status."<br>");
                $this->User_model->updateimporteddata($tmp);
            }
            else
            {
                 print_r("not found");
                if($tmp['email']!='')
                $this->User_model->saveimporteddata($tmp);
            }

          //  print_r($value['R']." == ".$tmp['user_id'].'_'.$tmp['weekly_hours']."<br>");  
 
           //$status = $this->User_model->saveimporteddata($tmp);
           //print($status)."<br>"; 
           
           // print($status)."<br>"; 
        }  
exit();
         
    }

    public function defaultshift()
    {
        $file = './FebruaryRota_Broomhill.xlsx';
        
        //load the excel library
        $this->load->library('excel');
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
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
        print_r('arr_data');
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
              // $statusupdate = $this->User_model->updatedefaultshift($user[0]['id'],$sdata);
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
    public function insertSettings(){
        $rota_settings_id = $this->insertRotaSettings();
    }
    public function exportRota(){
        $file = './MarchRotaKingsthorpeGrange.xlsx';
        $rota_settings_id = 197;
        $date_array = array();
        array_push($date_array, array('start_date' => '2020-03-01','end_date'=>'2020-03-07'));
        array_push($date_array, array('start_date' => '2020-03-08','end_date'=>'2020-03-14'));
        array_push($date_array, array('start_date' => '2020-03-15','end_date'=>'2020-03-21'));
        array_push($date_array, array('start_date' => '2020-03-22','end_date'=>'2020-03-28'));
        array_push($date_array, array('start_date' => '2020-03-29','end_date'=>'2020-04-04'));

        //print_r($date_array);exit();


        // January Rota - Broomhill.xlsx
        //array_push($date_array, array('start_date' => '2020-01-29','end_date'=>'2020-01-04'));
        $count = count($date_array);
        $user_count = 0;
        $rota_ids = $this->insertRota($rota_settings_id,$date_array);
        // exit();
        //load the excel library
        $this->load->library('excel');

        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);

        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $user_count = 0;
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
         //print_r('Userexport');
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
            if($column == "A"){
                $user_count++;
            }
            //exit();
            //The header will/should be in row 1 only. of course, this can be modified to suit your need.
            if ($row == 1) {
                // $header[$row][$column] = $data_value;
            } else {                
                $header[$row][$column] = $data_value;
            }
        }
        $first_week_data = array();
        $second_week_data = array();
        $third_week_data = array();
        $fourth_week_data = array();
        $fifth_week_data = array();
        $first_week_date_array = $this->getDatesFromRange($date_array[0]['start_date'],$date_array[0]['end_date']);
        $second_week_date_array = $this->getDatesFromRange($date_array[1]['start_date'],$date_array[1]['end_date']);
        $third_week_date_array = $this->getDatesFromRange($date_array[2]['start_date'],$date_array[2]['end_date']);
        $fourth_week_date_array = $this->getDatesFromRange($date_array[3]['start_date'],$date_array[3]['end_date']);
        $fifth_week_date_array = $this->getDatesFromRange($date_array[4]['start_date'],$date_array[4]['end_date']);
        $first_week = ['C','D','E','F','G','H','I'];
        $second_week = ['J','K','L','M','N','O','P'];
        $third_week = ['Q','R','S','T','U','V','W'];
        $fourth_week = ['X','Y','Z','AA','AB','AC','AD'];
        $fifth_week = ['AE','AF','AG','AH','AI','AJ','AK'];
        foreach ($header as $head) {
            array_push($first_week_data, array(
                'user_id' => $head['B'],
                'shift_name_0' => $head[$first_week[0]],
                'shift_name_1' => $head[$first_week[1]],
                'shift_name_2' => $head[$first_week[2]],
                'shift_name_3' => $head[$first_week[3]],
                'shift_name_4' => $head[$first_week[4]],
                'shift_name_5' => $head[$first_week[5]],
                'shift_name_6' => $head[$first_week[6]]
            ));
            array_push($second_week_data, array(
                'user_id' => $head['B'],
                'shift_name_0' => $head[$second_week[0]],
                'shift_name_1' => $head[$second_week[1]],
                'shift_name_2' => $head[$second_week[2]],
                'shift_name_3' => $head[$second_week[3]],
                'shift_name_4' => $head[$second_week[4]],
                'shift_name_5' => $head[$second_week[5]],
                'shift_name_6' => $head[$second_week[6]]
            ));
            array_push($third_week_data, array(
                'user_id' => $head['B'],
                'shift_name_0' => $head[$third_week[0]],
                'shift_name_1' => $head[$third_week[1]],
                'shift_name_2' => $head[$third_week[2]],
                'shift_name_3' => $head[$third_week[3]],
                'shift_name_4' => $head[$third_week[4]],
                'shift_name_5' => $head[$third_week[5]],
                'shift_name_6' => $head[$third_week[6]]
            ));
            array_push($fourth_week_data, array(
                'user_id' => $head['B'],
                'shift_name_0' => $head[$fourth_week[0]],
                'shift_name_1' => $head[$fourth_week[1]],
                'shift_name_2' => $head[$fourth_week[2]],
                'shift_name_3' => $head[$fourth_week[3]],
                'shift_name_4' => $head[$fourth_week[4]],
                'shift_name_5' => $head[$fourth_week[5]],
                'shift_name_6' => $head[$fourth_week[6]]
            ));
            array_push($fifth_week_data, array(
                'user_id' => $head['B'],
                'shift_name_0' => $head[$fifth_week[0]],
                'shift_name_1' => $head[$fifth_week[1]],
                'shift_name_2' => $head[$fifth_week[2]],
                'shift_name_3' => $head[$fifth_week[3]],
                'shift_name_4' => $head[$fifth_week[4]],
                'shift_name_5' => $head[$fifth_week[5]],
                'shift_name_6' => $head[$fifth_week[6]]
            ));
        }
        for ($i = 0; $i < count($first_week_data); $i++)
        {
            $user_id = $first_week_data[$i]['user_id'];
            $user_details=$this->User_model->finduseremail($user_id);
            if(count($user_details)==0)
            {
                print "user not found-".$user_id;
            }
            unset($first_week_data[$i]['user_id']);
          $this -> saveDataToRotaSchedule($user_id,$first_week_data[$i],$rota_ids[0],$first_week_date_array);
        }
        for ($i = 0; $i < count($second_week_data); $i++)
        {
            $user_id = $second_week_data[$i]['user_id'];
            if(count($user_details)==0)
            {
                print "user not found-".$user_id;
            }
            $user_details=$this->User_model->finduseremail($user_id);
            unset($second_week_data[$i]['user_id']);
            $this -> saveDataToRotaSchedule($user_id,$second_week_data[$i],$rota_ids[1],$second_week_date_array);
        }
        for ($i = 0; $i < count($third_week_data); $i++)
        {
            $user_id = $third_week_data[$i]['user_id'];
            if(count($user_details)==0)
            {
                print "user not found-".$user_id;
            }
            $user_details=$this->User_model->finduseremail($user_id);
            unset($third_week_data[$i]['user_id']);
            $this -> saveDataToRotaSchedule($user_id,$third_week_data[$i],$rota_ids[0],$third_week_date_array);
        }
        for ($i = 0; $i < count($fourth_week_data); $i++)
        {
            $user_id = $fourth_week_data[$i]['user_id'];
            if(count($user_details)==0)
            {
                print "user not found-".$user_id;
            }
            $user_details=$this->User_model->finduseremail($user_id);
            unset($fourth_week_data[$i]['user_id']);
            $this -> saveDataToRotaSchedule($user_id,$fourth_week_data[$i],$rota_ids[1],$fourth_week_date_array);
        }
        for ($i = 0; $i < count($fifth_week_data); $i++)
        {
            $user_id = $fifth_week_data[$i]['user_id'];
            if(count($user_details)==0)
            {
                print "user not found-".$user_id;
            }
            $user_details=$this->User_model->finduseremail($user_id);
            unset($fifth_week_data[$i]['user_id']);
            $this -> saveDataToRotaSchedule($user_id,$fifth_week_data[$i],$rota_ids[4],$fifth_week_date_array);
        }
    }

    public function saveDataToRotaSchedule($user_id,$details,$rota_id,$first_week_date_array){
        $unit_id = $this->getUnitIdOfUser($user_id);
        $designation_id= $this->getDesignationIdOfUser($user_id);
        $weekdaynames = ["Su","Mo","Tu","We","Th","Fr","Sa"];
        for ($i = 0; $i < 7; $i++)
        {
            if($user_id){
                $shift_details = $this->getShiftDetails(trim($details['shift_name_'.$i]));
                $shift_data = array(
                    'user_id'=>$user_id,
                    'shift_id'=>$shift_details[0]['id'],
                    'unit_id'=>$unit_id,
                    'from_unit'=>null,
                    'shift_hours'=>$shift_details[0]['targeted_hours'],
                    'status'=>1,
                    'rota_id'=>$rota_id,
                    'date'=>$first_week_date_array[$i],
                    'creation_date'=>date('Y-m-d H:i:s'),
                    'created_userid'=>1,
                    'updation_date'=>date('Y-m-d H:i:s'),
                    'day'=>$weekdaynames[$i],
                    'designation_id'=>$designation_id,
                    'shift_category'=>$shift_details[0]['shift_category']
                );
                // print "<pre>";
                // print_r($shift_data);
                $save_details = $this->Rotaschedule_model->addShiftDetails($shift_data);
                unset($shift_data);
            }            
        }
        //exit();
    }
    public function insertRotaSettings(){
        $response = array();
        $response['day_shift_min'] = 0;
        $response['day_shift_max'] = 0;
        $response['night_shift_min'] = 0;
        $response['night_shift_max'] = 0;
        $response['num_patients'] = 0;
        $response['designation'] = 0;
        $response['nurse_day_count'] = 0;
        $response['nurse_night_count'] = 0;
        $response['creation_date'] = date('Y-m-d H:i:s');
        $rota_settings_id = $this->Rota_model->InsertRotaSettings($response);
        return $rota_settings_id;
    }
    public function insertRota($rota_settings_id,$date_array){
        $count = count($date_array);
        $rota_ids = array();
        for ($i = 0; $i < $count; $i++)
        {
            $date_value = strtotime($date_array[$i]["end_date"]);
            $year = date('Y',$date_value);
            $month_no = date('m',$date_value);
            $rota_data    = array(
                'rota_settings'=> $rota_settings_id,
                'start_date' => $date_array[$i]["start_date"],
                'end_date' => $date_array[$i]["end_date"],
                'unit_id' => 6,
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s'),
                'created_user_id' => 1,
                'published' => 0,
                'month' => $month_no,
                'year'  => $year
            );
            $result = $this->Rota_model->addRotaDetails($rota_data);
            array_push($rota_ids, $result['rota_id']);
        }
        return $rota_ids;
    }
    public function getShiftDetails($shift_shortcut){
        $shift_details = $this->Shift_model->findshiftbyShiftShortcut($shift_shortcut);
        return $shift_details;
    }
    function getDatesFromRange($start, $end, $format = 'Y-m-d') {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach($period as $date) { 
            $array[] = $date->format($format); 
        }

        return $array;
    }
    function getUnitIdOfUser($user_id){
        $result = $this->Unit_model->findunitidofuser($user_id);
        return $result[0]['unit_id'];
    }
    function getDesignationIdOfUser($user_id){
        $result = $this->Designation_model->finddesignationidofuser($user_id);
        return $result[0]['designation_id'];
    }
    function updateUnitId(){
        $user_ids = $this->Rotaschedule_model->getUserIdsFromRotSchedule();
        for ($i = 0; $i <count($user_ids) ; $i++){
            $unit_id = $this->getUnitIdOfUser($user_ids[$i]['user_id']);
            if($unit_id){
                $status = $this->Rotaschedule_model->updateUnitIdOfUser($user_ids[$i]['user_id'],$unit_id);
            }
        }
    }

     public function exportWorkschedule()
     {
        $file = './WorkingSchedule.xlsx';
        
        //load the excel library
        $this->load->library('excel');
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
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
       //print_r(count($arr_data));
        for ($i = 2; $i <=count($arr_data)+1 ; $i++){ 
             $user_id=$arr_data[$i]['A'];
            if($arr_data[$i]['B']=='Dayoff') { $sunday=1;}else{$sunday=0;}
            if($arr_data[$i]['C']=='Dayoff') { $monday=1;}else{$monday=0;}
            if($arr_data[$i]['D']=='Dayoff') { $tuesday=1;}else{$tuesday=0;}
            if($arr_data[$i]['E']=='Dayoff') { $wednesday=1;}else{$wednesday=0;}
            if($arr_data[$i]['F']=='Dayoff') { $thursday=1;}else{$thursday=0;}
            if($arr_data[$i]['G']=='Dayoff') { $friday=1;}else{$friday=0;}
            if($arr_data[$i]['H']=='Dayoff') { $saturday=1;}else{$saturday=0;}

            $params['sunday']=$sunday;
            $params['monday']=$monday;
            $params['tuesday']=$tuesday;
            $params['wednesday']=$wednesday;
            $params['thursday']=$thursday;
            $params['friday']=$friday;
            $params['saturday']=$saturday;
            $params['user_id'] = $user_id;
            $status=$this->User_model->updateworkschedule($params);
            print "<pre>";
            print_r($status);
        }
        exit();


     }

     public function export_user_rates()
     {
        $file = './PayRates.xlsx';
        
        //load the excel library
        $this->load->library('excel');
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        //print_r('Userexport'); exit();
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
       //print_r($arr_data); exit();
        foreach ($arr_data as $key => $value) {  
             $user_id=$value['A'];
             $payrate=$value['B'];
             print_r($user_id); print '<br>';
             $rates=$this->User_model->getpayrateByUserId($user_id); 

             if(!empty($rates))
             { //updation
                $this->User_model->updaterates($user_id,$payrate);
             }
             else
             { //insertion
                $this->User_model->insertuserratevalues($user_id,$payrate);
             }
            //$status=$this->User_model->updateworkschedule($params);
            //print "<pre>"; print_r($user_id); 
            print_r($result); print '<br>';
            print "-------"; print '<br>';
        }
        exit();


     }

     public function exportAnnualAllowance_Coventry()
     {
        $file = './Annual_Leave_Coventry.xlsx';      
        //load the excel library
        $this->load->library('excel');
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
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
       $params =array();
       //print_r($arr_data);exit();
        for ($i = 2; $i <=count($arr_data)+1 ; $i++){ 
             
            $params['user_id']=$arr_data[$i]['A']; 
            
            $params['weekly_hours']=$arr_data[$i]['C'];
            $params['annual_holliday_allowance']=$arr_data[$i]['E'];

            //print_r($params);

            $status=$this->User_model->update_user_working_hours($params); 

            print_r("<prev>");
            print_r($status); print '<br>';
        }
        exit();
     }

     public function exportAnnualAllowance_Broomhill()
     {
        $file = './Annual_Leave_Northants.xlsx';      
        //load the excel library
        $this->load->library('excel');
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
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
       $params =array();
       //print_r($arr_data);exit();
        for ($i = 2; $i <=count($arr_data)+1 ; $i++){ 
             
            $params['user_id']=$arr_data[$i]['A']; 
            
            $params['weekly_hours']=$arr_data[$i]['C'];
            $params['annual_holliday_allowance']=$arr_data[$i]['E'];

            //print_r($params);

            $status=$this->User_model->update_user_working_hours($params); 

            print_r("<prev>");
            print_r($status); print '<br>';
        }
        exit();
     }
}
?>