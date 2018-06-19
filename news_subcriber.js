function newslettermail()
{
		var error = false;
		var email = document.getElementById('newsletter').value;
		var name = document.getElementById('name').value;
		var plugin_url = document.getElementById('plugin_url').value;
		var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		//alert(name);
 		if(name == '')
			{
					document.getElementById('status').innerHTML = "<font color='#FF0000' style='font-family:arial; font-size:11px;'><strong>Enter Name</strong></font>";
					
					error = true;
					return false;
			}
		
		 if(email == '')
			{
					document.getElementById('status').innerHTML = "<font color='#FF0000' style='font-family:arial; font-size:11px;'><strong>Enter Email</strong></font>";
					
					error = true;
					return false;
			}
		
		 if(!regex.test(email))
			{
				document.getElementById('status').innerHTML = "<font color='#FF0000' style='font-family:arial; font-size:11px;'><strong>Email Not Valid</strong></font>";
					
					error = true;
					return false;	
			}
			
		

		if(error==false){
			
			
			document.getElementById('status').innerHTML = '<img src="'+plugin_url+'images/loading.gif" />';
			//jQuery('#status').show();
			
			jQuery.ajax({
		
					type: "GET",
				
					url: plugin_url+"news_mail.php?email="+email+"&name="+name,
		
					async: true,
		
					success: function(msg){
		
					document.getElementById('newsletter').value = '';
					jQuery('#status').html(msg);
					
					
					}
					
		
				 });
		}

}