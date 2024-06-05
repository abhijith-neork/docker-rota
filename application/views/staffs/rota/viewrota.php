<style>

.fc-prev-button, .fc-next-button {

    background: #2196F3 !important;
    border: 1px solid #2196F3 !important;

}
</style> 
<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">

				<div class="row page-titles">
					<div class="col-md-6 col-8 align-self-center">
						<h3 class="text-themecolor mb-0 mt-0">View</h3>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a
								href="<?php echo base_url();?>staffs/profile">Home</a></li>
							<li class="breadcrumb-item active">View</li>
						</ol>
					</div>
					<div class="col-md-6 col-4 align-self-center"></div>
				</div>
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
<script type="text/javascript">
        var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        var element = document.getElementById('text');
        if (isMobile) {
            $view='agendaWeek';
        } else {
            $view='month';
        }
</script>
