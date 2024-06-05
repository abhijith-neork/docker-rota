<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
  /**
   * Get All Data from this method.
   *
   * @return Response
  */
  public function __construct() {
    Parent::__construct(); 
    if ($this->session->userdata('user_type') ==2)
    {
      $this->auth->logout();
      unset($params);
      $this->_login(INVALID_LOGIN);
    }
    $this->load->model('Dashboard_model');
    $this->load->model('Shift_model');
    $this->load->helper('form');
  }
  public function index()
  { 
    $this->auth->restrict('Admin.Dashboard.View');
    $result = array(); 
    $response = array();
    $posts = array();
    $this->load->view('includes/home_header'); 
    $data=array(); 
    $response['error']='';
    $resources=array();
    $response['posts'] = '';
    $response['rotaId'] ='';  
    $this->load->model('Unit_model'); 
    $params=array();
    $posts=array();
    $param=array();
    $shift_data_array=array();
    $date_array = array();
    if($this->input->post("month")!='')
        $smonth = $this->input->post("month");
    else 
        $smonth = date('m');
    
    if($this->input->post("month")!='')
        $syear = $this->input->post("year");
    else
        $syear = date('Y');
    $start_year=$syear;  
    $start_month=$smonth;   
    $params['month']=$smonth;
    $params['year']=$syear;
    $params['unit']=$this->input->post("unitdata");
    // if($this->session->userdata('user_id')==1) // super admin
    // { 
   //  $response['unit'] = $this->User_model->fetchCategoryTree();
    $response['unit'] = $this->User_model->fetchunit('');;
      //$response['unit'] = $this->User_model->fetchunit('');   
    // }
    // else
    // {  //others
    //   $response['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
    // }
    $param['start_date']=$start_year.'-'.$start_month.'-'.'01'; //print_r($param['start_date']);
    $param['end_date'] = date("Y-m-t", strtotime($param['start_date'])); //print_r($param['end_date']);
    $response['start_date']=date("d-m-Y", strtotime($param['start_date']));  
    $response['end_date']=date("d-m-Y", strtotime($param['end_date'])); 
    $date1=date_create($response['end_date']);
    $date2=date_create($response['start_date']);
    $diff = date_diff($date1,$date2);
    $days = $diff->format("%a")+1;  
    if($days>=28) //if month not is february set week as 5
    {
      $response['week']=5;
    }
    else
    {
      $response['week']=4;
    }

    // if(date('m')==$start_month)
    // {
    //   $eventstart=date('Y-m-d');   
    //   $eventstart_time = strtotime($eventstart . ' -7 day');
    //   $response['default_date']=date("Y-m-d",$eventstart_time); 
    // }
    // else
    // {
 
      $response['default_date']=date("Y-m-d", strtotime("first sunday of".$start_year.'-'.$start_month));
 
 
     //setting default date
    // }  
      
    // if($this->session->userdata('user_id')==1) // unit loading (super admin)
    // {
     $resource=$this->User_model->fetchCategoryTree();
     // $resource= $this->User_model->fetchunit('');     
    // }
    // else
    // {
    //   $resource=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
    // }
    if($params['unit']=='')   //no unit selected(first time loading)
    { 
  $unit=$this->Dashboard_model->fetchunit();  //units
      foreach ($unit as $value) {
        if($value['parent_unit']==0){ //only parent unit
               $resources[]=array(
          "title"       =>  $value['unit_name'],
          "id"          =>  $value['id'] 
        );
        }
     
      }
      foreach ($resource as $source)
      {
        $unit=$source['id'];
        $parent=0;
        $unit_rota = $this->checkUnitHasRota($unit); 
        $params['unit'] = $source['id'];
        if(count($unit_rota)>0){  
        $rota=$this->Dashboard_model->findRotaSettingsbyunit1($params);
          if(count($rota)>0){  
            foreach ($rota as $rot) {
            $isSub = $this->Unit_model->returnParentUnit($source['id']);
            //Added by chinchu
            $sub_units = array();
            $sub_units = $this->Unit_model->findsubunits($isSub);
            $sub_units_array = array();
            if(count($sub_units) > 0){
              for($i=0;$i<count($sub_units);$i++){
                array_push($sub_units_array, $sub_units[$i]['id']);
              }
            }else{
              array_push($sub_units_array, $source['id']);
            }
            $rota_ids_of_subunits = $this->Dashboard_model->findRotaIds($sub_units_array);
            $date_array = $this->getDatesFromRange($rot['start_date'],$rot['end_date']);    //print_r("<pre>"); print_r($date_array); 
            $count = count($date_array);  
            for($i=0;$i<$count;$i++){  
              $shift_data=$this->Dashboard_model->getShiftDatasOfSubunits($rota_ids_of_subunits,$date_array[$i]);  
              if($isSub==0){
                array_push($shift_data_array, $shift_data);  
                $total_settings_values = $this->Dashboard_model->findRotaSettingsSum($isSub,$date_array[$i],$params);
               $day_shift='Day shift minimum'.':'.' '.$total_settings_values['rotday_shift_min'].','.'Day shift maximum'.':'.' '.$total_settings_values['rotday_shift_max'];
               $night_shift='Night shift minimum'.':'.' '.$total_settings_values['rotnight_shift_min'].','.'Night shift maximum'.':'.' '.$total_settings_values['rotnight_shift_max'];
               $nurse_day_count='Nurse day count'.':'.' '.$total_settings_values['rotnurse_day_count'];
               $nurse_night_count='Nurse night count'.':'.' '.$total_settings_values['rotnurse_night_count'];

               $title = '<div data_settings="'.$total_settings_values['rotday_shift_min'].'_'.$total_settings_values['rotday_shift_max'].'_'.$total_settings_values['rotnight_shift_min'].'_'.$total_settings_values['rotnight_shift_max'].'_'.$total_settings_values['rotnurse_day_count'].'_'.$total_settings_values['rotnurse_night_count'].'_'.$isSub.'" class="shift_'.$date_array[$i].'_'.$isSub.'">'.$this->createHtml($day_shift,$night_shift,$nurse_day_count,$nurse_night_count).'<div>';
                $posts[]=array(
                  "resourceId"   => $source['id'],
                  "title"        => $title,
                  "start"        => $date_array[$i],
                  "end"          => $date_array[$i],
                  "shift_id"     => $date_array['shift_id']
                );
              }
              else{
                //Added by chinchu
                array_push($shift_data_array, $shift_data);
                $inArr = $this->multi_array_search( $posts , array('start' => $date_array[$i], 'resourceId' => $isSub)); 
                if (count( $inArr)==0) { 
                  $total_settings_values = $this->Dashboard_model->findRotaSettingsSum($isSub,$date_array[$i],$params);
                  $day_shift='Day shift minimum'.':'.' '.$total_settings_values['rotday_shift_min'].','.'Day shift maximum'.':'.' '.$total_settings_values['rotday_shift_max'];
                  $night_shift='Night shift minimum'.':'.' '.$total_settings_values['rotnight_shift_min'].','.'Night shift maximum'.':'.' '.$total_settings_values['rotnight_shift_max'];
                  $nurse_day_count='Nurse day count'.':'.' '.$total_settings_values['rotnurse_day_count'];
                  $nurse_night_count='Nurse night count'.':'.' '.$total_settings_values['rotnurse_night_count'];
                  $title = '<div data_settings="'.$total_settings_values['rotday_shift_min'].'_'.$total_settings_values['rotday_shift_max'].'_'.$total_settings_values['rotnight_shift_min'].'_'.$total_settings_values['rotnight_shift_max'].'_'.$total_settings_values['rotnurse_day_count'].'_'.$total_settings_values['rotnurse_night_count'].'_'.$isSub.'" class="shift_'.$date_array[$i].'_'.$isSub.'">'.$this->createHtml($day_shift,$night_shift,$nurse_day_count,$nurse_night_count).'<div>';
                  $posts[]=array(
                    "resourceId"   => $isSub,
                    "title"        => $title,
                    "start"        => $date_array[$i],
                    "end"          => $date_array[$i],
                  );
                }
              }
              $parentUnit = $isSub;
              $dateChk = $date_array[$i];
              } 
            }
          } 
        }  
      }
    }
    else{ // if a unit is selected from dropdown


 $resource=$this->User_model->fetchCategoryTree();

 //check if subunits are there, if sub units load all sub units
  $haveSubunits=$this->Dashboard_model->findBranches($params['unit']);
  if(count($haveSubunits) >0 ){
     $resource=$haveSubunits;
  }else{
     $resource=$this->Dashboard_model->getunitDetails($params['unit']);

   //else load same unit
  }
  $unit=$this->Dashboard_model->fetchunit();  //units
      foreach ($unit as $value) {
        if($value['parent_unit']==0){ //only parent unit
               $resources[]=array(
          "title"       =>  $value['unit_name'],
          "id"          =>  $value['id'] 
        );
        }
     
      }

      foreach ($resource as $source)
      {
      /*  if($source['id']!=1){
          $resources[]=array(
            "title"       =>  $source['unit_name'],
            "id"          =>  $source['id'] 
          );
        }*/
        // $parent_unit=$this->checkUnitHasParentunit($source['id']);  //checking parent unit
        // if($parent_unit[0]['parent_unit']==0)
        // { //no parent unit
          $unit=$source['id'];
          $parent=0;
        // }
        // else
        // {
        //   $unit=$parent_unit[0]['parent_unit'];
        //   $parent=1;
        // }
         // print_r($unit);

        // print_r("<pre>");
        // print_r($unit);
        $unit_rota = $this->checkUnitHasRota($unit); 
        //print_r("<pre>"); print_r($unit_rota);//exit();
        $params['unit'] = $source['id'];
        if(count($unit_rota)>0){  

          $rota=$this->Dashboard_model->findRotaSettingsbyunit1($params);   // print_r($rota); //exit();
        


          //print_r("<pre>");  print_r($rota);//exit();
          if(count($rota)>0){  

            foreach ($rota as $rot) {
            //check if subunit
            $isSub = $this->Unit_model->returnParentUnit($source['id']);
            //Added by chinchu
                      $sub_units = array();
                      $sub_units = $this->Unit_model->findsubunits($isSub);
                      $sub_units_array = array();
                      if(count($sub_units) > 0){
                        for($i=0;$i<count($sub_units);$i++){
                          array_push($sub_units_array, $sub_units[$i]['id']);
                        }
                      }else{
                        array_push($sub_units_array, $source['id']);
                      }

            $rota_ids_of_subunits = $this->Dashboard_model->findRotaIds($sub_units_array);
            
            $date_array = $this->getDatesFromRange($rot['start_date'],$rot['end_date']);    //print_r("<pre>"); print_r($date_array); 
            $count = count($date_array);  


              for($i=0;$i<$count;$i++){   //print_r("<pre>"); print_r($source['id']); 
              $shift_data=$this->Dashboard_model->getShiftDatasOfSubunits($rota_ids_of_subunits,$date_array[$i]);
                // $shift_data=$this->Dashboard_model->getShiftDatas1($rot['id'],$date_array[$i]);  
                //print_r("<pre>"); print_r($shift_data); 

                   if($isSub==0){
                        array_push($shift_data_array, $shift_data);
                         $total_settings_values = $this->Dashboard_model->findRotaSettingsSum($isSub,$date_array[$i],$params);
                         $day_shift='Day shift minimum'.':'.' '.$total_settings_values['rotday_shift_min'].','.'Day shift maximum'.':'.' '.$total_settings_values['rotday_shift_max'];
                         $night_shift='Night shift minimum'.':'.' '.$total_settings_values['rotnight_shift_min'].','.'Night shift maximum'.':'.' '.$total_settings_values['rotnight_shift_max'];
                         $nurse_day_count='Nurse day count'.':'.' '.$total_settings_values['rotnurse_day_count'];
                         $nurse_night_count='Nurse night count'.':'.' '.$total_settings_values['rotnurse_night_count'];

                         $title = '<div data_settings="'.$total_settings_values['rotday_shift_min'].'_'.$total_settings_values['rotday_shift_max'].'_'.$total_settings_values['rotnight_shift_min'].'_'.$total_settings_values['rotnight_shift_max'].'_'.$total_settings_values['rotnurse_day_count'].'_'.$total_settings_values['rotnurse_night_count'].'_'.$isSub.'" class="shift_'.$date_array[$i].'_'.$isSub.'">'.$this->createHtml($day_shift,$night_shift,$nurse_day_count,$nurse_night_count).'<div>';

                         $posts[]=array(
                          "resourceId"   => $source['id'],
                          "title"        => $title,
                          "start"        => $date_array[$i],
                          "end"          => $date_array[$i],
                          "shift_id"     => $date_array['shift_id']
                        );
                  }
                  else{
                      array_push($shift_data_array, $shift_data); 
                        $inArr = $this->multi_array_search( $posts , array('start' => $date_array[$i], 'resourceId' => $isSub));
                           if (count( $inArr)==0) {
                            $total_settings_values = $this->Dashboard_model->findRotaSettingsSum($isSub,$date_array[$i],$params);
                               $day_shift='Day shift minimum'.':'.' '.$total_settings_values['rotday_shift_min'].','.'Day shift maximum'.':'.' '.$total_settings_values['rotday_shift_max'];
                               $night_shift='Night shift minimum'.':'.' '.$total_settings_values['rotnight_shift_min'].','.'Night shift maximum'.':'.' '.$total_settings_values['rotnight_shift_max'];
                               $nurse_day_count='Nurse day count'.':'.' '.$total_settings_values['rotnurse_day_count'];
                               $nurse_night_count='Nurse night count'.':'.' '.$total_settings_values['rotnurse_night_count'];
                               $title = '<div data_settings="'.$total_settings_values['rotday_shift_min'].'_'.$total_settings_values['rotday_shift_max'].'_'.$total_settings_values['rotnight_shift_min'].'_'.$total_settings_values['rotnight_shift_max'].'_'.$total_settings_values['rotnurse_day_count'].'_'.$total_settings_values['rotnurse_night_count'].'_'.$isSub.'" class="shift_'.$date_array[$i].'_'.$isSub.'">'.$this->createHtml($day_shift,$night_shift,$nurse_day_count,$nurse_night_count).'<div>'; 
                                $posts[]=array(
                                          "resourceId"   => $isSub,
                                          "title"        => $title,
                                          "start"        => $date_array[$i],
                                          "end"          => $date_array[$i],
                                          // "shift_id"     => $date_array['shift_id']
                                        );
 
                             }
 


                  }
             
                      $parentUnit = $isSub;
                      $dateChk = $date_array[$i];
             
              } // for loop
            }
          } 
        }  
      }
      // exit();
    }
    $unit_name=$parent;
    $shift_cats = $this->getShiftDatas();
    $all_units = $this->Dashboard_model->fetchunit('');
    $response['shift_cats']=json_encode($shift_cats);
    $response['weekEvents']=json_encode($posts);  
    //print_r($response['weekEvents']);exit(); 
    $response['resources']=json_encode($resources);
    $response['shift_data']=json_encode($shift_data_array);  //print_r($response['shift_data']);exit();
    $response['all_units']=json_encode($all_units);
    $response['smonth']=$smonth;
    $response['syear']=$syear;
    $response['unit_name']=$unit_name;
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->load->model('Rota_model');
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/
    $this->load->view("admin/dashboard/dashboard",$response);
    $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar/fullcalendar.js','fullcalendar/scheduler.js','dashboard/dashboard.js');
    $this->load->view('includes/home_footer',$result);
  }
  public function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
      
    // Declare an empty array 
    $array = array(); 
      
    // Variable that store the date interval 
    // of period 1 day 
    $interval = new DateInterval('P1D'); 
  
    $realEnd = new DateTime($end); 
    $realEnd->add($interval); 
  
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
  
    // Use loop to store date into array 
    foreach($period as $date) {                  
        $array[] = $date->format($format);  
    } 
  
    // Return the array elements 
    return $array; 
} 
  public function checkUnitHasRota($id){
    $result = $this->Dashboard_model->checkUnitHasRota($id);
    return $result;
  }
  public function checkUnitHasSubUnit($id){
    $result = $this->Dashboard_model->checkUnitHasSubUnit($id);
    return $result;
  }
  public function checkUnitHasParentunit($id){
    $result = $this->Dashboard_model->checkUnitHasParentunit($id);
    return $result;
  }
  public function getShiftDatas(){
    $shifts =$this->Shift_model->getShift();
    $shift_cats = array();
    $break_hours = array();
    foreach ($shifts as $shift) {
      $shift_cats[$shift['id']]=$shift["shift_category"];
    }
    return $shift_cats;
  }
  public function createHtml($day_shift,$night_shift,$nurse_day_count,$nurse_night_count)
  {  
    $html = '';
    $html .= '<button style="width: 50%;font-size: 10px;color: white;height:20px;" class="day-color" disabled="" title="'.$day_shift.'"></button>';
    $html .= '<button style="width: 50%;font-size: 10px;color: white;height:20px;" class="night-color" disabled="" title="'.$night_shift.'"></button>'.'<br>';
    $html .= '<button style="width: 50%;font-size: 10px;color: white;height:20px;" class="day-nurse-color" disabled="" title="'.$nurse_day_count.'"></button>';
    $html .= '<button style="width: 50%;font-size: 10px;color: white;height:20px;" class="night-nurse-color" disabled="" title="'.$nurse_night_count.'"></button>';
    return $html;
  }

 public   function multi_array_search($array, $search)
    {

      // Create the result array
      $result = array();

      // Iterate over each array element
      foreach ($array as $key => $value)
      {

        // Iterate over each search condition
        foreach ($search as $k => $v)
        {

          // If the array element does not meet the search condition then continue to the next element
          if (!isset($value[$k]) || $value[$k] != $v)
          {
            continue 2;
          }

        }

        // Add the array element's key to the result array
        $result[] = $key;

      }
      return $result;
  }
}
?>