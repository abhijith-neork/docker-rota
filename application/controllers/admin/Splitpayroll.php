<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Splitpayroll extends CI_Controller {


    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        Parent::__construct(); 
        if ($this->session->userdata('user_type')==2)
        {
            $this->auth->logout();
            
            unset($params);
            $this->_login(INVALID_LOGIN);
        }
        $this->load->model('Split_payroll_model');
        $this->load->model('Reports_model');
        $this->load->model('User_model');
        $this->load->model('Shift_model'); 
        $this->load->model('Rotaschedule_model');
        $this->load->model('Training_model');
        $this->load->model('Rota_model');
        $this->load->helper('form');
    }

    public function transferhourreport()
    {
    $this->auth->restrict('Admin.Report.TransferHour');
    //  print_r($this->input->post()); exit();
    $header = array();
    $header['headername']=" : Transfer hours for the Period"." ".$this->input->post('start_date').' '. "to".' '.$this->input->post('end_date');
    $this->load->view('includes/home_header',$header);
    $result = array(); 
    $this->load->helper('user');
    $data=array(); 
    $params=array();
    $jobe_roles=array();
    if($this->input->post('start_date')=='')
    { 
        $data['start_date']=date('d/m/Y');  
    }
    else
    {
       $data['start_date']=$this->input->post('start_date');  
    }
    if($this->input->post('end_date')=='')
    { 
        $data['end_date']=date('d/m/Y');  
    }
    else
    {
       $data['end_date']=$this->input->post('end_date');  
    }
        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
       $data['atunit'] = $this->User_model->fetchCategoryTree();
    //print_r($this->input->post());
    $params['status']=$this->input->post('status');
    $params['jobrole']= $this->input->post("jobrole");
    if($this->input->post("jobrole"))
      $jobe_roles = $this->input->post("jobrole");
    //print_r($params);exit();
    //$params['start_date']=$this->input->post('start_date');
    if($this->input->post('start_date')=='')
    {
       $date_daily=date('d/m/y');
    }
    else
    {
      $date_daily=$this->input->post('start_date'); 
    }
    $old_date = explode('/', $date_daily); 
    $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['start_date']=$new_data;  

     if($this->input->post('end_date')=='')
    {
       $date_daily1=date('d/m/y');
    }
    else
    {
      $date_daily1=$this->input->post('end_date'); 
    }
 
    $old_date = explode('/', $date_daily1); 
    $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['end_date']=$new_data1;
    $params['unit_id']=$this->input->post('unitdata'); 
    $params['at_unit_id']=$this->input->post('at_unit'); 
    $params['user']=1; // not agency staffs

  //  print_r($params); exit();
  
  $startDate = new DateTime($new_data);
  $interval = $startDate->diff(new DateTime($new_data1));
  $weeks=ceil(($interval->y * 365 + $interval->days) / 7); 

    if($params['unit_id']=='')
    {
      $data['proll']=[];
    }
    else
    {
      if( in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1 || $this->session->userdata('user_type')==18)
      { 
        if($params['unit_id']==0)
          {
            $params['unit_id']=$this->session->userdata('unit_id');
          }
          else
          { 
            $params['unit_id']=$this->input->post('unitdata');
          } 
          if($params['status']==0)
          { 
            $all=$this->Split_payroll_model->GetPayrollactivereport($params);  
            $inactive=$this->Split_payroll_model->GetPayrollinactiveusersreport($params);
            $data['payroll']=array_merge($all,$inactive);

          }
          else
          { 
            $data['payroll'] = $this->Split_payroll_model->GetPayrollreport($params);  
          }
          
      }
      else
      {
        $params['unit_id']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
         if($params['status']==0)
          { 
            $all=$this->Split_payroll_model->GetPayrollactivereport($params);  
            $inactive=$this->Split_payroll_model->GetPayrollinactiveusersreport($params);
            $data['payroll']=array_merge($all,$inactive);

          }
          else
          { 
            $data['payroll'] = $this->Split_payroll_model->GetPayrollreport($params);  
          } 
      }
      
//exit();
    $date = strtotime($params['start_date']);
    $date = strtotime("+7 day", $date);
    $data['month']=date('m', $date); 
    $data['year']=date('Y', $date); 

    $date_range=getDatesFromRange($params['start_date'],$params['end_date']);
    $parent_unit=$this->Split_payroll_model->findparentunit($params['unit_id']);
    ///print_r('<pre>');
    //print_r($date_range);print '<br>'; 

    //print_r($parent_unit); exit();


      foreach ($data['payroll'] as $payroll) { 
        $user_info=$this->Split_payroll_model->finduserdetails($payroll['id']); //----- 3)

        if($params['at_unit_id']==0)
        {
          $at_unit=$this->Split_payroll_model->getotherunitname($payroll['id'],$params);
          if($at_unit!='')
          {
            $new_unit=array('');
            foreach ($at_unit as $value) {
               array_push($new_unit,$value['unit_name']);
            }
            $at_unit=$new_unit;
          }
          else
          {
            $at_unit='';
          }
        }
        else
        {
          $at_unit=$this->Split_payroll_model->getunitname($params['at_unit_id']);
        }

        $day_hour=$this->getDayHoursbyUserfortransfer($payroll['id'],$date_range,$params['unit_id'],$parent_unit,$params['at_unit_id']); //day details(hours includeing daysunday,daysaturday,days)--- 1)
        $night_hour=$this->getNightHoursbyUserforTransfer($payroll['id'],$date_range,$params['unit_id'],$parent_unit,$params['at_unit_id']); //nigh

        if($day_hour!='' && $night_hour=='')
        {
            $merge=array_values($day_hour);
        }
        else if($night_hour!='' && $day_hour=='')
        {
            $merge=array_values($night_hour);
        }
        else
        {
           $merge=array_values(array_unique(array_merge($day_hour,$night_hour),  SORT_REGULAR));
        }


            if($user_info[0]['payment_type']!='Agency Payment')
            {
            $proll[$payroll['id']]=array('user_details'=>$user_info,'hours'=>$merge,'dayhour'=>$day_hour,'nighthour'=>$night_hour);
            }
      } 
      $data['proll']=$proll;


  }  
// print_r('<pre>'); 
 //print_r($data['proll']); 
 //exit();
    if($weeks){ $data['weeks']=$weeks; }
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/
    $data['jobrole'] = $this->Reports_model->fetchjobrole(); 
    $data['job_roles']=json_encode($jobe_roles); //print_r($data['job_roles']);exit();
    $this->load->view('admin/reports/transfer',$data);
    $result['js_to_load'] = array('reports/transferhour.js');
    $this->load->view('includes/home_footer',$result);

    }

    public function getDayHoursbyUserfortransfer($user_id,$date_range,$unit_id,$parent,$at_unit)
{ // to find user day shift details


  //$user_id=459;
  $sunday_hour='0.00';
  $saturday_hour='0.00';
  $day_hour='0.00';

  for($i=0;$i<count($date_range);$i++){ //looping date from start to end dates

    //print_r($date_range[$i]); print '<br>';
         
          $shift_details=getShiftdetailsnewtransfer($user_id,$date_range[$i],$unit_id,$parent,$at_unit); //get user shift data using userid and date
          // print '<pre>'; print_r($shift_details);print_r('<br>');

          $start_time=$shift_details['start_time']; //set start time
          $end_time=$shift_details['end_time']; //end time
          $break=$shift_details['unpaid_break_hours']; //break hour
          $shift_name=$shift_details['shift_name']; //shift name
          $shift_category=$shift_details['shift_category'];


          $from_unit=$shift_details['at_unit']; //print_r($from_unit);exit();
           //print_r($shift_name);print '<br>';
          //print_r($user_id);print '<br>';
          //print_r($date_range[$i]); print '<br>';
          // print_r($from_unit);print '<br>';
          if($from_unit!='')
          {

              $from_unit_name=getRealUnitname($from_unit);
              if($shift_name!='Training')
              {

                if($shift_name!='Night')
                {
                       $additional_hour=$this->Split_payroll_model->getadditonalhours($user_id,$date_range[$i]);
                       //print_r($additional_hour); print '<br>';
                      if($additional_hour!=0 || $additional_hour!='')
                      { 
                         $additional=settimeformat($additional_hour);
                      }
                      else
                      {
                         $additional='0.00';
                      }
                }
                else
                {
                    $additional='0.00';
                }
              }
              else
              {
                  $additional='0.00';
              }
              

          }

          //print_r($additional); print '<br>';



         if($shift_category==1 || $shift_category==3 || $shift_category==4)
         {
            $checkin=getshiftcheckinDetails($date_range[$i],$user_id); //get user checkin datas ,avoiding 00:00:00 values
            $checkout=getDayshiftChekoutDetails($date_range[$i],$user_id);//get user checkout datas ,avoiding 00:00:00 values

         }
         else if($shift_name=='Sick')
         {
            $checkin=getshiftcheckinDetails($date_range[$i],$user_id); //get user checkin datas ,avoiding 00:00:00 values
            $checkout=getDayshiftChekoutDetails($date_range[$i],$user_id);//get user checkout datas ,avoiding 00:00:00 values

         }
         else if($shift_name=='Offday') 
         {
            $checkin=getshiftcheckinDetails($date_range[$i],$user_id); //get user checkin datas ,avoiding 00:00:00 values
            $checkout=getDayshiftChekoutDetails($date_range[$i],$user_id);//get user checkout datas ,avoiding 00:00:00 values

         }
         else
         {
            $checkin= "00:00:00";
            $checkout= "00:00:00";
         }                                                


              if($checkin==""){
                                  $checkin="00:00:00";
                              }
              if($checkout==""){
                                  $checkout="00:00:00";
                                }

              //print_r("<pre>");
              //print_r($checkin); print '<br>';
              //print_r($checkout); print '<br>';
              $cins = array();
              $couts = array();
              if(!empty($checkin))
              { //if checkin not empty
                  $j=1; 
                  foreach ($checkin as $check) {
                    $cins[] =$check['date'].' '.$check['time_from']; //adding date time form to cins array
                    $j++;
                  }

              }
              else
              { //othervice array is empty
                $cins = array(" ");
                $couts = array(" ");
              }

              //print_r($cins);print '<br>';

              if(!empty($checkout)){ //if checkout is not empty
                  $j=1; 
                  foreach ($checkout as $checks) {
                    $couts[] =$checks['date'].' '.$checks['time_to']; //adding datetime to couts
                    $j++;
                  }

              }
              else
              { //otherwice its empty
                  $cins = array(" ");
                  $couts = array(" ");
              }

              //print_r($couts);print '<br>';

              $time_n=array();
              for($c=0;$c<count($couts);$c++){
              $in_time="";
                if($couts[$c]!='' && $cins[$c]!='')
                { //if cout and cin are not empty
                    if($shift_name=='Offday')
                    {  
                        $_cin = explode(" ",$cins[$c]);
                        $_cout = explode(" ",$couts[$c]);
                        $start_time = $_cin[1];
                        $end_time = $_cout[1];
                    }

                    $hour=getPayrollExtrahournewforDayHours($cins[$c],$couts[$c],$start_time,$end_time); //finding intime using checkin and checkout values also compared with start and end values
                    //print_r($hour); print '<br>';
                    
                    $in_time=date("H:i",strtotime($hour['time'])); //print "hii-";print_r($in_time);
                    if($shift_details['targeted_hours']<$in_time)  // if intime > than shift hour,set shift hour as intime
                    {
                      $in_time=$shift_details['targeted_hours'];
                    }
                    else
                    {
                      $in_time=$in_time;
                    }
                    $new=$in_time; //print_r($new);
                    $time_n[]=$new;

                }
              }

              $in_time_sum=0;
              foreach ($time_n as $value1) { //adding intimes
                if($value1!='' || $value1!='00:00')
                {
                    $in_time_sum=$in_time_sum+settimeformat(getPayrollformat1($value1));
                }
              }
             $break=getPayrollformat1($break);
             //print($break);print '<br>';

            if($in_time_sum!='' ||$in_time_sum!=0)
            {  //adding intime and break hours
                  $sum_total=settimeformat($in_time_sum)-settimeformat($break);
                                                               //print_r($sum_total);
            }
            else
            {
                  $sum_total='00:00';
            }
            //print($sum_total); print '<br>';

            $hour_total_by_date=number_format($sum_total,2); //total value
            // print($hour_total_by_date); print_r($day_date); print '<br>';
            if($hour_total_by_date!='0.00' || $additional!='0') 
              if($from_unit!='')
                 $day_array[$i]=array('date'=>$date_range[$i],'total_by_date'=>$hour_total_by_date,'from_unit'=>$from_unit_name,'additional_hour_byday'=>$additional);


            // $nameOfDay = date('D', strtotime($date_range[$i]));
            // if($nameOfDay=='Sun')
            // { //check if its sunday,then assign to sunday hour
            //   $sunday_hour=$sunday_hour+$hour_total_by_date;
            // }
            // else if($nameOfDay=='Sat')
            // {//check if its saturday,then assign to saturday hour
            //   $saturday_hour=$saturday_hour+$hour_total_by_date;
            // }
            // else
            // { //added to day hour
            //   $day_hour=$day_hour+$hour_total_by_date;

            // } 

             $from_unit_array[$i]=array('from_unit'=>$from_unit_name);

  }

  //print_r($day_array);print '<br>';
 
  $new_from_unit=array_unique($from_unit_array,SORT_REGULAR); 

  $new_array=array('');
  
  foreach ($new_from_unit as $from) { 
   
    $hour_per_fromunit=$this->FindHourTotal($from['from_unit'],$day_array);
    $new_array[]=$hour_per_fromunit;
   
  }

  //$result=array('dayhour'=>$day_hour,'saturday_hour'=>$saturday_hour,'sunday_hour'=>$sunday_hour);
  $result=$new_array;
  //print_r($result);
  //exit();
  return $result;

}

