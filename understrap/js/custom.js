$ = jQuery;
$(document).ready(function(){
	var vrm_key;
	$("#vrm-form").submit(function(e){		
		e.preventDefault();
		vrm_key = $(".vrm").val();
		$('#main').html("<div id ='loading'></div>");
		if (/\s/.test(vrm_key)) {
			//replace %20 in url parameter with +
		    vrm_key = vrm_key.replace(/ /g, "+");

		}
		console.log(vrm_key);
		console.log(btoa(vrm_key));
		//window.location.href=window.location.origin+"/my-report/free?reg="+vrm_key;

		/*var vrm_key = $(".vrm").val();
		var ajax_url = jquerydata.ajaxurl+"?action=vrmsearch";
		console.log(vrm_key);
		console.log(ajax_url);
		console.log($(this).serialize());
		$('#main').html("<div id ='loading'></div>");
		$.ajax({
			url: ajax_url,
			type:"POST",
			data:{'action':'vrmsearch', 'vrm':vrm_key},
			success:function(data)
			{	
				console.log(data);
				var result = JSON.parse(data);
					console.log(result.Response.DataItems.VehicleRegistration.Vrm);
				var search_result;
				search_result = " <div class='head_text txt-center'> <h3>Your Report for <b>"+ result.Response.DataItems.VehicleRegistration.Vrm+"</b></h3></div>";
				$('#main').html(search_result);
				window.location.href=window.location.origin+"/vrm/free";
			}
		});
		return false;*/
	})
	/*$("#category_name").change(function(){
		var ajax_url = jquerydata.ajax_url+"&action=custom_ajax";
		var category_name = $("#category_name").val();
		$.ajax({
			url: ajax_url,
			type:"POST",
			data:{'action':'custom_ajax', 'category_name':category_name}
			success:function(data)
			{	
				console.log(data);
				console.log("success");
			}
		})
	})*/
})
