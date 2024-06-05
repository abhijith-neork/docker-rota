<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class CaledarController extends CI_Controller {
   
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        Parent::__construct();
        $this->load->model("Calendar_model");
    }  
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index()
    { 
        $result = array(); 
       $result['css_to_load'] = array( );
       $this->load->view('includes/header',$result); 
       $this->load->view("calendar/my_calendar.php");
       $result['js_to_load'] = array();
        $this->load->view('includes/footer',$result);
    }

      public function get_events() 
    {
        // Our Stand and End Dates
        $start = $this->input->get("start");
        $end = $this->input->get("end");

        $startdt = new DateTime('now'); // setup a local datetime
        $startdt->setTimestamp($start); // Set the date based on timestamp
        $format = $startdt->format('Y-m-d H:i:s');

        $enddt = new DateTime('now'); // setup a local datetime
        $enddt->setTimestamp($end); // Set the date based on timestamp
        $format2 = $enddt->format('Y-m-d H:i:s');

        $events = $this->Calendar_model->get_events($format, 
            $format2);

        $data_events = array();

        foreach($events->result() as $r) { 

            $data_events[] = array(
                "id" => $r->ID,
                "start" => $r->start,
                "end" => $r->end,
                "role"=> $r->role,
                "notes"=> $r->notes,
                "break"=> $r->break
            );
        }

        echo json_encode(array("events" => $data_events));
        exit();
    }

    public function add_event() 
    {
        /* Our calendar data */
        $starttime = $this->input->post("start");
        $endtime = $this->input->post("end");
        $break = $this->input->post("break");
        $role = $this->input->post("role");
        $notes = $this->input->post("notes"); 

        

        $this->Calendar_model->add_event(array(
            "starttime" => $starttime,
            "end" => $endtime,
            "break" => $break,
            "role" => $role,
            "notes" => $notes
            )
        );

        redirect(site_url("CaledarController"));
    }

    public function edit_event() 
    {
        $eventid =$this->input->post("eventid");
        $event = $this->Calendar_model->get_event($eventid);
        if($event->num_rows() == 0) {
            echo"Invalid Event";
            exit();
        }

        $event->row();

        /* Our calendar data */
        $name = $this->input->post("name");
        $desc = $this->input->post("description");
        $start_date = $this->input->post("start_date");
        $end_date = $this->input->post("end_date");
        $delete = $this->input->post("delete");

        if(!$delete) 
        {

            $this->Calendar_model->update_event($eventid, array(
                "title" => $name,
                "description" => $desc,
                "start" => $start_date,
                "end" => $end_date,
                )
            );
            
        } 
        else
        {
            $this->Calendar_model->delete_event($eventid);
        }

        redirect(site_url("CaledarController"));
    }

}
?>