public function getNightHoursbyUserforTransfer($user_id,$date_range,$unit_id,$parent,$at_unit)
{

  //$user_id=794;

  $weekday_hour='0.00';
  $weekend_hour='0.00'; 

  $new_array=array();

  for($i=0;$i<count($date_range);$i++){

    //print_r($date_range[$i]); print '<br>';
              $shift_details=getShiftdetailsnewtransfer($user_id,$date_range[$i],$unit_id,$parent,$at_unit);

             // $shift_details=getShiftdetailsnew($user_id,$date_range[$i]); //shift details
             //print '<pre>'; print_r($shift_details);print_r('<br>');

              $day_date=$date_range[$i]; //date
              $start_time=$shift_details['start_time']; //shift start time
              $end_time=$shift_details['end_time'];// shift end time
              $break=$shift_details['unpaid_break_hours']; //break time
              $shift_name=$shift_details['shift_name']; //shift name
              $shift_category=$shift_details['shift_category']; //shift category


              $from_unit=$shift_details['at_unit']; //print_r($from_unit);exit();
              if($from_unit!='')
              {

                $from_unit_name=getRealUnitname($from_unit);
                if($shift_name!='Training')
                {
                    if($shift_name=='Night')
                    {
                      $additional_hour=$this->Split_payroll_model->getadditonalhours($user_id,$date_range[$i]);
                      if($additional_hour!=0 || $additional_hour!='')
                      {
                         $additional=settimeformat($additional_hour);
                      }
                      else
                      {
                         $additional=$additional_hour;
                      }
                    }
                    else
                    {
                         $additional='0.00';
                    }
                }
                else
                {
                    $additional='0.00';
                }

              }


            if($shift_name=='Night' || $shift_name=='Training + Night'|| $shift_name=='Student Nurse Night')
            {
              $checkin=getNightshiftcheckinDetails($date_range[$i],$user_id); //finding checkin and checkout details
              $checkout=getNightshiftChekoutDetails($date_range[$i],$user_id);
            }
            else
            {
              $checkin= "00:00:00";
              $checkout= "00:00:00";
            }

              if($checkin==""){
                                  $checkin="00:00:00";
                              }
              if($checkout==""){
                                  $checkout="00:00:00";
                                }

              //print_r("<pre>");
             //print_r($checkin); print '<br>';
              //print_r($checkout); print '<br>';
              $cins = array();
              $couts = array();
              if(!empty($checkin))
              { //checkin not empty
                  $j=1; 
                  foreach ($checkin as $check) { //looopong it

                    if($day_date==$check['date'])
                    { //checin date equal to date
                      $cins[] =$check['date'].' '.$check['time_from']; //added to cin
                    }
                    else
                    { //if checkin in next date ,checkin check_in time greater than shift end time
                      if(settimeformat($end_time)<settimeformat($check['time_from']))
                      { //setting cin as empty
                        $cins = array(" ");
                        $cins_time[]= array(" ");
                      }
                      else
                      { //otherwise set cin value
                        $cins[] =$check['date'].' '.$check['time_from'];
                      }
                    }
                    $j++;
                  }

              }
              else
              {//if checkin empty,then set cin as empty
                $cins = array(" ");
                $couts = array(" ");
              }

              //print "cin-";print_r($cins);print '<br>';

              if(!empty($checkout)){
                  $j=1; 
                  foreach ($checkout as $checks) {
                    $couts[] =$checks['date'].' '.$checks['time_to'];
                    $j++;
                  }

              }
              else
              {
                  $cins = array(" ");
                  $couts = array(" ");
              }

             //print "cout-"; print_r($couts);print '<br>';
             //print(count($couts));
              $time_n=array();
              for($c=0;$c<count($couts);$c++){
              $in_time="";
                if($couts[$c]!='' && $cins[$c]!='')
                {    

                    $hour=getPayrollExtrahournew($cins[$c],$couts[$c],$start_time,$end_time,'',$shift_category,$day_date);
                    //print_r($hour); print '<br>';
                    
                    $in_time=date("H:i",strtotime($hour['time'])); //print "hii-";print_r($in_time);
                    if($shift_details['targeted_hours']<$in_time)  // if intime > than shift hour,set shift hour as intime
                    {
                      $in_time=$shift_details['targeted_hours'];
                    }
                    else
                    {
                      $in_time=$in_time;
                    }
                    $new=$in_time; //print_r($new);
                    $time_n[]=$new;

                } 
              }

              $in_time_sum=0;
              foreach ($time_n as $value1) {
                if($value1!='' || $value1!='00:00')
                {
                    $in_time_sum=$in_time_sum+settimeformat(getPayrollformat1($value1));
                }
              }
             $break=getPayrollformat1($break);
             //print($break);print '<br>';

            if($in_time_sum!='' ||$in_time_sum!=0)
            {  
                  $sum_total=settimeformat($in_time_sum)-settimeformat($break);
                                                               //print_r($sum_total);
            }
            else
            {
                  $sum_total='00:00';
            }
            //print($sum_total); print '<br>';

            $hour_total_by_date=number_format($sum_total,2);
             //print($hour_total_by_date); print '----';  print_r($additional); print '<br>';
//print_r($from_unit);

            if($hour_total_by_date!='0.00' || $additional!='0')  
              if($from_unit!='')
                  $night_array[$i]=array('date'=>$day_date,'total_by_date'=>$hour_total_by_date,'from_unit'=>$from_unit_name,'additional_hour_byday'=>$additional);
                  $from_unit_array[$i]=array('from_unit'=>$from_unit_name);

 }
 
  $new_from_unit=array_unique($from_unit_array,SORT_REGULAR); 

  $new_array=array('');
  
  foreach ($new_from_unit as $from) { 
   
    $hour_per_fromunit=$this->FindHourTotal($from['from_unit'],$night_array);
    $new_array[]=$hour_per_fromunit;
   
  }
 // print_r($new_array); print '<br>';
 // exit();
  //$result=array('weekdayhour'=>$weekday_hour,'weekend_hour'=>$weekend_hour);
  $result=$new_array; 
  //exit();
  return $result;

}

public function FindHourTotal($from_unit,$array)
{ 

   foreach ($array as $data) {//print_r($data); print '<br>';
     if($data['from_unit']==$from_unit)
     {
       $sum_from=$sum_from+$data['total_by_date'];
       $sum_from_additional=$sum_from_additional+$data['additional_hour_byday'];
     }
   }
   $result=array('total_by_date'=>$sum_from,'from_unit'=>$from_unit,'additional_hour_byday'=>$sum_from_additional);
   //print_r($result); print_r('<br>'); 

   return $result;
}

public function findAdditionlHoursBysunDayforTransfer($payroll,$params,$parent_unit,$at_unit)
{
      $additional_hours_sunday=$this->Split_payroll_model->findAdditionlHoursBysunDayforTransfer($payroll,$params,$parent_unit,$at_unit);
     
      // print_r($additional_hours_sunday);
      // print_r(count($additional_hours_sunday));
      // print '<br>';
      $sum='00.00.00';
      foreach ($additional_hours_sunday as $value) {
        //print_r($value['extrahour']); print '<br>';

            $hour_new=$value['extrahour']; //print 'hour_new'; print_r($hour_new); print '<br>';

                                if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }else
                                {
                                  $sum=$sum;
                                }

                                if(strpos($hour_new, "-") !== false){
                                $shour = explode(":",$hour_new);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }
                                else
                                {
                                  $hour=$hour_new;
                                }

                          // print_r('sum-'); print_r($sum); print '<br>';
                          //print_r('hour-');
                          //print_r($hour); print '<br>'; //exit();

                            //$sum = $this->time_to_decimal($sum);  print_r('sumnew-'); print_r($sum);
                            $b = $this->new_time_to_decimal($hour);  //print_r('hournew-');print_r($b); print_r('<br>');print_r('<br>');print_r('<br>');
                            $sum= $sum + $b; 

            }
            $total=$this->decimal_to_time($sum); 

            $shour = explode(".",$total);
            //print_r($shour);
            if($shour[0]<0)
            {
              $hour1=$shour[0]*-1;
              $status=1;
            }
            else
            {
              $hour1=$shour[0];
              $status=2;
            }
            //print_r($hour1);
            if($shour[1]<1)
            {
              $minute1=$shour[1]*-1;
            }
            else
            {
              $minute1=$shour[1];
            }

            //print_r($minute1);
            if($this->count_digit($hour1)==1)
            {
              $hour1='0'.$hour1;
            }
            else
            {
              $hour1=$hour1;
            } 

             if($this->count_digit($minute1)==1)
            {
              $minute1='0'.$minute1;
            }
            else
            {
              $minute1=$minute1;
            }


            $total=$hour1.'.'.$minute1;
            if($status==1)
            {
              $total='-'.$total;
            }
            else
            {
              $total=$total;
            }
           //print_r('total-');print_r($total); print_r('<br>'); exit();
            return $total;
            exit();
}

public function findAdditionlHoursBysaturDayforTransfer($payroll,$params,$parent_unit,$at_unit)
{
      $additional_hours_day=$this->Split_payroll_model->findAdditionlHoursBysaturDayforTransfer($payroll,$params,$parent_unit,$at_unit);
     
      //print_r($additional_hours_day);
      //print_r(count($additional_hours_day));
      //print '<br>';
      $sum='00.00.00';
      foreach ($additional_hours_day as $value) {
        //print_r($value['extrahour']); print '<br>';

            $hour_new=$value['extrahour']; //print 'hour_new'; print_r($hour_new); print '<br>';

                                if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }else
                                {
                                  $sum=$sum;
                                }

                                if(strpos($hour_new, "-") !== false){
                                $shour = explode(":",$hour_new);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }
                                else
                                {
                                  $hour=$hour_new;
                                }

                          // print_r('sum-'); print_r($sum); print '<br>';
                          //print_r('hour-');
                          //print_r($hour); print '<br>'; //exit();

                            //$sum = $this->time_to_decimal($sum);  print_r('sumnew-'); print_r($sum);
                            $b = $this->new_time_to_decimal($hour);  //print_r('hournew-');print_r($b); print_r('<br>');print_r('<br>');print_r('<br>');
                            $sum= $sum + $b; 

            }
            $total=$this->decimal_to_time($sum); 
            $shour = explode(".",$total);
            //print_r($shour);
            if($shour[0]<0)
            {
              $hour1=$shour[0]*-1;
              $status=1;
            }
            else
            {
              $hour1=$shour[0];
              $status=2;
            }
            //print_r($hour1);
            if($shour[1]<1)
            {
              $minute1=$shour[1]*-1;
            }
            else
            {
              $minute1=$shour[1];
            }

            //print_r($minute1);
            if($this->count_digit($hour1)==1)
            {
              $hour1='0'.$hour1;
            }
            else
            {
              $hour1=$hour1;
            } 

             if($this->count_digit($minute1)==1)
            {
              $minute1='0'.$minute1;
            }
            else
            {
              $minute1=$minute1;
            }


            $total=$hour1.'.'.$minute1;
            if($status==1)
            {
              $total='-'.$total;
            }
            else
            {
              $total=$total;
            }
            //print_r('total-');print_r($total); print_r('<br>'); exit();
            return $total;
            exit();
}


//night additional

public function findAdditionlHoursByWeekdayforTransfer($payroll,$params,$parent_unit)
{
      $additional_hours_day=$this->Split_payroll_model->findAdditionlHoursByWeekdayforTransfer($payroll,$params,$parent_unit,$at_unit);
     
      //print_r($additional_hours_day);
      //print_r(count($additional_hours_day));
      //print '<br>';
      $sum='00.00.00';
      foreach ($additional_hours_day as $value) {
        //print_r($value['extrahour']); print '<br>';

            $hour_new=$value['extrahour']; //print 'hour_new'; print_r($hour_new); print '<br>';

                                if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }else
                                {
                                  $sum=$sum;
                                }

                                if(strpos($hour_new, "-") !== false){
                                $shour = explode(":",$hour_new);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }
                                else
                                {
                                  $hour=$hour_new;
                                }

                          // print_r('sum-'); print_r($sum); print '<br>';
                          //print_r('hour-');
                          //print_r($hour); print '<br>'; //exit();

                            //$sum = $this->time_to_decimal($sum);  print_r('sumnew-'); print_r($sum);
                            $b = $this->new_time_to_decimal($hour);  //print_r('hournew-');print_r($b); print_r('<br>');print_r('<br>');print_r('<br>');
                            $sum= $sum + $b; 

            }
            $total=$this->decimal_to_time($sum);   

            //$total='02.39.00';
            $shour = explode(".",$total);
            //print '<pre>';
            //print_r($shour); print '<br>';

            if($shour[0]<0)
            {
              $hour1=$shour[0]*-1;
              $status=1; 
            }
            else
            {
              $hour1=$shour[0]; 
              $status=2;
            }
            //print_r($hour1);
            if($shour[1]<1)
            {
              $minute1=$shour[1]*-1;
            }
            else
            {
              $minute1=$shour[1];
            }

            //print_r($minute1);
            if($this->count_digit($hour1)==1)
            {
              $hour1='0'.$hour1;
            }
            else
            {
              $hour1=$hour1;
            } 

             if($this->count_digit($minute1)==1)
            {
              $minute1='0'.$minute1;
            }
            else
            {
              $minute1=$minute1;
            }


            $total=$hour1.'.'.$minute1;
            if($status==1)
            {
              $total='-'.$total;
            }
            else
            {
              $total=$total;
            }
            //print_r('total-');print_r($total); print_r('<br>'); exit();
            return $total;
            exit();
}

