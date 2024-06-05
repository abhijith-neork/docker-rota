<link href="<?php echo base_url();?>assets/css/fullcalendar.min.css" rel='stylesheet' />
<link href="<?php echo base_url();?>assets/css/fullcalendar.print.css" rel='stylesheet' media='print' />
<style>
.fc-prev-button, .fc-next-button {

    background: #2196F3 !important;
    border: 1px solid #2196F3 !important;

}
.fc-today {
    background: none !important;
}
.fc-content {
    height: 0px;
}
body{
        font-family: "Rubik", sans-serif;
            color: #54667a;
    font-weight: 300;
    cursor: pointer;
}
.fc-toolbar h2 {
    font-size: 18px;
    font-weight: 500;
    line-height: 30px;
    text-transform: uppercase;
}
.topbar {
    position: relative;
    z-index: 50;
}
.user-profile .profile-img {
    width: 50px;
    margin: 0 auto;
    border-radius: 100%;
}
.fc th.fc-widget-header {
    color: #54667a;
    font-size: 13px;
    font-weight: 300;
    line-height: 20px;
    padding: 7px 0px;
    text-transform: uppercase;
}
.text-themecolor {
    color: #009efb !important;
}
h3 {
    line-height: 30px;
    font-size: 21px;
}
.btn-success, .btn-success.disabled {
    background:  #868e96;
    border: 1px solid #868e96;
    color: #ffffff !important;
    border-color: #868e96;
    border-image: initial;
    float: left !important;
    border-radius: .25rem;
} 
.fc-widget-content
{
    height: 200px !important;
} 
.fc-scroller
{
    height: 1201px !important;
}

</style>

<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
<p>
				<div class="row page-titles">
					<div class="col-md-6 col-8 align-self-center">
						<h3 class="text-themecolor mb-0 mt-0" style="text-align: center;">Rota View</h3> 
						<button class="btn float-right hidden-sm-down btn-success"
                            onclick="location.href='<?php echo base_url();?>manager/editprofile'" style="height: 40px; width:120px;margin-left:10px;">
                            <i class="mdi mdi-plus-circle"></i> Back
                        </button> <br> 
					</div>
					<div class="col-md-6 col-4 align-self-center"></div>
				</div><br></p>
                     <?php
                    if ($this->session->flashdata('error') == 1)
                        $color = "red";
                    else
                        $color = "green";

                    if ($this->session->flashdata('message')) :
                        ?>
                    <p class="success-msg" id="success-alert"
                            style="color: <?php echo $color; ?>; text-align:center;">
                      <?php echo $this->session->flashdata('message');?>
                    </p> 
                    <?php endif;?> 
                  
  <style>
 .fc-event{     position: relative;

display: block;

font-size: .85em;

line-height: 1.3;

border-radius: 3px;

border: 1px solid #c8e1ec !important;

background-color: #fff!important ;

font-weight: normal;
}
.tds{
padding-top: 5px !important;
}
/*.tds h5{ color:#0000;}*/

      </style>           

<script type="text/javascript">
   var default_date='<?php print $default_date;?>';
   //var trainings=<?php print $trainings;?>; 
   var baseURL = "<?php echo base_url();?>"; 

</script> 
              <div class="row"><p id="text"></p>
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<div class="col-md-12">
									<div id="calendar"></div>
								</div>

							</div>
						</div>
					</div>
				</div>
		 
 			</div>
		</div>
	</div> 
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-1.12.4.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/fullcalendar/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/fullcalendar/moment.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/fullcalendar_staff.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/rota/managerrota.js"></script>  
<script type="text/javascript">
        var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        var element = document.getElementById('text');
        if (isMobile) {
            $view='agendaWeek';
        } else {
            $view='month';
        }
</script>

