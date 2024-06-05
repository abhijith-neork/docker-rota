<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Payment extends CI_Controller {
   
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
        $this->load->model('Payment_model');
        $this->load->helper('form');
    }

     public function index()
    {
       $this->auth->restrict('Admin.Payment type.View');
       $result = array(); 
       $this->load->view('includes/home_header'); 
       $data=array(); 
       $data['payment']=$this->Payment_model->allpaymenttype(); 
       // $data['count']=count($data['designation']); 
       /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
       $this->load->model('Rota_model');
       $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
       /*End*/
       $this->load->view("admin/payment/managepayment",$data);
       $result['js_to_load'] = array('payment/payment.js');
       $this->load->view('includes/home_footer',$result);

    }

    public  function  addpaymenttype()
{
        $this->auth->restrict('Admin.Payment type.Add');
        $header['title'] = 'Add Paymenttype';
        $data = array(); 
        
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('payment_type', 'payment type', 'required');
        $this->form_validation->set_rules('description', 'description', 'required'); 
        $data = array(); 
        $header['title'] = 'Add'.'payment_type';
        $this->load->view('includes/home_header',$header);
    
       if ($this->form_validation->run() == FALSE)
       {
          
            $data['payment']=$this->Payment_model->allpaymenttype();
            $data['error']='';
            $this->load->view("admin/payment/addpaymenttype",$data);
             
        }
        else
        {   
           // print_r(date('Y-m-d H:i:s'));exit();
        $payment_type= $this->input->post('payment_type'); 
    		$description=$this->input->post('description');
    		$status=$this->input->post('status');
        if($this->checkPaymenttype($payment_type,'')==0){
                $data['error']= "Payment type already exist, please try another name.";
              
                $this->load->view("admin/payment/addpaymenttype",$data);
            }
            else
            {
    		$dataform=array('payment_type' => $payment_type,'created_date'=>date('Y-m-d H:i:s'),'description'=>$description,'status'=>$status); 
    		$result=$this->Payment_model->insertpayment($dataform); 
            if($result=='true')
            {
                   $title='Add Paymenttype';
                   InsertEditedData($this->input->post(),$title);
            }
            $this->session->set_flashdata('message', 'Added successfully.');
            $data = array();
            redirect('admin/payment');

          }

        }
        
        $this->load->view('includes/home_footer');
    
} 
     public function deletedpaymenttype()
        {   
            $this->auth->restrict('Admin.Payment type.Delete');
            try{
                $id=$this->uri->segment(4); 
                $payment=$this->Payment_model->findUsersPayment($id);   
                if(empty($payment))
                    {    
                        $data = $this->Payment_model->deletedpaymenttype($id); 
                        if($data==1)
                        $abc=array('id'=>'payment id : '. $id);
                        $title='Delete Paymenttype';
                        InsertEditedData($abc,$title);
               
                        $this->session->set_flashdata('error',"0");
                        $this->session->set_flashdata('message', 'Deleted successfully.');
                        redirect('admin/managepayment');
                    }
                else
                    { 
                        $this->session->set_flashdata('error',"1");
                        $this->session->set_flashdata('message', 'Cannot delete this payment type, this payment type is already assigned to users.');
                        redirect('admin/managepayment');
                    }
                }

        catch (Exception $e){
            print_r($e->getMessage());
             }
        }

    public function checkPaymenttype($paymenttype, $id=''){
        
        $status = 1;
        $data = array();
        $data['payment_type']= $paymenttype;
        $data['id']= $id;
        $result = $this->Payment_model->getpaymentDetails($data);
       
        unset($data);
          if(count($result)>0) 
            $status = 0;
          
       return $status;
       
    }

    public function findpayment()
    {
       $status=$this->input->post('status');
       $result=$this->Payment_model->findpayment($status);
        if(empty($result))
        { 
           $json = []; 
        }
        else
        { 
            foreach ($result as $row)
              {
                if($row['status']==1) {$stat="Active"; } else if($row['status']==2) {$stat="Inactive";} else{ $stat="Deleted";} 
                $delete="'".$row['id']."','".$row['payment_type']."'";  
                $json[] =array('<a href="payment/editpaymenttype/'.$row['id'].'">'.$row['payment_type'],$row['description'],$stat,'<a class="Edit" data-id="'.$row['id'].'" href="payment/editpaymenttype/'.$row['id'].'" title="Edit"><i class="fas fa-edit"></i></a> '.' '.'<a href="javascript:void(0);" data-id="'.$row['id'].'" title="Delete" onclick="deleteFunction('.$delete.')">'.'<i class="fa fa-trash"></i>');
              } 
        }
 
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();
    }
 
 

    public function Editpaymenttype()
   { 
        $this->auth->restrict('Admin.Payment type.Edit');
        $title_slug = $this->uri->segment(4);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $id=$this->input->post('id'); 
        $status=$this->input->post('status');  
        $this->form_validation->set_rules('payment_type', 'payment type', 'required');
        $this->form_validation->set_rules('description', 'description', 'required'); 
        $abc=2;
        if($status==$abc) 
        {
            $this->form_validation->set_rules('status', 'status', 'callback_status_check['.$id.']', 'required');
        }
        $header['title'] = 'Edit'.'payment_type';
        $data = array(); 
        $this->load->view('includes/home_header',$header);
    

      if ($this->form_validation->run() == FALSE)
       {
            if(empty($title_slug)) { $title_slug=$id;}
            $data['pay']=$this->Payment_model->findpaymenttype($title_slug);
            $data['error']='';
            $this->load->view('admin/payment/editpaymenttype', $data);
             
        }
        else
        
         {
                $payment_type= $this->input->post('payment_type'); 
                $description= $this->input->post('description');
                $status= $this->input->post('status');
                $id= $this->input->post('id'); 
                $userid= $this->input->post('userid'); 
                if($this->checkPaymenttype($payment_type,$id)==0)
            {
                $data['error']= 'Payment type already exist, please try another name.';
                $data['pay']=$this->Payment_model->findpaymenttype($id);
                $this->load->view("admin/payment/editpaymenttype",$data);
            }
            else
            {
                $datahome=array('payment_type'=>$payment_type,'description'=>$description,'status'=>$status,'updated_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$userid); 
                $result = $this->Payment_model->update($id,$datahome);
                if($result)
                {
                   $title='Edit Paymenttype';
                   InsertEditedData($this->input->post(),$title);
                }
                $this->session->set_flashdata('message', 'Updated successfully.');
                $data['pay']=$this->Payment_model->findpaymenttype($title_slug);
                redirect('admin/Payment');
            //$this->load->view('admin/editmenubanners', $data);
            }

            }
              
        $this->load->view('includes/home_footer');
         
        }     
         

         public function status_check($str,$id)
        {  
                $test=array();
                $test=$this->Payment_model->findUsersPayment($id);   
                if (empty($test))
                {   
                      return TRUE;
                       
                }
                else
                {  
                    // $this->session->set_flashdata('message',"1");
                    // $this->session->set_flashdata('message', 'Cannot change status, this paymenttype is already assigned to users.');
                     $this->form_validation->set_message('status_check', 'Cannot change status, this payment type is already assigned to users.'); 
                        return FALSE;
                }  
        }


}
?>