public function findAdditionlHoursByWeekendforTransfer($payroll,$params,$parent_unit,$at_unit)
{
      $additional_hours_day=$this->Split_payroll_model->findAdditionlHoursByWeekendforTransfer($payroll,$params,$parent_unit);
     
      //print_r($additional_hours_day);
      //print_r(count($additional_hours_day));
      //print '<br>';
      $sum='00.00.00';
      foreach ($additional_hours_day as $value) {
        //print_r($value['extrahour']); print '<br>';

            $hour_new=$value['extrahour']; //print 'hour_new'; print_r($hour_new); print '<br>';

                                if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }else
                                {
                                  $sum=$sum;
                                }

                                if(strpos($hour_new, "-") !== false){
                                $shour = explode(":",$hour_new);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }
                                else
                                {
                                  $hour=$hour_new;
                                }

                          // print_r('sum-'); print_r($sum); print '<br>';
                          //print_r('hour-');
                          //print_r($hour); print '<br>'; //exit();

                            //$sum = $this->time_to_decimal($sum);  print_r('sumnew-'); print_r($sum);
                            $b = $this->new_time_to_decimal($hour);  //print_r('hournew-');print_r($b); print_r('<br>');print_r('<br>');print_r('<br>');
                            $sum= $sum + $b; 

            }
            $total=$this->decimal_to_time($sum);   

            //$total='02.39.00';
            $shour = explode(".",$total);
            //print '<pre>';
            //print_r($shour); print '<br>';

            if($shour[0]<0)
            {
              $hour1=$shour[0]*-1;
              $status=1; 
            }
            else
            {
              $hour1=$shour[0]; 
              $status=2;
            }
            //print_r($hour1);
            if($shour[1]<1)
            {
              $minute1=$shour[1]*-1;
            }
            else
            {
              $minute1=$shour[1];
            }

            //print_r($minute1);
            if($this->count_digit($hour1)==1)
            {
              $hour1='0'.$hour1;
            }
            else
            {
              $hour1=$hour1;
            } 

             if($this->count_digit($minute1)==1)
            {
              $minute1='0'.$minute1;
            }
            else
            {
              $minute1=$minute1;
            }


            $total=$hour1.'.'.$minute1;
            if($status==1)
            {
              $total='-'.$total;
            }
            else
            {
              $total=$total;
            }
            //print_r('total-');print_r($total); print_r('<br>'); exit();
            return $total;
            exit();
}


      public function extrahourreport()
    {
    $this->auth->restrict('Admin.Report.Overtime_Report');
    $header = array();
    $header['headername']=" : Overtime Report";
    $this->load->view('includes/home_header',$header);
    $result = array(); 
    $this->load->helper('user');
    $data=array(); 
    $params=array();
    if($this->input->post('start_date')=='')
    { 
        $data['start_date']=date('d/m/Y');  
    }
    else
    {
       $data['start_date']=$this->input->post('start_date');  
    }
    if($this->input->post('end_date')=='')
    { 
        $data['end_date']=date('d/m/Y');  
    }
    else
    {
       $data['end_date']=$this->input->post('end_date');  
    }
       $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if( in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
    
    $params['status']=$this->input->post('status'); ///
    $params['user_id']=$this->input->post('user'); 
    //print_r($params); exit();

    if($this->input->post('start_date')=='')
    {
       $date_daily=date('d/m/y');
    }
    else
    {
      $date_daily=$this->input->post('start_date'); 
    }
    $old_date = explode('/', $date_daily); 
    $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['start_date']=$new_data;  

     if($this->input->post('end_date')=='')
    {
       $date_daily1=date('d/m/y');
    }
    else
    {
      $date_daily1=$this->input->post('end_date'); 
    }
 
    $old_date = explode('/', $date_daily1); 
    $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['end_date']=$new_data1;
    $params['unit_id']=$this->input->post('unitdata');
    $params['user']=1; // not agency staffs
  

    $startDate = new DateTime($new_data);
    $interval = $startDate->diff(new DateTime($new_data1));
    $weeks=ceil(($interval->y * 365 + $interval->days) / 7); 

   if($params['unit_id']=='')
    {
      $data['proll']=[];
    }
    else
    {
      if( in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1 || $this->session->userdata('user_type')==18)
      { 
        if($params['user_id']!=0)
          {
            $data['payroll'][0]=array('id'=>$params['user_id']);
          }
          else
          { 
            $params['unit_id']=$this->input->post('unitdata');
              if($params['status']==0)
              { 
                $all=$this->Split_payroll_model->GetPayrollactivereport($params);  
                $inactive=$this->Split_payroll_model->GetPayrollinactiveusersreport($params);
                $data['payroll']=array_merge($all,$inactive);

              }
              else
              { 
                $data['payroll'] = $this->Split_payroll_model->GetPayrollreport($params);  
              }
          } 
           
      }
      else
      {

        if($params['user_id']!=0)
          {
            $data['payroll'][0]=array('id'=>$params['user_id']);
          }
          else
          {
            $params['unit_id']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
              if($params['status']==0)
              { 
                $all=$this->Split_payroll_model->GetPayrollactivereport($params);  
                $inactive=$this->Split_payroll_model->GetPayrollinactiveusersreport($params);
                $data['payroll']=array_merge($all,$inactive);

              }
              else
              { 
                $data['payroll'] = $this->Split_payroll_model->GetPayrollreport($params);  
              }
          }
          
      }  

      // print_r($data['payroll']);
      // exit();

    $date = strtotime($params['start_date']);
    $date = strtotime("+7 day", $date);
    $data['month']=date('m', $date); 
    $data['year']=date('Y', $date); 

    $date_range=getDatesFromRange($params['start_date'],$params['end_date']);
    //print_r($date_range);exit();
    
     foreach ($data['payroll'] as $payroll) { //print_r($payroll);
        $user_info=$this->Split_payroll_model->finduserdetails($payroll['id']); 

        $day_hour=$this->getDayHoursbyUser($payroll['id'],$date_range); //day details(hours includeing daysunday,daysaturday,days)
        $night_hour=$this->getNightHoursbyUser($payroll['id'],$date_range); //night details(hours includeing nightweekday,nightweekenddays)
        $training_details=$this->getTrainingDetails($payroll['id'],$params);
        $additional_hour_bytraining=$this->findAdditionlHoursByTraining($payroll['id'],$params);

        //$daysum=$this->getCalculatedhours($payroll['id'],$params,'day'); //print_r($daysum);
        //$daysundaysum=$this->getCalculatedhours($payroll['id'],$params,'sunday');
        //$daysaturday=$this->getCalculatedhours($payroll['id'],$params,'saturday'); 
        //$days=$this->Split_payroll_model->finddays($payroll['id'],$params); 
        //$daysun=$this->Split_payroll_model->findsundays($payroll['id'],$params);
        //$daysat=$this->Split_payroll_model->findsaturdays($payroll['id'],$params);
        //$breakhour=$this->getBreakhours($payroll['id'],$params); 
        //$training=$this->Split_payroll_model->findtraininghourbyuser($payroll['id'],$params);
        $holiday=$this->getholiday($payroll['id'],$params); 
        //$bank_holiday=$this->Split_payroll_model->findbankholiday($payroll['id'],$params);
        $additional_hours_day=$this->findAdditionlHoursByDay($payroll['id'],$params);
        $additional_hours_sunday=$this->findAdditionlHoursBysunDay($payroll['id'],$params);
        $additional_hours_saturday=$this->findAdditionlHoursBysaturDay($payroll['id'],$params);
                //print_r('<pre>');
        //print_r($additional_hours_day);
 
        //$nightshift=$this->findNightshiftDetails($payroll['id'],$params); 
        $additional_hour_byweekday=$this->findAdditionlHoursByWeekday($payroll['id'],$params);
        $additional_hour_byweekend=$this->findAdditionlHoursByWeekend($payroll['id'],$params);
        // print_r('<pre>');
        // print_r($nightshift);exit();

        $additional_hour_bySick=$this->Split_payroll_model->findAdditionlHoursBySick($payroll['id'],$params); 
        $comment=$this->Split_payroll_model->Getcomment($payroll['id'],$data['month'],$data['year']); 
            if($user_info[0]['payment_type']!='Agency Payment')
            {
            $proll[$payroll['id']]=array('user_details'=>$user_info,'day_hour'=>$day_hour,'night_hour'=>$night_hour,'new_training'=>$training_details,'holiday'=>$holiday,'additional'=>$additional_hours_day,'additional_sun'=>$additional_hours_sunday,'additional_sat'=>$additional_hours_saturday,'additional_hour_bytraining'=>$additional_hour_bytraining,'additional_hour_byweekday'=>$additional_hour_byweekday,'additional_hour_byweekend'=>$additional_hour_byweekend,'comment'=>$comment,'additional_Sick'=>$additional_hour_bySick);
            }
      } 
      //exit();
      $data['proll']=$proll;
 } 
 //  print_r('<pre>');
 // print_r($data['proll']); 
 //  exit();
    if($weeks){ $data['weeks']=$weeks; }
    if($params['unit_id']!='none'){
        $data['user']=$this->Reports_model->finduserdataforall($params['unit_id'],$params['status']);   
      }
    $data['user_post']=$this->input->post('user');  
    $data['status']=$params['status'];
    $this->load->view('admin/reports/extrahourreport',$data);
    $result['js_to_load'] = array('reports/extrahourreport.js');
    $this->load->view('includes/home_footer',$result);
  }
    public function splitpayroll()
    {
        $this->auth->restrict('Admin.Report.Payroll');
        $header = array();
        $header['headername']=" : Timesheet Summary for the Period"." ".$this->input->post('start_date').' '. "to".' '.$this->input->post('end_date');
        $this->load->view('includes/home_header',$header);
        $result = array(); 
        $this->load->helper('user');
        $data=array(); 
        $params=array();
        $jobe_roles=array();
        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if( in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        else if($this->session->userdata('subunit_access')==1)
        {
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                    $sub=$parent;
                }
                else
                {
                    $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
        $data['jobrole'] = $this->Reports_model->fetchjobrole(); 
        $data['job_roles']=json_encode($jobe_roles); //print_r($data['job_roles']);exit();
        $this->load->view('admin/reports/splitpayroll',$data);
        $result['js_to_load'] = array('reports/payroll.js');
        $this->load->view('includes/home_footer',$result);
    }
    public function get_payrollreport(){
        $order_table = 'users';
        $order = $this->input->post('order');
        $column_index = $order[0]['column'];
        if($column_index == 0){
            $sort_column_name = 'payroll_id';
        }else{
            $sort_column_name = 'fname';
            $order_table = 'personal_details';
        }
        $column_name = $sort_column_name;
        $order_direction = $order[0]['dir'];
        $config = array(
            'draw'            => $this->input->post('draw'),
            'start'           => $this->input->post('start'),
            'length'          => $this->input->post('length'),
            'search_value'    => $this->input->post('search[value]'),
            'column_name'     => $column_name,
            'order_direction' => $order_direction,
            'order_table' => $order_table
        );
        $params['unit_id'] = $this->input->post('unit');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $params['jobrole'] = $this->input->post('jobrole');
        $params['status'] = $this->input->post('status');

        $old_start_date = explode('/', $start_date); 
        $new_start_date = $old_start_date[2].'-'.$old_start_date[1].'-'.$old_start_date[0];
        $params['start_date']=$new_start_date;

        $old_end_date = explode('/', $end_date); 
        $new_end_date = $old_end_date[2].'-'.$old_end_date[1].'-'.$old_end_date[0];
        $params['end_date']=$new_end_date;

        $startDate = new DateTime($new_start_date);
        $interval = $startDate->diff(new DateTime($new_end_date));
        $weeks=ceil(($interval->y * 365 + $interval->days) / 7);
        $params['user']=1;
        if($params['start_date'] == $params['end_date'])
        {
            $data['proll']=[];
            $total_count=0;
            $filter_count=0;
        }
        else
        {
            if( in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1 || $this->session->userdata('user_type')==18)
            { 
                if($params['unit_id']==0)
                {
                    $params['unit_id']=$this->session->userdata('unit_id');
                }
                else
                { 
                    $params['unit_id']=$this->input->post('unit');
                } 
                if($params['status']==0)
                {
                    $data['payroll']=$this->Split_payroll_model->GetPayrollactivereport_payroll($params,$config,1);
                    $total_count=$this->Split_payroll_model->GetPayrollactivereport_payroll($params,$config,2);
                    $filter_count=$this->Split_payroll_model->GetPayrollactivereport_payroll($params,$config,3);
                }
                else
                { 
                    $data['payroll'] = $this->Split_payroll_model->GetPayrollreport_payroll($params,$config,1);
                    $total_count=$this->Split_payroll_model->GetPayrollreport_payroll($params,$config,2);
                    $filter_count=$this->Split_payroll_model->GetPayrollreport_payroll($params,$config,3);
                }
            }
            else
            {
                $params['unit_id']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
                if($params['status']==0)
                { 
                    $data['payroll']=$this->Split_payroll_model->GetPayrollactivereport_payroll($params,$config,1);  
                    $total_count=$this->Split_payroll_model->GetPayrollactivereport_payroll($params,$config,2);
                    $filter_count=$this->Split_payroll_model->GetPayrollactivereport_payroll($params,$config,3);
                }
                else
                { 
                    $data['payroll'] = $this->Split_payroll_model->GetPayrollreport_payroll($params,$config,1);
                    $total_count=$this->Split_payroll_model->GetPayrollreport_payroll($params,$config,2);
                    $filter_count=$this->Split_payroll_model->GetPayrollreport_payroll($params,$config,3); 
                } 
            }
            $date = strtotime($params['start_date']);
            $date = strtotime("+7 day", $date);
            $data['month']=date('m', $date); 
            $data['year']=date('Y', $date); 
            $pmonth=date('m', $date); 
            $pyear=date('Y', $date); 
            $date_range=getDatesFromRange($params['start_date'],$params['end_date']);
            foreach ($data['payroll'] as $payroll) {
                $user_info=$this->Split_payroll_model->finduserdetails($payroll['id']); 
                $day_hour=$this->getDayHoursbyUser($payroll['id'],$date_range); //day details(hours includeing daysunday,daysaturday,days)
                $night_hour=$this->getNightHoursbyUser($payroll['id'],$date_range); //night details(hours includeing nightweekday,nightweekenddays)
                $training_details=$this->getTrainingDetails($payroll['id'],$params);
                $additional_hour_bytraining=$this->findAdditionlHoursByTraining($payroll['id'],$params);
                $holiday=$this->getholiday($payroll['id'],$params); 
                $additional_hours_day=$this->findAdditionlHoursByDay($payroll['id'],$params);
                $additional_hours_sunday=$this->findAdditionlHoursBysunDay($payroll['id'],$params);
                $additional_hours_saturday=$this->findAdditionlHoursBysaturDay($payroll['id'],$params);
                $additional_hour_byweekday=$this->findAdditionlHoursByWeekday($payroll['id'],$params);
                $additional_hour_byweekend=$this->findAdditionlHoursByWeekend($payroll['id'],$params);
                $additional_hours_bankholiday=$this->findAdditionlHoursBybankHoliday($payroll['id'],$params);
                $additional_hour_bySick=$this->Split_payroll_model->findAdditionlHoursBySick($payroll['id'],$params);
                $comment=$this->Split_payroll_model->Getcomment($payroll['id'],$data['month'],$data['year']); 
                if($user_info[0]['payment_type']!='Agency Payment')
                {
                    $proll[$payroll['id']]=array('user_details'=>$user_info,'day_hour'=>$day_hour,'night_hour'=>$night_hour,'new_training'=>$training_details,'holiday'=>$holiday,'additional'=>$additional_hours_day,'additional_sun'=>$additional_hours_sunday,'additional_sat'=>$additional_hours_saturday,'additional_hour_byweekday'=>$additional_hour_byweekday,'additional_hour_bytraining'=>$additional_hour_bytraining,'additional_hour_byweekend'=>$additional_hour_byweekend,'comment'=>$comment,'additional_Sick'=>$additional_hour_bySick,'additional_hours_bankholiday'=>$additional_hours_bankholiday);
                }
            }
            $payroll_data=$proll;
        }
        if($weeks){ $data['weeks']=$weeks; }
        $data_arr = array();
        foreach ($payroll_data as $payroll){
            $overtime = '';
            $html = '';
            $result = $this->get_payroll_data_values($payroll,$weeks);
            $payroll_id = $payroll['user_details'][0]['payroll_id'];
            $name = $payroll['user_details'][0]['fname'].' '.$payroll['user_details'][0]['lname'];
            $days = $result['day'];
            $days_sat = $result['daysat'];
            $days_sun = $result['daysun'];
            $weekday_nights = $result['weeknightday'];
            $weekend_nights = $result['weekendnightday'];
            $paid_sickness = $result['new_sick'];
            $training = $result['training'];
            $annual_leave = $result['holiday'];
            $bank_holiday = $result['new_bank_holiday'];
            $total = $result['total'];
            $contracted = $result['Hours'];
            $salaried = $payroll['user_details'][0]['payment_type'];
            if($result['Overtime'] > '0.00'  &&  $payroll['user_details'][0]['payment_type']=='Salaried'){
                $overtime = $result['Overtime'];
            }
            $comment=getCommentByUser($payroll['user_details'][0]['id'],$pmonth,$pyear);
            if($comment=="0"){
                $comments = '<label id="comment_' . $payroll['user_details'][0]['id'] . '" class="comment" style="width:100px;" value=""></label>';
            }else{
                $comments = '<label id="comment_' . $payroll['user_details'][0]['id'] . '" class="comment" style="width:100px;" value="">' . $payroll['comment'] . '</label>';
            }

            // Build the HTML structure for the action column
            $actionColumn = '<a href="#" onclick="edit(' . $payroll['user_details'][0]['id'] . ',' . $pmonth . ',' . $pyear . ')">';
            if ($comment == "0") {
                $actionColumn .= '<label id="edit_' . $payroll['user_details'][0]['id'] . '" class="edit" style="width:100px; float:right;">Add</label>';
            } else {
                $actionColumn .= '<label id="edit_' . $payroll['user_details'][0]['id'] . '" class="edit" style="width:100px; float:right;">Edit</label>';
            }
            $actionColumn .= '</a>';
            $action = $actionColumn;
            $data_arr[] = array(
               "payroll_id" => $payroll_id,
               "name" => $name,
               "days" => $days,
               "days_sat" => $days_sat,
               "days_sun" => $days_sun,
               "weekday_nights" => $weekday_nights,
               "weekend_nights" => $weekend_nights,
               "paid_sickness" => $paid_sickness,
               "training" => $training, 
               "annual_leave" => $annual_leave, 
               "bank_holiday" => $bank_holiday, 
               "total" => $total,
               "contracted" => $contracted, 
               "salaried" => $salaried, 
               "overtime" => $overtime, 
               "comments" => $comments, 
               "action" => $action 
            );
        }
        $response = array(
            'draw' => $config['draw'],
            'recordsTotal' => $total_count,
            'recordsFiltered' => $filter_count,
            'data' => $data_arr
        );
        echo json_encode($response);
    }
    

    public function get_payroll_data_values($payroll,$weeks){
        $dayhour=$payroll['day_hour']['dayhour'];
        $daysat=$payroll['day_hour']['saturday_hour'];
        $daysun=$payroll['day_hour']['sunday_hour'];
        if($dayhour=='00.00')
        {
            $dayhour=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional'],2))));
            $day=settimeformat($dayhour);
        }
        else
        {  
            $dayhour=$dayhour+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional'],2))));
            $day=number_format($dayhour,2);
        }
        if($daysat=='00.00' )
        {
            $daysat=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_sat'],2))));
            $daysat=settimeformat($daysat);
        }
        else
        {
            $daysat=$daysat+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_sat'],2))));
            $daysat=number_format($daysat,2);
        }
        if($daysun=='00.00')
        {
            $daysun=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_sun'],2))));
            $daysun=settimeformat($daysun);
        }
        else
        {
            $daysun=$daysun+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_sun'],2))));
            $daysun=number_format($daysun,2);
        }
        $weekday=$payroll['night_hour']['weekdayhour'];
        $weekend=$payroll['night_hour']['weekend_hour']; 

        if($weekday=='00.00')
        {
            $weekday=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_byweekday'],2))));
            $weeknightday=settimeformat($weekday);
        }
        else
        {  
            $weekday=$weekday+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_byweekday'],2))));
            $weeknightday=number_format($weekday,2);
        }

        if($weekend=='00.00')
        {
            $weekend=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_byweekend'],2))));
            $weekendnightday=settimeformat($weekend);
        }
        else
        {  
            $weekend=$weekend+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_byweekend'],2))));
            $weekendnightday=number_format($weekend,2);
        }
        $holiday=$payroll['holiday'][0]['total'];
        $training=number_format($payroll['new_training'],2); //print_r($training);
        if($training=='0.00')
        {
            $training_new=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_bytraining'],2))));
            $training=settimeformat($training_new);

        }
        else
        {
            $training_new=$training+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_bytraining'],2))));
            $training=number_format($training_new,2);

        }
        $bank_holiday=number_format($payroll['day_hour']['day_bank_holiday'],2)+number_format($payroll['night_hour']['night_bank_holiday'],2);
        if($bank_holiday=='0.00' || $bank_holiday=='0')
        {
            $bank_holiday=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hours_bankholiday'],2))));
            $new_bank_holiday=settimeformat($bank_holiday);
        }
        else
        {
            $bank_holiday=$bank_holiday+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hours_bankholiday'],2))));
            $new_bank_holiday=number_format($bank_holiday,2);
        }
        $sick = '0.00';
        $new_sick = '0.00';
        if (isset($payroll['additional_Sick'][0]['totalsickhours'])) {
            $sick = $payroll['additional_Sick'][0]['totalsickhours'];
            // Now you can use $sick without causing an "Undefined array key" error
        }
        if($sick=='0.00')
        {
            $new_sick=$sick;
        }
        else
        {
            if (isset($payroll['additional_Sick'][0]['totalsickhours'])) {
                $new_sick=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_Sick'][0]['totalsickhours'],2))));
            }

        }
        $total=$day+$daysun+$daysat+$training+$holiday+$weeknightday+$weekendnightday+$new_sick+$new_bank_holiday;
        $weekly_hour=getPayrollformat1($payroll['user_details'][0]['weekly_hours']);
        $Hours=getContractHours($weeks,$weekly_hour); 
        $Overtime=number_format($total,2)-number_format($Hours,2);
        return array(
            'day' => number_format($day, 2),
            'daysat' => number_format($daysat, 2),
            'daysun' => number_format($daysun, 2),
            'weeknightday' => number_format($weeknightday, 2),
            'weekendnightday' => number_format($weekendnightday, 2),
            'new_sick' => number_format($new_sick, 2),
            'training' => number_format($training, 2),
            'holiday' => number_format($holiday, 2),
            'new_bank_holiday' => number_format($new_bank_holiday, 2),
            'total' => number_format($total, 2),
            'Hours' => number_format($Hours, 2),
            'Overtime' => number_format($Overtime, 2)
        );
    }
    public function splitpayroll_old()
    {
    $this->auth->restrict('Admin.Report.Payroll');
    $header = array();
    $header['headername']=" : Timesheet Summary for the Period"." ".$this->input->post('start_date').' '. "to".' '.$this->input->post('end_date');
    $this->load->view('includes/home_header',$header);
    $result = array(); 
    $this->load->helper('user');
    $data=array(); 
    $params=array();
    $jobe_roles=array();
    if($this->input->post('start_date')=='')
    { 
        $data['start_date']=date('d/m/Y');  
    }
    else
    {
       $data['start_date']=$this->input->post('start_date');  
    }
    if($this->input->post('end_date')=='')
    { 
        $data['end_date']=date('d/m/Y');  
    }
    else
    {
       $data['end_date']=$this->input->post('end_date');  
    }
        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if( in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
    //print_r($this->input->post());
    $params['status']=$this->input->post('status');
    $params['jobrole']= $this->input->post("jobrole");
    if($this->input->post("jobrole"))
      $jobe_roles = $this->input->post("jobrole");
    //print_r($params);exit();
    //$params['start_date']=$this->input->post('start_date');
    if($this->input->post('start_date')=='')
    {
       $date_daily=date('d/m/y');
    }
    else
    {
    	$date_daily=$this->input->post('start_date'); 
    }
    $old_date = explode('/', $date_daily); 
    $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['start_date']=$new_data;  

     if($this->input->post('end_date')=='')
    {
       $date_daily1=date('d/m/y');
    }
    else
    {
    	$date_daily1=$this->input->post('end_date'); 
    }
 
    $old_date = explode('/', $date_daily1); 
    $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['end_date']=$new_data1;
    $params['unit_id']=$this->input->post('unitdata'); 
    $params['user']=1; // not agency staffs
    
	
	$startDate = new DateTime($new_data);
	$interval = $startDate->diff(new DateTime($new_data1));
	$weeks=ceil(($interval->y * 365 + $interval->days) / 7); 

    if($params['unit_id']=='')
    {
    	$data['proll']=[];
    }
    else
    {
	    if( in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1 || $this->session->userdata('user_type')==18)
	    { 
	      if($params['unit_id']==0)
	      	{
	      		$params['unit_id']=$this->session->userdata('unit_id');
	      	}
	      	else
	      	{ 
	      		$params['unit_id']=$this->input->post('unitdata');
	      	} 
          if($params['status']==0)
          { 
            $all=$this->Split_payroll_model->GetPayrollactivereport($params);  
            $inactive=$this->Split_payroll_model->GetPayrollinactiveusersreport($params);
            $data['payroll']=array_merge($all,$inactive);

          }
          else
          { 
            $data['payroll'] = $this->Split_payroll_model->GetPayrollreport($params);  
          }
	        
	    }
	    else
	    {
	      $params['unit_id']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
	       if($params['status']==0)
          { 
            $all=$this->Split_payroll_model->GetPayrollactivereport($params);  
            $inactive=$this->Split_payroll_model->GetPayrollinactiveusersreport($params);
            $data['payroll']=array_merge($all,$inactive);

          }
          else
          { 
            $data['payroll'] = $this->Split_payroll_model->GetPayrollreport($params);  
          } 
	    }
      
//exit();
		$date = strtotime($params['start_date']);
		$date = strtotime("+7 day", $date);
		$data['month']=date('m', $date); 
		$data['year']=date('Y', $date); 

    $date_range=getDatesFromRange($params['start_date'],$params['end_date']);
    //print_r('<pre>');
    //print_r($date_range);print '<br>'; 

	    foreach ($data['payroll'] as $payroll) { 
	    	$user_info=$this->Split_payroll_model->finduserdetails($payroll['id']); 

        $day_hour=$this->getDayHoursbyUser($payroll['id'],$date_range); //day details(hours includeing daysunday,daysaturday,days)
        $night_hour=$this->getNightHoursbyUser($payroll['id'],$date_range); //night details(hours includeing nightweekday,nightweekenddays)
        $training_details=$this->getTrainingDetails($payroll['id'],$params);
        $additional_hour_bytraining=$this->findAdditionlHoursByTraining($payroll['id'],$params);

        //$daysum=$this->getCalculatedhours($payroll['id'],$params,'day'); //print_r($daysum);
        //$daysundaysum=$this->getCalculatedhours($payroll['id'],$params,'sunday');
        //$daysaturday=$this->getCalculatedhours($payroll['id'],$params,'saturday'); 
	    	//$days=$this->Split_payroll_model->finddays($payroll['id'],$params); 
	    	//$daysun=$this->Split_payroll_model->findsundays($payroll['id'],$params);
	    	//$daysat=$this->Split_payroll_model->findsaturdays($payroll['id'],$params);
        //$breakhour=$this->getBreakhours($payroll['id'],$params); 
	    	//$training=$this->Split_payroll_model->findtraininghourbyuser($payroll['id'],$params);
	    	$holiday=$this->getholiday($payroll['id'],$params); 
	    	//$bank_holiday=$this->Split_payroll_model->findbankholiday($payroll['id'],$params);
        $additional_hours_day=$this->findAdditionlHoursByDay($payroll['id'],$params);
        $additional_hours_sunday=$this->findAdditionlHoursBysunDay($payroll['id'],$params);
        $additional_hours_saturday=$this->findAdditionlHoursBysaturDay($payroll['id'],$params);
                //print_r('<pre>');
        //print_r($additional_hours_day);
 
	    	//$nightshift=$this->findNightshiftDetails($payroll['id'],$params); 
        $additional_hour_byweekday=$this->findAdditionlHoursByWeekday($payroll['id'],$params);
        $additional_hour_byweekend=$this->findAdditionlHoursByWeekend($payroll['id'],$params);

        $additional_hours_bankholiday=$this->findAdditionlHoursBybankHoliday($payroll['id'],$params);
        // print_r('<pre>');
        // print_r($nightshift);exit();

        $additional_hour_bySick=$this->Split_payroll_model->findAdditionlHoursBySick($payroll['id'],$params); //---- 1
	    	$comment=$this->Split_payroll_model->Getcomment($payroll['id'],$data['month'],$data['year']); 

            if($user_info[0]['payment_type']!='Agency Payment')
            {
	    	    $proll[$payroll['id']]=array('user_details'=>$user_info,'day_hour'=>$day_hour,'night_hour'=>$night_hour,'new_training'=>$training_details,'holiday'=>$holiday,'additional'=>$additional_hours_day,'additional_sun'=>$additional_hours_sunday,'additional_sat'=>$additional_hours_saturday,'additional_hour_byweekday'=>$additional_hour_byweekday,'additional_hour_bytraining'=>$additional_hour_bytraining,'additional_hour_byweekend'=>$additional_hour_byweekend,'comment'=>$comment,'additional_Sick'=>$additional_hour_bySick,'additional_hours_bankholiday'=>$additional_hours_bankholiday);
            }
	    } 
	    $data['proll']=$proll;


	}  
 //  print_r('<pre>'); 
 // print_r($data['proll']); 
 //  exit();
    if($weeks){ $data['weeks']=$weeks; }
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/
    $data['jobrole'] = $this->Reports_model->fetchjobrole(); 
    $data['job_roles']=json_encode($jobe_roles); //print_r($data['job_roles']);exit();
    $this->load->view('admin/reports/splitpayroll',$data);
    $result['js_to_load'] = array('reports/payroll.js');
    $this->load->view('includes/home_footer',$result);
    }

     public function agencyuserreport()
    {
    $this->auth->restrict('Admin.Report.AgencyReport');
    $header = array();
    $header['headername']=" : Agency Use Summary for the Period"." ".$this->input->post('start_date').' '. "to".' '.$this->input->post('end_date');
    $this->load->view('includes/home_header',$header);
    $result = array(); 
    $this->load->helper('user');
    $data=array(); 
    $params=array();
    $jobe_roles=array();
    if($this->input->post('start_date')=='')
    { 
        $data['start_date']=date('d/m/Y');  
    }
    else
    {
       $data['start_date']=$this->input->post('start_date');  
    }
    if($this->input->post('end_date')=='')
    { 
        $data['end_date']=date('d/m/Y');  
    }
    else
    {
       $data['end_date']=$this->input->post('end_date');  
    }
        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
    //print_r($this->input->post());
    $params['status']=$this->input->post('status');
    $params['jobrole']= $this->input->post("jobrole");
    if($this->input->post("jobrole"))
      $jobe_roles = $this->input->post("jobrole");
    //print_r($params);exit();
    //$params['start_date']=$this->input->post('start_date');
    if($this->input->post('start_date')=='')
    {
       $date_daily=date('d/m/y');
    }
    else
    {
      $date_daily=$this->input->post('start_date'); 
    }
    $old_date = explode('/', $date_daily); 
    $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['start_date']=$new_data;  

     if($this->input->post('end_date')=='')
    {
       $date_daily1=date('d/m/y');
    }
    else
    {
      $date_daily1=$this->input->post('end_date'); 
    }
 
    $old_date = explode('/', $date_daily1); 
    $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['end_date']=$new_data1;
    $params['unit_id']=$this->input->post('unitdata'); 

    $params['user']=2; //agency staff
    
  
  $startDate = new DateTime($new_data);
  $interval = $startDate->diff(new DateTime($new_data1));
  $weeks=ceil(($interval->y * 365 + $interval->days) / 7); 

    if($params['unit_id']=='')
    {
      $data['proll']=[];
    }
    else
    {
      if( in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1 || $this->session->userdata('user_type')==18)
      { 
        if($params['unit_id']==0)
          {
            $params['unit_id']=$this->session->userdata('unit_id');
          }
          else
          { 
            $params['unit_id']=$this->input->post('unitdata');
          } 
          if($params['status']==0)
          { 
            $all=$this->Split_payroll_model->GetPayrollactivereport($params);  
            $inactive=$this->Split_payroll_model->GetPayrollinactiveusersreport($params);
            $data['payroll']=array_merge($all,$inactive);

          }
          else
          { 
            $data['payroll'] = $this->Split_payroll_model->GetPayrollreport($params);  
          }
          
      }
      else
      {
        $params['unit_id']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
         if($params['status']==0)
          { 
            $all=$this->Split_payroll_model->GetPayrollactivereport($params);  
            $inactive=$this->Split_payroll_model->GetPayrollinactiveusersreport($params);
            $data['payroll']=array_merge($all,$inactive);

          }
          else
          { 
            $data['payroll'] = $this->Split_payroll_model->GetPayrollreport($params);  
          } 
      }
      
//exit();
    $date = strtotime($params['start_date']);
    $date = strtotime("+7 day", $date);
    $data['month']=date('m', $date); 
    $data['year']=date('Y', $date); 

    $date_range=getDatesFromRange($params['start_date'],$params['end_date']);
    // print_r('<pre>');
    // print_r($data['payroll']);print '<br>'; exit();

      foreach ($data['payroll'] as $payroll) { 
        $user_info=$this->Split_payroll_model->finduserdetails($payroll['id']); 

        $day_hour=$this->getDayHoursbyUser($payroll['id'],$date_range); //day details(hours includeing daysunday,daysaturday,days)
        $night_hour=$this->getNightHoursbyUser($payroll['id'],$date_range); //night details(hours includeing nightweekday,nightweekenddays)
        $training_details=$this->getTrainingDetails($payroll['id'],$params);
        $additional_hour_bytraining=$this->findAdditionlHoursByTraining($payroll['id'],$params);
        $shift_count=$this->findagencyshiftcount($payroll['id'],$params);

        //$daysum=$this->getCalculatedhours($payroll['id'],$params,'day'); //print_r($daysum);
        //$daysundaysum=$this->getCalculatedhours($payroll['id'],$params,'sunday');
        //$daysaturday=$this->getCalculatedhours($payroll['id'],$params,'saturday'); 
        //$days=$this->Split_payroll_model->finddays($payroll['id'],$params); 
        //$daysun=$this->Split_payroll_model->findsundays($payroll['id'],$params);
        //$daysat=$this->Split_payroll_model->findsaturdays($payroll['id'],$params);
        //$breakhour=$this->getBreakhours($payroll['id'],$params); 
        //$training=$this->Split_payroll_model->findtraininghourbyuser($payroll['id'],$params);
        $holiday=$this->getholiday($payroll['id'],$params); 
        //$bank_holiday=$this->Split_payroll_model->findbankholiday($payroll['id'],$params);
        $additional_hours_day=$this->findAdditionlHoursByDay($payroll['id'],$params);
        $additional_hours_sunday=$this->findAdditionlHoursBysunDay($payroll['id'],$params);
        $additional_hours_saturday=$this->findAdditionlHoursBysaturDay($payroll['id'],$params);
                //print_r('<pre>');
        //print_r($additional_hours_day);
 
        //$nightshift=$this->findNightshiftDetails($payroll['id'],$params); 
        $additional_hour_byweekday=$this->findAdditionlHoursByWeekday($payroll['id'],$params);
        $additional_hour_byweekend=$this->findAdditionlHoursByWeekend($payroll['id'],$params);
        // print_r('<pre>');
        // print_r($nightshift);exit();

        $additional_hour_bySick=$this->Split_payroll_model->findAdditionlHoursBySick($payroll['id'],$params); 
        $comment=$this->Split_payroll_model->Getcomment($payroll['id'],$data['month'],$data['year']); 
            if($user_info[0]['payment_type']='Agency Payment')
            {
            $proll[$payroll['id']]=array('user_details'=>$user_info,'day_hour'=>$day_hour,'night_hour'=>$night_hour,'new_training'=>$training_details,'holiday'=>$holiday,'additional'=>$additional_hours_day,'additional_sun'=>$additional_hours_sunday,'additional_sat'=>$additional_hours_saturday,'additional_hour_byweekday'=>$additional_hour_byweekday,'additional_hour_bytraining'=>$additional_hour_bytraining,'additional_hour_byweekend'=>$additional_hour_byweekend,'comment'=>$comment,'additional_Sick'=>$additional_hour_bySick,'shifts_count'=>$shift_count);
            }
      } 
      $data['proll']=$proll;


  }  
 //  print_r('<pre>'); 
 // print_r($data['proll']); 
 //  exit();
    if($weeks){ $data['weeks']=$weeks; }
   
    $data['jobrole'] = $this->Reports_model->fetchjobrole(); 
    $data['job_roles']=json_encode($jobe_roles); //print_r($data['job_roles']);exit();
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/
    $this->load->view('admin/reports/newagencyreport',$data);
    $result['js_to_load'] = array('reports/agencyreport.js');
    $this->load->view('includes/home_footer',$result);
    }

    public function findagencyshiftcount($user_id,$params)
    {
      $shifts=$this->Split_payroll_model->getShiftcount($user_id,$params);
      $EL_count=0;
      $E_count=0;
      $L_count=0;
      $N_count=0;
      if($shifts)
        foreach ($shifts as $value) {

              if($value['shift_id']=='13')
              {
                  $E_count=$E_count+1;
              } 
              else if($value['shift_id']=='14')
              {
                 $L_count=$L_count+1;
              } 
              else if($value['shift_id']=='15')
              {
                 $EL_count=$EL_count+1;
              } 
              else if($value['shift_id']=='16')
              {
                 $N_count=$N_count+1;
              } 
        }
        $result=array('user_id'=>$user_id,'E_count'=>$E_count,'L_count'=>$L_count,'EL_count'=>$EL_count,'N_count'=>$N_count);
     return $result;
    }


    public function getholiday($user_id,$params)
    { 
      //$user_id=803;
      $holiday=$this->Split_payroll_model->findAnnualLeave($user_id,$params);  //find goliday details
      //print_r($holiday);//exit();
      if(empty($holiday))
      {
         $holiday_days[0]['total']='00.00';
      }
      else
      {
      	$hours=0;
        foreach ($holiday as  $value) {   

        	$total_hours=$value['apllied_hours'];   
          $total_hours=str_replace(":",".",$total_hours);
          $total_hours=number_format(getPayrollformat(number_format($total_hours,2),2),2);//print_r($total_hours);print '<br>';  //applied hours 

			    $weekendDays = 0;    
        	$startTimestamp = strtotime($value['fromtime']);
		      $endTimestamp = strtotime($value['totime']);
		    for ($i = $startTimestamp; $i <= $endTimestamp; $i = $i + (60 * 60 * 24)) {
		        if (date("N", $i) > 5) $weekendDays = $weekendDays + 1;
		    } 
		    $number=$this->getNumberOfDays($value['fromtime'],$value['totime']); //print_r($number);print '<br>';//exit(); // total number of days between dates
		    // if($weekendDays==0)
		    // {
		    // 	     $days=$number;
		    // }
		    // else
		    // {
      //           $days=$number-$weekendDays;

		    // }  
        $days=$number; 
        if($days==0)  
        { 
          $each_hour_day=$total_hours;  //hor per day   
        } 
        else  
        { 
          $each_hour_day=$total_hours/$days;  //hor per day   
        }  // finding hour per day

		    //$each_hour_day=$total_hours/$days;  //hor per day  
        // print_r($total_hours);
        // print_r($days);
       //print_r($each_hour_day);print '<br>';//exit();

      $begin = new DateTime($value['fromtime']);
			$end = new DateTime($value['totime']);
			$end = $end->modify( '+1 day' ); 

			$interval = new DateInterval('P1D');
			$daterange = new DatePeriod($begin, $interval ,$end);
            $count=0;
			foreach($daterange as $date){  //print_r($date); print '<br>';
			    $date_new=$date->format("Y-m-d"); 
			    $result=$this->dateBetweenTwodates($params['start_date'],$params['end_date'],$date_new);
          //print_r($result); print '<br>';
			    if($result=='true')
			    {
			    	$count=$count+1;
			    }
          //print_r($count); print '<br>';
			    
			    $hour=$each_hour_day*$count; 
			} //print_r($hour); print '<br>';
            $hours=$hours+$hour;
		   
        } 
       
		  $holiday_days[0]['total']=number_format($hours,2);
      }

      //print_r($holiday_days); 
      //exit();

      return $holiday_days;
  }

  public function dateBetweenTwodates($from_date,$to_date,$date)
  { 
      $paymentDate = $date;
    	$paymentDate=date('Y-m-d', strtotime($paymentDate));
    	//echo $paymentDate; // echos today!  
    	$contractDateBegin = date('Y-m-d', strtotime($from_date));
    	$contractDateEnd = date('Y-m-d', strtotime($to_date));
     
    	$nameOfDay = date('D', strtotime($date));
    	$day=$nameOfDay;  

      	if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd) )
      	{
                 return 'true';
       
      	}
      	else
      	{ 
      	    return 'false';  
      	} 
  }

    public function getNumberOfDays($fromtime,$totime)
    { 
    $now = strtotime($totime); // or your date as well
		$your_date = strtotime($fromtime);
		$datediff = $now - $your_date;

		$days=round($datediff / (60 * 60 * 24));
		return $days+1; 
      }


public function getDayHoursbyUser($user_id,$date_range)
{ // to find user day shift details


  //$user_id='1357';
  $sunday_hour='0.00';
  $saturday_hour='0.00';
  $day_hour='0.00';
  $sunday_hour_holiday='0.00';
  $saturday_hour_holiday='0.00';
  $day_hour_holiday='0.00';

  for($i=0;$i<count($date_range);$i++){ //looping date from start to end dates

    //print_r($date_range[$i]); print '<br>';
         
          $shift_details=getShiftdetailsnew($user_id,$date_range[$i]); //get user shift data using userid and date
          //print '<pre>'; print_r($shift_details);print_r('<br>');

          $start_time=$shift_details['start_time']; //set start time
          $end_time=$shift_details['end_time']; //end time
          $break=$shift_details['unpaid_break_hours']; //break hour
          $shift_name=$shift_details['shift_name']; //shift name
          $shift_category=$shift_details['shift_category'];


         if($shift_category==1 || $shift_category==3 || $shift_category==4)
         {
            $checkin=getshiftcheckinDetails($date_range[$i],$user_id); //get user checkin datas ,avoiding 00:00:00 values
            $checkout=getDayshiftChekoutDetails($date_range[$i],$user_id);//get user checkout datas ,avoiding 00:00:00 values

         }
         else if($shift_name=='Sick')
         {
            $checkin=getshiftcheckinDetails($date_range[$i],$user_id); //get user checkin datas ,avoiding 00:00:00 values
            $checkout=getDayshiftChekoutDetails($date_range[$i],$user_id);//get user checkout datas ,avoiding 00:00:00 values

         }
         else if($shift_name=='Offday') 
         {
            $checkin=getshiftcheckinDetails($date_range[$i],$user_id); //get user checkin datas ,avoiding 00:00:00 values
            $checkout=getDayshiftChekoutDetails($date_range[$i],$user_id);//get user checkout datas ,avoiding 00:00:00 values

         }
         else
         {
            $checkin= "00:00:00";
            $checkout= "00:00:00";
         }                                                


              if($checkin==""){
                                  $checkin="00:00:00";
                              }
              if($checkout==""){
                                  $checkout="00:00:00";
                                }

              //print_r("<pre>");
              //print_r($checkin); print '<br>';
              //print_r($checkout); print '<br>';
              $cins = array();
              $couts = array();
              if(!empty($checkin))
              { //if checkin not empty
                  $j=1; 
                  foreach ($checkin as $check) {
                    $cins[] =$check['date'].' '.$check['time_from']; //adding date time form to cins array
                    $j++;
                  }

              }
              else
              { //othervice array is empty
                $cins = array(" ");
                $couts = array(" ");
              }

              //print_r($cins);print '<br>';

              if(!empty($checkout)){ //if checkout is not empty
                  $j=1; 
                  foreach ($checkout as $checks) {
                    $couts[] =$checks['date'].' '.$checks['time_to']; //adding datetime to couts
                    $j++;
                  }

              }
              else
              { //otherwice its empty
                  $cins = array(" ");
                  $couts = array(" ");
              }

              //print_r($couts);print '<br>';

              $time_n=array();
              for($c=0;$c<count($couts);$c++){
              $in_time="";
                if($couts[$c]!='' && $cins[$c]!='')
                { //if cout and cin are not empty
                    if($shift_name=='Offday')
                    {  
                        $_cin = explode(" ",$cins[$c]);
                        $_cout = explode(" ",$couts[$c]);
                        $start_time = $_cin[1];
                        $end_time = $_cout[1];
                    }

                    $hour=getPayrollExtrahournewforDayHours($cins[$c],$couts[$c],$start_time,$end_time); //finding intime using checkin and checkout values also compared with start and end values
                    //print_r($hour); print '<br>';
                    
                    $in_time=date("H:i",strtotime($hour['time'])); //print "hii-";print_r($in_time);
                    if($shift_details['targeted_hours']<$in_time)  // if intime > than shift hour,set shift hour as intime
                    {
                      $in_time=$shift_details['targeted_hours'];
                    }
                    else
                    {
                      $in_time=$in_time;
                    }
                    $new=$in_time; 
                    $time_n[]=$new;

                }
              }

              $in_time_sum=0;
              foreach ($time_n as $value1) { //adding intimes
                if($value1!='' || $value1!='00:00')
                {
                    $in_time_sum=$in_time_sum+settimeformat(getPayrollformat1($value1));
                }
              }
             $break=getPayrollformat1($break);
             //print($break);print '<br>';

            if($in_time_sum!='' ||$in_time_sum!=0)
            {  //adding intime and break hours
                  $sum_total=settimeformat($in_time_sum)-settimeformat($break);
                                                               //print_r($sum_total);
            }
            else
            {
                  $sum_total='00:00';
            }
            //print($sum_total); print '<br>';

            $hour_total_by_date=number_format($sum_total,2); //total value
            
            //changed by swaraj on jan 18
              if($hour_total_by_date>0)
              {
                if(settimeformat(getPayrollformat1($shift_details['total_targeted_hours']))<$hour_total_by_date)
                {
                  $hour_total_by_date = settimeformat(getPayrollformat1($shift_details['total_targeted_hours']));
                }
                else
                {
                  $hour_total_by_date = $hour_total_by_date;
                }
              }
              else
              {
                $hour_total_by_date = '0.0';
              }


            //print($hour_total_by_date); print '<br>';


            $nameOfDay = date('D', strtotime($date_range[$i]));
            if($nameOfDay=='Sun')
            { //check if its sunday,then assign to sunday hour
              $sunday_hour=$sunday_hour+$hour_total_by_date;
            }
            else if($nameOfDay=='Sat')
            {//check if its saturday,then assign to saturday hour
              $saturday_hour=$saturday_hour+$hour_total_by_date;
            }
            else
            { //added to day hour
              $day_hour=$day_hour+$hour_total_by_date;

            } 


            $status=Checkbankholiday($date_range[$i]); //checking bank holiday by date
            //print_r($status); print '---';
            //print($hour_total_by_date); print '<br>';

            if($status=='true')
            {
              $nameOfDay = date('D', strtotime($date_range[$i]));
              if($nameOfDay=='Sun')
              { //check if its sunday,then assign to sunday hour
                $sunday_hour_holiday=$sunday_bank_holiday+$hour_total_by_date;
              }
              else if($nameOfDay=='Sat')
              {//check if its saturday,then assign to saturday hour
                $saturday_hour_holiday=$saturday_hour_holiday+$hour_total_by_date;
              }
              else
              { //added to day hour
                $day_hour_holiday=$day_hour_holiday+$hour_total_by_date;

              } 
            }

  }

  //print_r($sunday_hour); print '-------'; print_r($sunday_hour_holiday); print '<br>';
  //print_r($saturday_hour); print '-------'; print_r($saturday_hour_holiday); print '<br>';
  //print_r($day_hour); print '-------'; print_r($day_hour_holiday); print '<br>';
  $day_bank_holiday=$sunday_hour_holiday+$saturday_hour_holiday+$day_hour_holiday;
  //print_r($day_bank_holiday);

  if($day_hour_holiday!='')
  {
    $day_hour=$day_hour-$day_hour_holiday;
  }
  else
  {
    $day_hour=$day_hour;
  }

  if($saturday_hour_holiday!='')
  {
    $saturday_hour=$saturday_hour-$saturday_hour_holiday;
  }
  else
  {
    $saturday_hour=$saturday_hour;
  }

  if($sunday_hour_holiday!='')
  {
    $sunday_hour=$sunday_hour-$sunday_hour_holiday;
  }
  else
  {
    $sunday_hour=$sunday_hour;
  }

  $result=array('dayhour'=>$day_hour,'saturday_hour'=>$saturday_hour,'sunday_hour'=>$sunday_hour,'day_bank_holiday'=>$day_bank_holiday);
  //print_r($result);
  // exit();
  return $result;

}

public function getNightHoursbyUser($user_id,$date_range)
{

 //$user_id=665;

  $weekday_hour='0.00';
  $weekend_hour='0.00'; 
  $weekday_bank_holiday='0.00';
  $weekend_bank_holiday='0.00';

  for($i=0;$i<count($date_range);$i++){

    //print_r($date_range[$i]); print '<br>';

              $shift_details=getShiftdetailsnew($user_id,$date_range[$i]); //shift details
              //print '<pre>'; print_r($shift_details);print_r('<br>');

              $day_date=$date_range[$i]; //date
              $start_time=$shift_details['start_time']; //shift start time
              $end_time=$shift_details['end_time'];// shift end time
              $break=$shift_details['unpaid_break_hours']; //break time
              $shift_name=$shift_details['shift_name']; //shift name
              $shift_category=$shift_details['shift_category']; //shift category


            if($shift_name=='Night' || $shift_name=='Training + Night'|| $shift_name=='Student Nurse Night')
            {
              $checkin=getNightshiftcheckinDetails($date_range[$i],$user_id); //finding checkin and checkout details
              $checkout=getNightshiftChekoutDetails($date_range[$i],$user_id);
            }
            else
            {
              $checkin= "00:00:00";
              $checkout= "00:00:00";
            }

              if($checkin==""){
                                  $checkin="00:00:00";
                              }
              if($checkout==""){
                                  $checkout="00:00:00";
                                }

              //print_r("<pre>");
             // print_r($checkin); print '<br>';
              //print_r($checkout); print '<br>';
              $cins = array();
              $couts = array();
              if(!empty($checkin))
              { //checkin not empty
                  $j=1; 
                  foreach ($checkin as $check) { //looopong it

                    if($day_date==$check['date'])
                    { //checin date equal to date
                      $cins[] =$check['date'].' '.$check['time_from']; //added to cin
                    }
                    else
                    { //if checkin in next date ,checkin check_in time greater than shift end time
                      if(settimeformat($end_time)<settimeformat($check['time_from']))
                      { //setting cin as empty
                        $cins = array(" ");
                        $cins_time[]= array(" ");
                      }
                      else
                      { //otherwise set cin value
                        $cins[] =$check['date'].' '.$check['time_from'];
                      }
                    }
                    $j++;
                  }

              }
              else
              {//if checkin empty,then set cin as empty
                $cins = array(" ");
                $couts = array(" ");
              }

              //print "cin-";print_r($cins);print '<br>';

              if(!empty($checkout)){
                  $j=1; 
                  foreach ($checkout as $checks) {
                    $couts[] =$checks['date'].' '.$checks['time_to'];
                    $j++;
                  }

              }
              else
              {
                  $cins = array(" ");
                  $couts = array(" ");
              }

             //print "cout-"; print_r($couts);print '<br>';
             //print(count($couts));
              $time_n=array();
              for($c=0;$c<count($couts);$c++){
              $in_time="";
                if($couts[$c]!='' && $cins[$c]!='')
                {    
                    // if($shift_name=='Offday')
                    // {
                    //     $_cin = explode(" ",$cins[$c]);
                    //     $_cout = explode(" ",$couts[$c]);
                    //     $start_time = $_cin[1];
                    //     $end_time = $_cout[1];
                    // }

                    
                  

                    $hour=getPayrollExtrahournew($cins[$c],$couts[$c],$start_time,$end_time,'',$shift_category,$day_date);
                    //print_r($hour); print '<br>';
                    
                    $in_time=date("H:i",strtotime($hour['time'])); //print "hii-";print_r($in_time);
                    if($shift_details['targeted_hours']<$in_time)  // if intime > than shift hour,set shift hour as intime
                    {
                      $in_time=$shift_details['targeted_hours'];
                    }
                    else
                    {
                      $in_time=$in_time;
                    }
                    $new=$in_time; //print_r($new);
                    $time_n[]=$new;

                } 
              }

              $in_time_sum=0;
              foreach ($time_n as $value1) {
                if($value1!='' || $value1!='00:00')
                {
                    $in_time_sum=$in_time_sum+settimeformat(getPayrollformat1($value1));
                }
              }
             $break=getPayrollformat1($break);
             //print($break);print '<br>';

            if($in_time_sum!='' ||$in_time_sum!=0)
            {  
                  $sum_total=settimeformat($in_time_sum)-settimeformat($break);
                                                               //print_r($sum_total);
            }
            else
            {
                  $sum_total='00:00';
            }
            //print($sum_total); print '<br>';

            $hour_total_by_date=number_format($sum_total,2);
            //print($hour_total_by_date); print '<br>';

            //changed by swaraj on jan 18
            if($hour_total_by_date>0)
            {
                if(settimeformat(getPayrollformat1($shift_details['total_targeted_hours']))<$hour_total_by_date)
                {
                  $hour_total_by_date = settimeformat(getPayrollformat1($shift_details['total_targeted_hours']));
                }
                else
                {
                  $hour_total_by_date = $hour_total_by_date;
                }
            }
            else
            {
                $hour_total_by_date = '0.0';
            }


            $nameOfDay = date('D', strtotime($date_range[$i]));
            if($nameOfDay=='Sun' || $nameOfDay=='Sat')
            {
              $weekend_hour=$weekend_hour+$hour_total_by_date;
            }
            else
            {
              $weekday_hour=$weekday_hour+$hour_total_by_date;

            } 

             $status=Checkbankholiday($date_range[$i]); //checking bank holiday by date
            //print_r($status); print '---';
            //print($hour_total_by_date); print '<br>';
            if($status=='true')
            {
              $nameOfDay = date('D', strtotime($date_range[$i]));
              if($nameOfDay=='Sun' || $nameOfDay=='Sat')
              { //check if its sunday,then assign to sunday hour
                $weekend_bank_holiday=$weekend_bank_holiday+$hour_total_by_date;
              }
              else 
              {//check if its saturday,then assign to saturday hour
                $weekday_bank_holiday=$weekday_bank_holiday+$hour_total_by_date;
              }
              
            }

  }

   //print_r($sunday_hour); print '-------'; print_r($sunday_hour_holiday); print '<br>';
  //print_r($saturday_hour); print '-------'; print_r($saturday_hour_holiday); print '<br>';
  //print_r($day_hour); print '-------'; print_r($day_hour_holiday); print '<br>';
  $night_bank_holiday=$weekend_bank_holiday+$weekday_bank_holiday;
  //print_r($day_bank_holiday);

  if($weekday_bank_holiday!='')
  {
    $weekday_hour=$weekday_hour-$weekday_bank_holiday;
  }
  else
  {
    $weekday_hour=$weekday_hour;
  }

  if($weekend_bank_holiday!='')
  {
    $weekend_hour=$weekend_hour-$weekend_bank_holiday;
  }
  else
  {
    $weekend_hour=$weekend_hour;
  }


  $result=array('weekdayhour'=>$weekday_hour,'weekend_hour'=>$weekend_hour,'night_bank_holiday'=>$night_bank_holiday);
  // print_r($result);
  // exit();
  return $result;

}

public function getTrainingDetails($user_id,$params)
{
  $sum='00.00';
  //print_r($params);//exit();
  $training_hour=$this->Split_payroll_model->GetTrainingHourbyuser($user_id,$params);
  if(!empty($training_hour))
  {
    foreach ($training_hour as $value) {   

      if($value['training_hour']=='00:00' || $value['training_hour']=='' )
      {
        $hour='00.00';
      }
      else
      { 
        if($value['date_from']!='')
        { // if training date from not null, checking  rota schedule with date and user_id for shifts like absence,sick,awol,if found  return true else false
          $shift=getShiftIdByUser($user_id,$value['date_from']); 
        }
        else
        { //otherwise print false
           $shift='false';
        }

        if($shift=='true')
        { //if absence etc.. shifts present in rotaschedule,no training hours
           $hour='00.00';
        }
        else
        {
           $hour=settimeformat(getPayrollformat1($value['training_hour']));
        }

      }

      $sum=$sum+$hour;
    }
    $hour_new=$sum;
  } 
  else
  {
    $hour_new='00.00';
  } 
  //exit();
  return $hour_new;
   
}

		 
 
 public function getCalculatedhours($user_id,$params,$day)
    { 
        $sum='00.00'; 
        //$user_id=737;
        $hour=$this->Split_payroll_model->calculatepayrolldata($user_id,$params,$day);
        //print_r("<pre>");
        //print_r($hour);
       //print_r('<br>');//exit();
          if(count($hour)>0) 
          {

            foreach ($hour as $day) {

                 if($day['logtime']!='00:00' && $day['logtime']!=0)
                 {  
                    if($day['fromtime']!='00:00:00')
                    { 
                        $hour=findDifference($day['logtime'],$day['break']); 
                    }
                    else
                    {
                        $hour='00.00';
                    }
                 }
                 else
                 {  
                   $hour='00.00';
                 }
                $hour=getPayrollformat1(settimeformat_new($hour));
                //print_r('<pre>');
                //print_r("sum-".$sum."<br>");
               //print_r("hour-".$hour."<br>");
                 $hour_new=settimeformat($hour); 
                 $sum=$sum+$hour_new; //print_r("asum-".$hour_new."<br>");   //print_r("neesum-".$sum."<br>");
                 // $sums=explode('.', $sum);print_r($sums);
                 // $sum=$sums[0].'.'.abs($sums[1]);

             


            } 
          }
          else
          {

            $sum='00.00';
          } 
          //print_r($sum);
         //exit();
        return $sum;

    }

public function findAdditionlHoursByTraining($payroll,$params)
{
      $additional_hours_day=$this->Split_payroll_model->findAdditionlHoursByTraining($payroll,$params);
     
      //print_r($additional_hours_day);
      //print_r(count($additional_hours_day));
      //print '<br>';
      $sum='00.00.00';
      foreach ($additional_hours_day as $value) {
        //print_r($value['extrahour']); print '<br>';

            $hour_new=$value['extrahour']; //print 'hour_new'; print_r($hour_new); print '<br>';

                                if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }else
                                {
                                  $sum=$sum;
                                }

                                if(strpos($hour_new, "-") !== false){
                                $shour = explode(":",$hour_new);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }
                                else
                                {
                                  $hour=$hour_new;
                                }

                          // print_r('sum-'); print_r($sum); print '<br>';
                          //print_r('hour-');
                          //print_r($hour); print '<br>'; //exit();

                            //$sum = $this->time_to_decimal($sum);  print_r('sumnew-'); print_r($sum);
                            $b = $this->new_time_to_decimal($hour);  //print_r('hournew-');print_r($b); print_r('<br>');print_r('<br>');print_r('<br>');
                            $sum= $sum + $b; 


            } 
            $total=$this->decimal_to_time($sum);   

             //$total='00.30.00';
            $shour = explode(".",$total);
            //print '<pre>';
            //print_r($shour); print '<br>';//exit();

            if($shour[0]<0)
            {
              $hour1=$shour[0]*-1;
              $status=1; 
            }
            else
            {
              $hour1=$shour[0]; 
              $status=2;
            }
            //print_r($hour1); print '<br>'; print_r($status); exit();
            if($shour[1]<1)
            {
              $minute1=$shour[1]*-1;
            }
            else
            {
              $minute1=$shour[1];
            }

            //print_r($minute1);
            if($this->count_digit($hour1)==1)
            {
              $hour1='0'.$hour1;
            }
            else
            {
              $hour1=$hour1;
            } 

             if($this->count_digit($minute1)==1)
            {
              $minute1='0'.$minute1;
            }
            else
            {
              $minute1=$minute1;
            }


            $total=$hour1.'.'.$minute1;
            if($status==1)
            {
              $total='-'.$total;
            }
            else
            {
              $total=$total;
            }
            //print_r('total=');print_r($total); print_r('<br>'); exit();
            return $total;
            exit();
}

public function findAdditionlHoursBybankHoliday($payroll,$params)
{
      $additional_hours_day=$this->Split_payroll_model->findAdditionlHoursBybankHoliday($payroll,$params);
     
      // print_r($additional_hours_day);
      // print_r(count($additional_hours_day));
      // print '<br>';
      $sum='00.00.00';
      foreach ($additional_hours_day as $value) {
        //print_r($value['extrahour']); print '<br>';

            $hour_new=$value['extrahour']; //print 'hour_new'; print_r($hour_new); print '<br>';

                                if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }else
                                {
                                  $sum=$sum;
                                }

                                if(strpos($hour_new, "-") !== false){
                                $shour = explode(":",$hour_new);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }
                                else
                                {
                                  $hour=$hour_new;
                                }

                          // print_r('sum-'); print_r($sum); print '<br>';
                          // print_r('hour-');
                          // print_r($hour); print '<br>'; //exit();

                            //$sum = $this->time_to_decimal($sum);  print_r('sumnew-'); print_r($sum);
                            $b = $this->new_time_to_decimal($hour);  //print_r('hournew-');print_r($b); print_r('<br>');print_r('<br>');print_r('<br>');
                            $bank_holiday_status=Checkbankholiday($value['date']); //print $bank_holiday_status;
                            if($bank_holiday_status=='true')
                            $sum= $sum + $b; 


            } 
            $total=$this->decimal_to_time($sum);   

             //$total='00.30.00';
            $shour = explode(".",$total);
            //print '<pre>';
            //print_r($shour); print '<br>';//exit();

            if($shour[0]<0)
            {
              $hour1=$shour[0]*-1;
              $status=1; 
            }
            else
            {
              $hour1=$shour[0]; 
              $status=2;
            }
            //print_r($hour1); print '<br>'; print_r($status); exit();
            if($shour[1]<1)
            {
              $minute1=$shour[1]*-1;
            }
            else
            {
              $minute1=$shour[1];
            }

            //print_r($minute1);
            if($this->count_digit($hour1)==1)
            {
              $hour1='0'.$hour1;
            }
            else
            {
              $hour1=$hour1;
            } 

             if($this->count_digit($minute1)==1)
            {
              $minute1='0'.$minute1;
            }
            else
            {
              $minute1=$minute1;
            }


            $total=$hour1.'.'.$minute1;
            if($status==1)
            {
              $total='-'.$total;
            }
            else
            {
              $total=$total;
            }
            //print_r('total=');print_r($total); print_r('<br>'); exit();
            return $total;
            exit();
}


