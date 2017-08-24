jQuery(document).ready(function($) {
	var realtimeSolar 	= jQuery(".cemm-realtime-solar");
	var monthSolar 		= jQuery(".cemm-month-solar");
	var todaySolar 		= jQuery(".cemm-today-solar");

	function UpdateRealtimeData(response){
		realtimeSolar.text(Math.floor(response.data.electric_power[1]));
	}

	function UpdateMonthData(response){
		var days  = [];
        var total = 0;
        var today = new Date();
        
        for(var i=0; i<response.data.electric_energy_high.length; i++){
            days[i] = {
            	time:  new Date(response.data.electric_energy_high[i][0]), 
            	value: (response.data.electric_energy_high[i][1] + response.data.electric_energy_low[i][1]) 
            };

            total += days[i].value;

            if(todaySolar.length && (days[i].time.getDate() == today.getDate())){
                todaySolar.text(Math.floor(days[i].value));
            }
        }

        if(monthSolar.length){
        	monthSolar.text(Math.floor(total));
        }
	}

	if( realtimeSolar.length ){
	    function GetRealtimeSolarData(){
	        jQuery.post(ajax_object.ajax_url, {"action": "get_realtime_data"}, function(response){
	            if(response.data && response.data.electric_power){
	                UpdateRealtimeData(response)
	            }
	            else if( response.promise ){
	            	// Retry after receiving a promise;
	            	setTimeout(function(){
	            		jQuery.post(ajax_object.ajax_url, {"action": "get_realtime_data"}, function(response){
		            		if(response.data && response.data.electric_power){
				                UpdateRealtimeData(response);
				            }
		            	});
	            	}, 2000);
	            	
	            }
	        });
	    }

	    GetRealtimeSolarData();

	    setInterval(function(){
	        GetRealtimeSolarData();
	    }, 10000);
	}

	if( monthSolar.length || todaySolar.length ){
	    function GetMonthSolarData(){
	        jQuery.post(ajax_object.ajax_url, {"action": "get_month_data"}, function(response) {
	            if(response.data && response.data.electric_energy_low && response.data.electric_energy_high){
	                UpdateMonthData(response);
	            }
	            else if(response.promise){
	            	// Retry after receiving a promise;
	            	setTimeout(function(){
	            		jQuery.post(ajax_object.ajax_url, {"action": "get_month_data"}, function(response){
		            		 if(response.data && response.data.electric_energy_low && response.data.electric_energy_high){
				                UpdateMonthData(response);
				            }
		            	});
	            	}, 5000);
	            }
	        });
	    }

   		GetMonthSolarData();

    	setInterval(GetMonthSolarData, 300000);
	}
});