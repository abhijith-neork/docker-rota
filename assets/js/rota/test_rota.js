$(document).ready(function() {
	$('#calendar').fullCalendar({
		resourceGroupField: 'designation_id',
		resourceOrder : 'title',
		height: 700,
		resourceAreaWidth: 350,  
		editable: false,
		eventLimit: true,
		aspectRatio: 3,
		scrollTime: '00:00',
		header: {
		  left: 'promptResource today prev,next',
		  center: 'title',
		  right: ' ',   
		},
		titleFormat: '[Schedule for week] - ddd D/MM/YYYY [: '+unit+']', 
		eventRender: function( event, element, view ) { 
			var title = element.find( '.fc-title' );
			title.html( title.text() );
		},eventAfterRender: function( event, element, view ) {

		},
		eventAfterAllRender:function(){ //console.log('time delay');
          
              $("#img-loader-data").hide();
            },
		events: weekEvents,
		defaultView: 'customWeek',
			views: {
				customWeek: {
				type: 'timeline',
				duration: { weeks: 1 },
				slotDuration: {days: 1},
				buttonText: 'Custom Week'
			}
		},
		resourceColumns: [
			{
				labelText: 'Employees',
				field: 'title',
				width:'79%'
			},
			{
				labelText: 'Hours',
				field: 'totalhours', width:'21%'
			}
		],
		resources:rotabc,
		resourceRender: function (resourceObj, labelTds) { 
			     
		} 
	});
});