public function findAdditionlHoursByDay($payroll,$params)
{
      $additional_hours_day=$this->Split_payroll_model->findAdditionlHoursByDay($payroll,$params);
     
      //print_r($additional_hours_day);
      //print_r(count($additional_hours_day));
      //print '<br>';
      $sum='00.00.00';
      $day_bank='00.00.00';
      foreach ($additional_hours_day as $value) {
        $day_string_new = substr(strtolower($value['day']), 0, 2);
        if($day_string_new != 'su' && $day_string_new != 'sa'){
        //print_r($value['extrahour']); print '<br>';

        

            $hour_new=$value['extrahour']; //print 'hour_new'; print_r($hour_new); print '<br>';

                                if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }else
                                {
                                  $sum=$sum;
                                }

                                if(strpos($hour_new, "-") !== false){
                                $shour = explode(":",$hour_new);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }
                                else
                                {
                                  $hour=$hour_new;
                                }

                          // print_r('sum-'); print_r($sum); print '<br>';
                          //print_r('hour-');
                          //print_r($hour); print '<br>'; //exit();

                            //$sum = $this->time_to_decimal($sum);  print_r('sumnew-'); print_r($sum);
                            $b = $this->new_time_to_decimal($hour);  //print_r('hournew-');print_r($b); print_r('<br>');print_r('<br>');print_r('<br>');
                            $bank_holiday_status=Checkbankholiday($value['date']); //print $bank_holiday_status;
                            if($bank_holiday_status=='false')
                            $sum= $sum + $b; 


                }
            } 
            $total=$this->decimal_to_time($sum);   

             //$total='00.30.00';
            $shour = explode(".",$total);
            //print '<pre>';
            //print_r($shour); print '<br>';//exit();

            if($shour[0]<0)
            {
              $hour1=$shour[0]*-1;
              $status=1; 
            }
            else
            {
              $hour1=$shour[0]; 
              $status=2;
            }
            //print_r($hour1); print '<br>'; print_r($status); exit();
            if($shour[1]<1)
            {
              $minute1=$shour[1]*-1;
            }
            else
            {
              $minute1=$shour[1];
            }

            //print_r($minute1);
            if($this->count_digit($hour1)==1)
            {
              $hour1='0'.$hour1;
            }
            else
            {
              $hour1=$hour1;
            } 

             if($this->count_digit($minute1)==1)
            {
              $minute1='0'.$minute1;
            }
            else
            {
              $minute1=$minute1;
            }


            $total=$hour1.'.'.$minute1;
            if($status==1)
            {
              $total='-'.$total;
            }
            else
            {
              $total=$total;
            }
            //print_r('total=');print_r($total); print_r('<br>'); exit();
            return $total;
            exit();
}

