<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='http://fullcalendar.io/js/fullcalendar-2.5.0/fullcalendar.css' rel='stylesheet' />
<link href='http://fullcalendar.io/js/fullcalendar-2.5.0/fullcalendar.print.css' rel='stylesheet' media='print' />
<link rel="stylesheet" href="<?php echo base_url()?>assets/scripts/fullcalendar/scheduler.min.css" rel='stylesheet' />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap1.min.css" /> 


 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/fullcalendar/bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/fullcalendar/moment.js"></script> 
<script src='http://fullcalendar.io/js/fullcalendar-2.5.0/fullcalendar.js'></script>  
<script src="<?php echo base_url() ?>assets/scripts/fullcalendar/scheduler.js"></script>


</head>
<body>

    <div class="container" style="width: 90%; padding-top: 100px;"> 
    <div class="row" style="width:100%;padding: 0px;"> 
    <div class="col-md-12"> 

    <div id="calendar">

    </div>

    </div>
    </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 397px;height: 360px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Shift</h4>
      </div>
      <div >
      <form class="contactus-form wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s"  action="<?php echo base_url('CaledarController/');?>add_event" method="post">
        <div class="form-group" style="padding-bottom: 76px; "> <label style="padding-left: 20px;">Event Shifttime</label><br>
                 <div class="col-md-3">start time:<input type="text" class="form-control" name="start" value=""></div>
                 <div class="col-md-3">end time:<input type="text" class="form-control" name="end" value=""></div> 
                 <div class="col-md-6">Break time(minutes):<input type="text" class="form-control" name="break" value=""></div> 
        </div> 
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Select role:</label>
                <div class="col-md-8 ui-front" style="padding-bottom: 20px; ">
                     <select class="form-control" name="role" id="role" required="required">
                       <?php 
                foreach($role as $r)
                { ?> 
                
                <option value="<?php echo $r['id']; ?>"<?php if($r['role']==$r['id']) echo 'selected="selected"'; ?>><?php echo $r['role']; ?></option> 
               <?php } ?>

                     </select>
                </div>
        </div>
        
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Notes</label>
                <div class="col-md-8" style="padding-bottom: 20px; ">
                    <textarea class="form-control" name="notes"></textarea>
                </div>
        </div>

      </div>
      <div style="padding-top: 10px; float: right; padding-right: 20px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Add Event">
        </form>
      </div>
    </div>
  </div>
</div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content"  style="width: 397px;height: 360px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Calendar Event</h4>
      </div>
      <div class="modal-body">
      <form class="contactus-form wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s"  action="<?php echo base_url('CaledarController/');?>edit_event" method="post">
      <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Event Name</label>
                <div class="col-md-8 ui-front" style="padding-bottom: 20px;">
                    <input type="text" class="form-control" name="name" value="" id="name">
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Description</label>
                <div class="col-md-8 ui-front" style="padding-bottom: 20px;">
                    <input type="text" class="form-control" name="description" id="description">
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Start Date</label>
                <div class="col-md-8" style="padding-bottom: 20px;">
                    <input type="text" class="form-control" name="start_date" id="start_date">
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">End Date</label>
                <div class="col-md-8" style="padding-bottom: 20px;">
                    <input type="text" class="form-control" name="end_date" id="end_date">
                </div>
        </div>
        <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading">Delete Event</label>
                    <div class="col-md-8" style="padding-bottom: 20px;">
                        <input type="checkbox" name="delete" value="1">
                    </div>
            </div>
            <input type="hidden" name="eventid" id="event_id" value="0" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Update Event">
        </form>
      </div>
    </div>
  </div>
</div>
 
<script type="text/javascript">
$(document).ready(function() {

    var date_last_clicked = null;

    $('#calendar').fullCalendar({
       //  eventSources: [
       //     {
       //     events: function(start, end, timezone, callback) {
       //          $.ajax({
       //              url: '<?php echo base_url() ?>CaledarController/get_events',
       //              dataType: 'json',
       //              data: {
       //                  // our hypothetical feed requires UNIX timestamps
       //                  start: start.unix(),
       //                  end: end.unix()
       //              },
       //              success: function(msg) { 
       //                  var events = msg.events; console.log(events); 
       //                  callback(events);
       //              }
       //          });
       //        }
       //      },
       //  ],
       //  dayClick: function(date, jsEvent, view) {
       //      date_last_clicked = $(this); 
       //      $('#addModal').modal();
       //  },
       //  eventClick: function(event, jsEvent, view) {
       //    $('#name').val(event.title);
       //    $('#description').val(event.description);
       //    $('#start_date').val(moment(event.start).format('YYYY/MM/DD HH:mm'));
       //    if(event.end) {
       //      $('#end_date').val(moment(event.end).format('YYYY/MM/DD HH:mm'));
       //    } else {
       //      $('#end_date').val(moment(event.start).format('YYYY/MM/DD HH:mm'));
       //    }
       //    $('#event_id').val(event.id);
       //    $('#editModal').modal();
       // },
    resourceAreaWidth: 300,  
    editable: true,
    aspectRatio: 3,
    scrollTime: '00:00',
    header: {
      left: 'promptResource today prev,next',
      center: 'title',
      right: ' '
    }, 

    customButtons: {
      promptResource: {
        text: '+ staff',
        click: function() {
          var title = prompt('Staff name');
          if (title) {
            $('#calendar').fullCalendar(
              'addResource',
              { title: title },
              true // scroll to the new resource?
            );
          }
        }
      }
    },
    firstDay: 1,
    defaultView: 'customWeek',
    views: {
        customWeek: {
            type: 'timeline',
            duration: { weeks: 1 },
            slotDuration: {days: 1},
            buttonText: 'Custom Week'
        }
    },
    resourceLabelText: 'Staff',
    resources: '<?php echo base_url() ?>assets/scripts/fullcalendar/abc.php'
  });
  
});

</script>
</body>
</html>
