<?php
	class Dailysenses extends CI_Controller {
		  /**
     * Get All Data from this method.
     *
     * @return Response
    */
	    public function __construct() {
			Parent::__construct();
			if ($this->session->userdata('user_type') ==2){
			    $this->auth->logout();
			    
			    unset($params);
			    $this->_login(INVALID_LOGIN);
			}
			  
			$this->load->model('Dailysenses_model');
			$this->load->helper('form');
	    }
	    public function index()
	    {

			     $this->auth->restrict('Admin.Dailycensus.View');
		       $result = array(); 
		       $this->load->helper('user');
		       $this->load->view('includes/home_header'); 
		       $data=array(); 
		       $id=$this->session->userdata('user_id');   
		       // if($id==1)
		       // { 
		       // $data['user']=$this->Dailysenses_model->findallsenses(''); 
		       // }
		       // else
		       //  {
		       //   $unit=$this->session->userdata('unit_id');
		       //   $data['user']=$this->Dailysenses_model->findallsenses($unit);
		       //  } 

           if($this->input->post('start_date')=='')
           {
              $data['start_date'] =  '01'.'/'.date("m").'/'.date("Y");  
              $params['start_date'] = date('Y').'-'.date("m").'-'.'01';
           }
           else
           {
              $data['start_date'] = $this->input->post('start_date'); 
              $new_date=explode('/', $this->input->post('start_date'));
              $params['start_date'] = $new_date[2].'-'.$new_date[1].'-'.$new_date[0];        
           }

           if($this->input->post('end_date')=='')
           {
              $data['end_date'] =date('d/m/Y'); //print_r($params); exit();
              $params['end_date'] =date('Y-m-d'); //print_r($params); exit();
           }
           else
           {
             $data['end_date'] =$this->input->post('end_date'); //print_r($params); exit();  
             $new_date=explode('/', $this->input->post('end_date'));
             $params['end_date'] = $new_date[2].'-'.$new_date[1].'-'.$new_date[0];      
           }

 
                  $sub=$this->User_model->CheckuserUnit($id);
                  $unit=$this->User_model->findunitofuser($id); 
                  $parent=$this->User_model->Checkparent($unit[0]['unit_id']);  

		        //if($this->session->userdata('user_type')==1) //all super admin can access
                  if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                  {  
                   $data['user']=$this->Dailysenses_model->findallsenses('',$params); 
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

                        $data['user'] = $this->Dailysenses_model->fetchDailysenses($sub,$params);  
                    }
                    else
                    {    
                        $unit=$unit[0]['unit_id'];
		         		        $data['user']=$this->Dailysenses_model->findallsenses($unit,$params);
                    }

                  }
                  else
                  { 
                     $unit=$unit[0]['unit_id'];
		         	        $data['user']=$this->Dailysenses_model->findallsenses($unit,$params);
                    
                  }

		       //print_r($data['user']);exit();

            /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
            $this->load->model('Rota_model');
            $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
            /*End*/
		       $this->load->view("admin/dailysenses/listsenses",$data);
		       $result['js_to_load'] = array('user/dailysenses.js');
		       $this->load->view('includes/home_footer',$result);


	    }

	    public function addsenses()
	    {
	    	//$this->auth->restrict('Admin.Company.Edit'); 
	    $this->auth->restrict('Admin.Dailycensus.Add');
			$this->load->helper('form'); 	 
			$this->load->helper('form');
			$this->load->library('form_validation'); 
			$this->form_validation->set_rules('unitdata', 'unitdata', 'required'); 
			$this->form_validation->set_rules('comment', 'comment', 'required'); 
			$this->form_validation->set_rules('date', 'date', 'required'); 
			$data = array();

			$this->load->view('includes/home_header');
			if($this->form_validation->run()==FALSE)
			{  

				  $u_id=$this->session->userdata('user_id');  
                  $sub=$this->User_model->CheckuserUnit($u_id);
                  $unit=$this->User_model->findunitofuser($u_id); 
                  $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
                  //if($this->session->userdata('user_type')==1) //all super admin can access
                  if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
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

				  // if($this->session->userdata('user_id')==1)
			    // { 
			    //     $data['unit'] = $this->Dailysenses_model->fetchCategoryTree('');   
			    // }
			    //     else
			    // {
			    //     $data['unit']=$this->Dailysenses_model->findunitnameOfUser($this->session->userdata('user_id'));  
			    // }
			    $data['error']= '';
			    $data['date']=date('d/m/Y');
			    $this->load->view('admin/dailysenses/dailysenses',$data);
			}
			else
			{  

        //print_r($data_edited);exit();
				$date_daily=$this->input->post('date');  
		    $old_date = explode('/', $date_daily); 
				$new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
				//print_r($new_data);exit();
			    //Getting all input field values
			    $unit_id 	= $this->input->post('unitdata');
			    $user_id 	= $this->input->post('user');
			    $comment 	= $this->input->post('comment');
			    $date=$new_data;
			    //$date 	= $this->input->post('date');
			    
			    //Creating an array with new values
			    $datas	= array(
			        'unit_id'=>$unit_id,
			        'user_id'=>$user_id,
			        'date' =>$date,
			        'comment'=>$comment,
			        'creation_date'=>date('Y-m-d H:i:s'),
			        'created_userid'=>$this->session->userdata('user_id'),
			        'status' => 1
			    ); 
			    //print_r($datas);exit();
			    //Calling update function from company model
			    $result = $this->Dailysenses_model->insertsenses($datas);
          if($result=='true')
          {
             $title='Add Dailysenses';
             InsertEditedData($this->input->post(),$title);
          }
			    $this->session->set_flashdata('message', 'Added successfully.');
			    redirect('admin/Dailysenses/index');
			}
			$result['js_to_load'] = array('user/dailysenses.js');
			$this->load->view('includes/home_footer',$result);
	    }

	  public function editsenses()
    {  
   		//$this->auth->restrict('Admin.Dailycensus.Edit');
        $title_slug = $this->uri->segment(4); 
        //print_r($title_slug);exit();
		
		$this->load->helper('form');
		$this->load->library('form_validation'); 
		$this->form_validation->set_rules('unitdata', 'unitdata', 'required'); 
		$this->form_validation->set_rules('comment', 'comment', 'required'); 
		$this->form_validation->set_rules('date', 'date', 'required'); 
      	$data = array();

		$this->load->view('includes/home_header');

      if ($this->form_validation->run() == FALSE)
       {
       		$id= $this->input->post('id'); 
            if(empty($title_slug)) { $title_slug=$id;}
       	   
            $data['user']=$this->Dailysenses_model->finddailysenses($title_slug);    
            //print_r($data['user']);exit();
            //$data['userbyunit'] =$this->Dailysenses_model->finduserdata($unit_id); 
            $data['error']='';
            $this->load->view('admin/dailysenses/editsenses', $data);
             
        }
        else
         { 

         	 //print_r($this->input->post());exit();
         	  $date_daily=$this->input->post('date');  
		       	$old_date = explode('/', $date_daily); 
				    $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];

                $unit_id= $this->input->post('unit_id');
                $date=$new_data;
			    //$date 	= $this->input->post('date');
                $id= $this->input->post('id'); 
                $comment= $this->input->post('comment'); 
                $user_id= $this->input->post('user_id');

                 $datas	= array(
			        'unit_id'=>$unit_id,
			        'user_id'=>$user_id,
			        'date' =>$date,
			        'comment'=>$comment,
			        'creation_date'=>date('Y-m-d H:i:s'),
			        'created_userid'=>$this->session->userdata('user_id') 
			    );  
                 
                $result = $this->Dailysenses_model->update($id,$datas);
                if($result=='true')
                {
                   $title='Edit Dailysenses';
                   InsertEditedData($this->input->post(),$title);
                }
                $this->session->set_flashdata('message', 'Updated successfully.'); 
                redirect('admin/Dailysenses/index');
        }
            //$this->load->view('admin/editmenubanners', $data);
            // }
              
        $result['js_to_load'] = array('user/dailysenses.js');
			$this->load->view('includes/home_footer',$result);
         
        }


	public function finduserdata()
    {
    $this->load->model('Dailysenses_model');
    $unit_id=$this->input->post('unit_id'); 
    $unit_data =$this->Dailysenses_model->finduserdata($unit_id);   
    // foreach ($unit_data as $value) {
    //    $data['name']=$value['fname'].' '.$value['lname'];
    //    $data['id']=$value['user_id'];
    // }   
    echo json_encode($unit_data);
    }

    public function deleteddailysenses($id)
        {   
        //$this->auth->restrict('Admin.Dailycensus.Delete'); 
            try{
                       //print_r($id);exit();
                        $data = $this->Dailysenses_model->deleteddailysenses($id); 
                        if($data==1) 

                         $abc=array('id'=>'Dailysenses Id : '.$id);

                         $title='Delete Dailysenses';
                         InsertEditedData($abc,$title);
              
                        $this->session->set_flashdata('error',"0");
                        $this->session->set_flashdata('message', 'Deleted successfully.');
                        redirect('admin/Dailysenses/index');
               
                }

        catch (Exception $e){
            print_r($e->getMessage());
             }
        }

    }
?>