public function findAdditionlHoursBysunDay($payroll,$params)
{
      $additional_hours_sunday=$this->Split_payroll_model->findAdditionlHoursBysunDay($payroll,$params);
     
      // print_r($additional_hours_sunday);
      // print_r(count($additional_hours_sunday));
      // print '<br>';
      $sum='00.00.00';
      foreach ($additional_hours_sunday as $value) {
        //print_r($value['extrahour']); print '<br>';

            $hour_new=$value['extrahour']; //print 'hour_new'; print_r($hour_new); print '<br>';

                                if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }else
                                {
                                  $sum=$sum;
                                }

                                if(strpos($hour_new, "-") !== false){
                                $shour = explode(":",$hour_new);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }
                                else
                                {
                                  $hour=$hour_new;
                                }

                          // print_r('sum-'); print_r($sum); print '<br>';
                          //print_r('hour-');
                          //print_r($hour); print '<br>'; //exit();

                            //$sum = $this->time_to_decimal($sum);  print_r('sumnew-'); print_r($sum);
                            $b = $this->new_time_to_decimal($hour);  //print_r('hournew-');print_r($b); print_r('<br>');print_r('<br>');print_r('<br>');
                            $bank_holiday_status=Checkbankholiday($value['date']); //print $bank_holiday_status;
                            if($bank_holiday_status=='false')
                            $sum= $sum + $b; 

            }
            $total=$this->decimal_to_time($sum); 

            $shour = explode(".",$total);
            //print_r($shour);
            if($shour[0]<0)
            {
              $hour1=$shour[0]*-1;
              $status=1;
            }
            else
            {
              $hour1=$shour[0];
              $status=2;
            }
            //print_r($hour1);
            if($shour[1]<1)
            {
              $minute1=$shour[1]*-1;
            }
            else
            {
              $minute1=$shour[1];
            }

            //print_r($minute1);
            if($this->count_digit($hour1)==1)
            {
              $hour1='0'.$hour1;
            }
            else
            {
              $hour1=$hour1;
            } 

             if($this->count_digit($minute1)==1)
            {
              $minute1='0'.$minute1;
            }
            else
            {
              $minute1=$minute1;
            }


            $total=$hour1.'.'.$minute1;
            if($status==1)
            {
              $total='-'.$total;
            }
            else
            {
              $total=$total;
            }
           //print_r('total-');print_r($total); print_r('<br>'); exit();
            return $total;
            exit();
}

