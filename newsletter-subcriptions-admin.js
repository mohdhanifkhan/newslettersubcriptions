// JavaScript Document

//admin js

// on check #snsf-all-checkboxes", select all check boxes
jQuery(document).ready(function(){
    var newValue;
	jQuery('#snsf-action-form').change(function () {
		 newValue = jQuery('#snsf-action-form').val();
			jQuery('#snsf-the-do-action').val(newValue);
    });
	
	jQuery('#snsf-action-form1').change(function () {
		 newValue = jQuery('#snsf-action-form1').val();
			jQuery('#snsf-the-do-action').val(newValue);
    });
	
	
	jQuery('#snsf-all-checkboxes').click(function () {
        jQuery('.all-checkable').attr('checked', this.checked);
    });
// on change update the form with action
    
	
	jQuery('#snsf-perform-action').click(function(){

			jQuery('#snsf-actions-form').submit();

});
	
		jQuery('#snsf-perform-action1').click(function(){

			jQuery('#snsf-actions-form').submit();

});
	
	

});


// JavaScript Document
jQuery(document).ready(function(){
	jQuery('#snsf-checkbox').click(function(){	
		if(jQuery('#snsf-checkbox').is(':checked')){
			jQuery('#snsf-submit-button').removeAttr('disabled');
		}
		else{
			jQuery('#snsf-submit-button').attr('disabled','disabled');
		}
		
	});
});

