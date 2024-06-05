<link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css">
<link href="<?php echo base_url();?>assets/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet"> 

<style type="text/css"> 
.page-wrapper {
        margin-left: 0px !important;
}
</style>

<div class="page-wrapper">
<div class="container-fluid">
<div class="card">
<div class="card-body">
<div class="row page-titles">
<div class="col-md-6 col-8 align-self-center">
<h3 class="text-themecolor mb-0 mt-0">My Trainings</h3>
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/manageuser">Home</a></li>
<li class="breadcrumb-item active">My Trainings</li>
</ol>
</div>
<div class="col-md-3 col-4 align-self-center">
</div>
<div class="col-md-3 col-4 align-self-center" style="padding-left: 31px;">
    <button type="button" onclick="cancelTraining(<?php echo $this->session->userdata('user_type');?>)"  class="btn float-right hidden-sm-down btn-secondary" id="cancel" style="height: 40px; width:120px;margin-left:10px;">Cancel</button>
	<!-- <button class=""
                                                    onclick="location.href='<?php echo base_url();?>staffs/profile'" >
                                                     Back
                                                </button> -->
	</div>
</div>
<?php
if($this->session->flashdata('error')==1)
$color="red";
else 
$color="green";

if($this->session->flashdata('message')):?>
<p class="success-msg" id="success-alert"
style="color: <?php echo $color; ?>; text-align:center;">
<?php echo $this->session->flashdata('message');?>
</p>
<?php endif;?> 

<div class="row">
<div class="col-12">
<div class="card">
<div class="card-body"><p id="selectTriggerFilter"></p>
<div class="table-responsive m-t-40">
<table id="myTable" class="table table-bordered table-striped">
<thead>
<tr>
<!-- <th style="border:1px solid #dee2e6">No</th> --> 
<th style="border:1px solid #dee2e6">Date</th> 
<th style="border:1px solid #dee2e6">Title</th> 
<th style="border:1px solid #dee2e6">Description</th>
<th style="border:1px solid #dee2e6">From</th>
<th style="border:1px solid #dee2e6">To</th>
<th style="border:1px solid #dee2e6">Place</th>
<th style="border:1px solid #dee2e6">Point of Contact</th>
<th style="border:1px solid #dee2e6">Comments</th>
<!-- <th style="border:1px solid #dee2e6">Action</th> -->
</tr>
</thead>

<tbody> 
<?php 
foreach($training as $desig)

{ 

$userfeedback = getfeedback($this->session->userdata('user_id'),$desig['training_id']);


?> 
<tr>                                                             
<td><?php echo date("d/m/Y",strtotime($desig['date_from'])); ?></td>                                                          
<td><?php echo $desig['title']; ?></td>
<td><?php echo $desig['description']; ?></td>
<td><?php echo $desig['time_from']; ?></td>
<td><?php echo $desig['time_to']; ?></td>
<td><?php echo $desig['place']; echo ","; print '<br>'; echo $desig['address'];?></td>
<td><?php echo $desig['point_of_person'];?></td>   
<td>
<?php   if(count($userfeedback)>0) { echo $userfeedback[0]['feedback']; }else{ ?>

<button data-id="<?php echo $desig['training_id']; ?>" class="btn waves-effect waves-light btn-info" id="feedback" onclick="editFeedback(<?php echo $desig['training_id']; ?>)" >
<i class="fa fa-edit"></i>&nbsp;Add 
</button>
<?php }?>

</td> 

</tr>  

<?php
}
?>
</tbody>

</table>

</div>
</div>
</div>

</div>


</div> 
</div>
</div>
</div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/popper.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/waves.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sidebarmenu.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/dropify/dist/js/dropify.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/moment/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/footable/js/footable.min.js"></script> -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.datetimepicker.min.js"></script>
 <!-- start - This is for export functionality only -->
 <script type="text/javascript"
    src="<?php echo base_url();?>/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<!-- start - This is for export functionality only -->
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.flash.min.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/jszip.min.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/pdfmake.min.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/vfs_fonts.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.html5.min.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.print.min.js"></script> 
<script type="text/javascript">
     $(function() {
               
    $(document).ready(function() {
        $("#start_date").datepicker({ 
            dateFormat: 'dd/mm/yy'
        });
        $("#end_date").datepicker({ 
            dateFormat: 'dd/mm/yy'
        });
        var table = $('#myTable').DataTable({
            "bFilter": false,
            "bInfo": false,
            "bLengthChange": false,    
            "order": [
                [0, 'asc']
            ],
            "displayLength": 25,
            "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
        });
        // Order by the grouping
        $('#myTable tbody').on('click', 'tr.unit', function() {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            table.order([2, 'desc']).draw();
        } else {
            table.order([2, 'asc']).draw();
        }
        });
    });
});

    function editFeedback(id) {
        var baseURL = '<?php echo base_url();?>';
        var mobile="mobile";
        event.preventDefault(); // prevent form submit
        window.location = baseURL+"staffs/training/editFeedback/"+id+'/'+mobile;
    }

function cancelTraining(id){   console.log(id);
           var baseURL = '<?php echo base_url();?>';
            if(id==2)
            {
                event.preventDefault();
                window.location = baseURL+"staffs/profile";
            }
            else
            {
                event.preventDefault();
                window.location = baseURL+"manager/profile";

            }
}
</script>