public function findAdditionlHoursBysaturDay($payroll,$params)
{
      $additional_hours_day=$this->Split_payroll_model->findAdditionlHoursBysaturDay($payroll,$params);
     
      //print_r($additional_hours_day);
      //print_r(count($additional_hours_day));
      //print '<br>';
      $sum='00.00.00';
      foreach ($additional_hours_day as $value) {
        //print_r($value['extrahour']); print '<br>';

            $hour_new=$value['extrahour']; //print 'hour_new'; print_r($hour_new); print '<br>';

                                if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }else
                                {
                                  $sum=$sum;
                                }

                                if(strpos($hour_new, "-") !== false){
                                $shour = explode(":",$hour_new);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }
                                else
                                {
                                  $hour=$hour_new;
                                }

                          // print_r('sum-'); print_r($sum); print '<br>';
                          //print_r('hour-');
                          //print_r($hour); print '<br>'; //exit();

                            //$sum = $this->time_to_decimal($sum);  print_r('sumnew-'); print_r($sum);
                            $b = $this->new_time_to_decimal($hour);  //print_r('hournew-');print_r($b); print_r('<br>');print_r('<br>');print_r('<br>');
                            $bank_holiday_status=Checkbankholiday($value['date']); //print $bank_holiday_status;
                            if($bank_holiday_status=='false')
                            $sum= $sum + $b; 

            }
            $total=$this->decimal_to_time($sum); 
            $shour = explode(".",$total);
            //print_r($shour);
            if($shour[0]<0)
            {
              $hour1=$shour[0]*-1;
              $status=1;
            }
            else
            {
              $hour1=$shour[0];
              $status=2;
            }
            //print_r($hour1);
            if($shour[1]<1)
            {
              $minute1=$shour[1]*-1;
            }
            else
            {
              $minute1=$shour[1];
            }

            //print_r($minute1);
            if($this->count_digit($hour1)==1)
            {
              $hour1='0'.$hour1;
            }
            else
            {
              $hour1=$hour1;
            } 

             if($this->count_digit($minute1)==1)
            {
              $minute1='0'.$minute1;
            }
            else
            {
              $minute1=$minute1;
            }


            $total=$hour1.'.'.$minute1;
            if($status==1)
            {
              $total='-'.$total;
            }
            else
            {
              $total=$total;
            }
            //print_r('total-');print_r($total); print_r('<br>'); exit();
            return $total;
            exit();
}


