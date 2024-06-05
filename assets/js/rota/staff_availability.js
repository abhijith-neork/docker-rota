$(document).ready(function() {
    $('#calendar').fullCalendar({
    height: "auto",
    slotWidth:100,
    resourceAreaWidth: 350,  
    editable: false,
    eventLimit: true,filterResourcesWithEvents:true,refetchResourcesOnNavigate:true,
    aspectRatio: 3,
    scrollTime: '00:00',
    header: {
      left: ' ',
      center: 'title',
      right: ' ',   
    },
    titleFormat: ' ', 
    eventRender: function( event, element, view ) { 

    },eventAfterRender: function( event, element, view ) { 
              // console.log("eventAfterRender");  
    },
    defaultDate: default_date, 
    events: weekEvents,
    firstDay: 0,
    defaultView: 'customWeek',
    views: {
        customWeek: {
            type: 'timeline',
            duration: { weeks: week },
            slotDuration: {days: 1},
            buttonText: 'Custom Week'
        }
    },
    resourceColumns: [
        {
          labelText: 'Employee',
          field: 'title'
        }
      ],
    resources: resources,
   
   });

    
     $(document).on('click', '.search', function(e) { 

      var unit=$('#unit').val();  
      var jobrole=$('#jobrole').val();  
      var year=$('#year').val(); //console.log(jobrole.length);
      var month=$('#month').val();
      if(unit==1 ){
          swal("Please select unit");
      e.preventDefault();

      }
      else if(year == "none"){
          swal("Please select year");
      e.preventDefault();

      }
      else if(month == "none"){
          swal("Please select month");
      e.preventDefault();

      }else{
      $( "#frmStaffAvailabilityRota" ).submit();
      }
    });
    
   

});