//night additional

public function findAdditionlHoursByWeekday($payroll,$params)
{
      $additional_hours_day=$this->Split_payroll_model->findAdditionlHoursByWeekday($payroll,$params);
     
      //print_r($additional_hours_day);
      //print_r(count($additional_hours_day));
      //print '<br>';
      $sum='00.00.00';
      foreach ($additional_hours_day as $value) {
        //print_r($value['extrahour']); print '<br>';

            $hour_new=$value['extrahour']; //print 'hour_new'; print_r($hour_new); print '<br>';

                                if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }else
                                {
                                  $sum=$sum;
                                }

                                if(strpos($hour_new, "-") !== false){
                                $shour = explode(":",$hour_new);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }
                                else
                                {
                                  $hour=$hour_new;
                                }

                          // print_r('sum-'); print_r($sum); print '<br>';
                          //print_r('hour-');
                          //print_r($hour); print '<br>'; //exit();

                            //$sum = $this->time_to_decimal($sum);  print_r('sumnew-'); print_r($sum);
                            $b = $this->new_time_to_decimal($hour);  //print_r('hournew-');print_r($b); print_r('<br>');print_r('<br>');print_r('<br>');
                            $bank_holiday_status=Checkbankholiday($value['date']); //print $bank_holiday_status;
                            if($bank_holiday_status=='false')
                            $sum= $sum + $b; 

            }
            $total=$this->decimal_to_time($sum);   

            //$total='02.39.00';
            $shour = explode(".",$total);
            //print '<pre>';
            //print_r($shour); print '<br>';

            if($shour[0]<0)
            {
              $hour1=$shour[0]*-1;
              $status=1; 
            }
            else
            {
              $hour1=$shour[0]; 
              $status=2;
            }
            //print_r($hour1);
            if($shour[1]<1)
            {
              $minute1=$shour[1]*-1;
            }
            else
            {
              $minute1=$shour[1];
            }

            //print_r($minute1);
            if($this->count_digit($hour1)==1)
            {
              $hour1='0'.$hour1;
            }
            else
            {
              $hour1=$hour1;
            } 

             if($this->count_digit($minute1)==1)
            {
              $minute1='0'.$minute1;
            }
            else
            {
              $minute1=$minute1;
            }


            $total=$hour1.'.'.$minute1;
            if($status==1)
            {
              $total='-'.$total;
            }
            else
            {
              $total=$total;
            }
            //print_r('total-');print_r($total); print_r('<br>'); exit();
            return $total;
            exit();
}

public function findAdditionlHoursByWeekend($payroll,$params)
{
      $additional_hours_day=$this->Split_payroll_model->findAdditionlHoursByWeekend($payroll,$params);
     
      //print_r($additional_hours_day);
      //print_r(count($additional_hours_day));
      //print '<br>';
      $sum='00.00.00';
      foreach ($additional_hours_day as $value) {
        //print_r($value['extrahour']); print '<br>';

            $hour_new=$value['extrahour']; //print 'hour_new'; print_r($hour_new); print '<br>';

                                if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }else
                                {
                                  $sum=$sum;
                                }

                                if(strpos($hour_new, "-") !== false){
                                $shour = explode(":",$hour_new);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }
                                else
                                {
                                  $hour=$hour_new;
                                }

                          // print_r('sum-'); print_r($sum); print '<br>';
                          //print_r('hour-');
                          //print_r($hour); print '<br>'; //exit();

                            //$sum = $this->time_to_decimal($sum);  print_r('sumnew-'); print_r($sum);
                            $b = $this->new_time_to_decimal($hour);  //print_r('hournew-');print_r($b); print_r('<br>');print_r('<br>');print_r('<br>');
                            $bank_holiday_status=Checkbankholiday($value['date']); //print $bank_holiday_status;
                            if($bank_holiday_status=='false')
                            $sum= $sum + $b; 

            }
            $total=$this->decimal_to_time($sum);   

            //$total='02.39.00';
            $shour = explode(".",$total);
            //print '<pre>';
            //print_r($shour); print '<br>';

            if($shour[0]<0)
            {
              $hour1=$shour[0]*-1;
              $status=1; 
            }
            else
            {
              $hour1=$shour[0]; 
              $status=2;
            }
            //print_r($hour1);
            if($shour[1]<1)
            {
              $minute1=$shour[1]*-1;
            }
            else
            {
              $minute1=$shour[1];
            }

            //print_r($minute1);
            if($this->count_digit($hour1)==1)
            {
              $hour1='0'.$hour1;
            }
            else
            {
              $hour1=$hour1;
            } 

             if($this->count_digit($minute1)==1)
            {
              $minute1='0'.$minute1;
            }
            else
            {
              $minute1=$minute1;
            }


            $total=$hour1.'.'.$minute1;
            if($status==1)
            {
              $total='-'.$total;
            }
            else
            {
              $total=$total;
            }
            //print_r('total-');print_r($total); print_r('<br>'); exit();
            return $total;
            exit();
}


function count_digit($number) {
  return strlen($number);
}




    public function findNightshiftDetails($user_id,$params)
    { 
    	$end=date('Y-m-d', strtotime("+1 day", strtotime($params['end_date'])));
    	//
    	$tmp=array('start_date'=>$params['start_date'],'end_date'=>$end);
    	//print_r($tmp);exit();
      //$user_id=796;
       $shift=$this->Split_payroll_model->findNightShiftdetails($user_id,$tmp); 
       //print '<pre>';
       //print_r($shift);//exit();
       if(!empty($shift))
       {    
       	    $weekdaynight=0;
       	    $weekendnight=0;

	        for($i=0;$i<count($shift);$i++)
	        {
	        	if($shift[$i]['shift_id']=='16' || $shift[$i+1]['shift_id']=='16')
	        	{ //print_r('hiii'); print '<br>';
          //print_r($shift[$i]['time_to']); print '<br>';
          //print_r($shift[$i+1]['time_to']); print '<br>';
		        	       if($shift[$i]['time_to']=='00:00:00' && !empty($shift[$i+1]['time_to']))
	                    { //print 'hii1';print '<br>';
	                        $shift[$i]['time_to']= $shift[$i+1]['time_to'];
	                        $date=$shift[$i]['date'];
	                    }
	                    else
	                    { //print 'hii2';print '<br>';
	                        $shift[$i]['time_to']= $shift[$i]['time_to'];
	                        $date=$shift[$i]['date'];
	                    }
                      //print_r($date);print '<br>';
	                    if($shift[$i]['time_from']!='00:00:00')
	                    { 
	                    	$dates=getShiftdetails($shift[$i]['user_id'],$shift[$i]['date']);
                            if($shift[$i]['time_from'] < $dates[0]['start_time']) 
                            {  
                                $stime=$dates[0]['start_time'];
                            }else{  
                                $stime=$shift[$i]['time_from'];
                            }

                            if($shift[$i]['time_to']>$dates[0]['end_time']) 
                            {
                                $etime=$dates[0]['end_time'];
                            }else{
                                $etime=$shift[$i]['time_to'];
                            }
                            //print_r("stime-".$stime);
                            //print_r("etime-".$etime."<br>");


	      //               	$to_time = strtotime($etime);
							// $from_time = strtotime($stime);
							// $time_diff = $to_time - $from_time; 
							// $time=gmdate('H:i', $time_diff);
                            $time=$this->findDifferenceodtime($stime,$etime,$shift[$i]['date'],$shift[$i+1]['date']);
                            // print_r($time);
                            $time_split = explode(":",$time); //print_r($time_split);
                            $time_new = $time_split[0].".".$time_split[1];
                             
							$shift_hour=getShiftHours($shift[$i]['user_id'],$shift[$i]['date']);
		                    //print_r("<pre>");
		                   // print_r("time_from-".$shift[$i]['time_from']." "."time_to-".$shift[$i]['time_to']."<br>");
		                   // print_r($time."<br>".$shift[$i]['day']."<br>");
                        //print_r($shift[$i]['date']."<br>");
		                    //print_r("shift-".$shift_hour);
                        //print '<br>';
		                    if($shift[$i]['time_to']=='00:00:00')
	                    	{
	                    		$day='00.00';
                          $break='00.00.00';
	                    		
	                    	}
	                    	else
	                    	{ 
                          $break=$this->Split_payroll_model->getBreakhoursbynightshift($shift[$i]['user_id'],$shift[$i]['date']);
	                    		$day=$time_new;
                               
	                    	}

                            
                        //print_r($day.'-'.$shift_hour); print '<br>';
                        if($shift_hour!='')
                        { //print 'shift is not null'; print '<br>';
                          if($day>$shift_hour)
                          {
                            $hour=$shift_hour.".00";
                          }
                          else
                          {
                            $hour=$day.".00";
                          }
                        }
                        else
                        { //print 'shift is  null'; print '<br>';
                            $hour=$day.".00";
                        }

	                    	
                            

                             $sum=$hour;
                             $hour=$break;
 


                            if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }
 //print_r('sum-'.$sum);
                                if(strpos($hour, "-") !== false){
                                $shour = explode(":",$hour);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }

                            $sum = $this->time_to_decimal($sum);
                            $b = $this->time_to_decimal($hour); //print_r($b);exit();
                            $sum= $sum - $b;
                            $total=$this->decimal_to_time($sum);
                            //print_r("<br>");
                            //print_r('total-'.$total);print '<br>';


	                    	if($shift[$i]['day']=='Su' || $shift[$i]['day']=='Sa')
	                    	{
	                    		$weekendnight=$weekendnight+$total;
	                    		// print_r('Saturdy or Sunday-'.$weekendnight);print_r("<br>");

	                    	}
	                    	else
	                    	{
	                    		$weekdaynight=$weekdaynight+$total;
	                    		//print_r('Week days-'.$weekdaynight);print_r("<br>");
	                    	}

	                    	//print_r($weektimes);
	                    	
		                    //print '<---------------->';print '<br>'; 
		                 

	                	}
	            }
	        } 
	         $result=array('weekday'=>$weekdaynight,'weekendnight'=>$weekendnight);
            //print_r($result);exit();
	     
  		}
          return $result;
    }

public function findDifferenceodtime($firsttime,$lasttime,$date_from,$date_to)
{
        $ftime=date('H:i',strtotime($firsttime));  //print_r($ftime); print_r('<br>');
        $ltime=date('H:i',strtotime($lasttime));   //print_r($ltime);  print_r('<br>');
        $from_time1=$date_from.' '.$ftime;  
        $to_time1=$date_to.' '.$ltime;  

        $date_a = new DateTime($from_time1);
        $date_b = new DateTime($to_time1);

        $interval = date_diff($date_a,$date_b);

        $time2=$interval->format('%h:%i');
        return $time2;
}

public function findDifference($firsttime,$lasttime)
  {  
    // print "<pre>";
    // print_r($firsttime); print '<br>';
    // print_r($lasttime); print '<br>';
        $ftime=date('H:i',strtotime($firsttime)); 
        $ltime=date('H:i',strtotime($lasttime));  

        $startTime = new DateTime($ftime);  //print_r($startTime); print '<br>';
        $endTime = new DateTime($ltime);  //print_r($endTime); print '<br>';
        $duration = $startTime->diff($endTime); //$duration is a DateInterval object
        $time2=$duration->format("%H:%I:%S");
        return $time2;
  }

public function new_time_to_decimal($time) {  
$timeArr = explode(':', $time);

$decTime = ($timeArr[0]*60) + ($timeArr[1]) + ($timeArr[2]/60);
//print_r($decTime);exit();

return $decTime;
}


public function time_to_decimal($time) {  
$timeArr = explode('.', $time);

$decTime = ($timeArr[0]*60) + ($timeArr[1]) + ($timeArr[2]/60);
//print_r($decTime);exit();

return $decTime;
}

public function decimal_to_time($decimal) {

if(strpos($decimal, "-") !== false){ //print "hii";
$hours = ceil((int)$decimal / 60); 
$minutes = floor((int)$decimal % 60);
$seconds = $decimal - (int)$decimal; 
$seconds = round($seconds * 60); 
}
else{
$hours = floor((int)$decimal / 60);
$minutes = floor((int)$decimal % 60);
$seconds = $decimal - (int)$decimal; 
$seconds = round($seconds * 60); 
}



return str_pad($hours, 2, "0", STR_PAD_LEFT) . "." . str_pad($minutes, 2, "0", STR_PAD_LEFT) . "." . str_pad($seconds, 2, "0", STR_PAD_LEFT);

}

    public function getBreakhours($user_id,$params)
    {
        //print_r($user_id);exit();
        //$user_id=737;
        $hour=$this->Split_payroll_model->getBreakhours($user_id,$params);
        // print_r($hour);
        // exit();
        $day_unpaid=0;
        $sun_unpaid=0;
        $sat_unpaid=0;
        $nightday_unpaid=0;
        $nightend_unpaid=0;

        foreach ($hour as  $h) { //print_r($h);
            if($h['logid']!='')
            {
                if($h['shift_category']==2)
                {
                     if($h['day']=='Su' || $h['day']=='Sa')
                     {
                        $nightend_unpaid=$nightend_unpaid+settimeformat($h['unpaid_break_hours']);
                     }
                     else
                     {
                        $nightday_unpaid=$nightday_unpaid+settimeformat($h['unpaid_break_hours']);
                     }

                }
                else
                {
                    $timelog=$this->Split_payroll_model->checktimelog($user_id,$h['date']);

                    if($timelog[0]['fromtime']!='00:00:00' && $timelog[0]['totime']!='00:00:00') 
                    { //print 'hii';
                        if($h['day']=='Su')
                        { 
                           $sun_unpaid=$sun_unpaid+settimeformat($h['unpaid_break_hours']);
                        }
                        else if($h['day']=='Sa')
                        { 
                           $sat_unpaid=$sat_unpaid+settimeformat($h['unpaid_break_hours']);
                        }
                        else
                        {
                           $day_unpaid=$day_unpaid+settimeformat($h['unpaid_break_hours']);
                        }
                    }
                    else
                    { //print 'hello';

                      if($h['day']=='Su')
                        { 
                           $sun_unpaid=$sun_unpaid;
                        }
                        else if($h['day']=='Sa')
                        { 
                           $sat_unpaid=$sat_unpaid;
                        }
                        else
                        {
                           $day_unpaid=$day_unpaid;
                        }


                    }

                }
            }
            
           
        } 
        $result=array('day_unpaid'=>$day_unpaid,'sat_unpaid'=>$sat_unpaid,'sun_unpaid'=>$sun_unpaid,'nightday_unpaid'=>$nightday_unpaid,'nightend_unpaid'=>$nightend_unpaid);
        //print_r($result);exit();

        return $result;
         
    }


    public function insertcomments()
    {

    	$params=array(); 
        $params['comment']=$this->input->post('comment');
        $params['user_id']=$this->input->post('user_id');
        $params['month']=$this->input->post('month');
        $params['year']=$this->input->post('year'); 
        $params['creation_date']=date('Y-m-d H:i:s');
        $params['created_by']=$this->session->userdata('user_id');
        $status=$this->Split_payroll_model->CheckforUserPayrollComment($params);
        //print_r($status);exit();
        if($status==0)
        { //print_r('hiii');exit();
        	//insert
        	 $result=$this->Split_payroll_model->insertcomments($params);
        }
        else
        { //print_r('helo');exit();
        	//updattion
        	 $result=$this->Split_payroll_model->updatecomments($params,$status);
        }

        header('Content-Type: application/json');
        echo json_encode(array('status' => $result));
        exit();
    } 

}
?>