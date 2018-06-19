<?php
/*
Plugin Name: Newsletter Subscriptions
Plugin URI: https://profiles.wordpress.org/hanif-khan
Description: This plugin is for newsletter subscriptions. 
Version: 1.1
Author: Hanif Khan
Author URI: https://www.facebook.com/hanif.khan.5249
Text Domain: newsletter-subcriptions
Domain Path: /lang/

*/
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	
// Creating the widget 
class wpb_widget extends WP_Widget {

function __construct() {

	parent::__construct(
	// Base ID of your widget
	'wpb_widget', 
	
	// Widget name will appear in UI
	__('Newsletter Subscriptions Widget', 'wpb_widget_domain'), 
	
	// Widget description
	array( 'description' => __( 'Add Newsletter Subscriptions widget for users', 'wpb_widget_domain' ), ) 
	);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];


if ( ! empty( $title ) )
$title = $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
news_letter($title);

/*echo __( 'Hello, World!', 'wpb_widget_domain' );
echo $args['after_widget'];*/
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Newsletter', 'wpb_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

add_action ( 'admin_enqueue_scripts', function () {
	if (is_admin ())
		wp_enqueue_media ();
		wp_enqueue_script('jquery-ui','https://code.jquery.com/ui/1.12.1/jquery-ui.js');
		wp_enqueue_style('jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
} );

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );	



add_action('admin_menu','simple_newsletter_menu_tlwm');
add_filter('widget_text', 'do_shortcode');

// if there is a delete query, do it before they do anything else!
add_action('admin_init','snsf_prep_admin');
function snsf_prep_admin(){

		global $wpdb;
if(isset($_POST['snsf-checkbox-value'])){
	
	if($_POST['snsf-the-do-action'] == 'delete'){
$snsf_sql = "update tbl_news_letter set status='trash' where id in (";
		foreach($_POST['snsf-checkbox-value'] as $snsf_box){
			$snsf_sql .= $snsf_box.",";
		}
		
		$snsf_sql = substr_replace($snsf_sql ,"",-1);
		$snsf_sql .= ")";

		$wpdb->query($snsf_sql);
	}

	if(isset($_POST['snsf-the-do-action']) && $_POST['snsf-the-do-action'] == 'edit'){
	//edit em
	echo "edit em!";	
	}
	
}
	
}

register_activation_hook(__FILE__,'snsf_install');
function snsf_install(){
#create database table	

		$query = "CREATE TABLE IF NOT EXISTS `tbl_news_letter` (
					`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY key,
					`name` varchar(250) NOT NULL,
					`email` varchar(250) NOT NULL,
					`status` varchar(15) NOT NULL,
					`create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
		mysql_query($query);	
	
}


## ENQUEUE ADMIN SCRIPTS
add_action('init','snsf_call_admin_dependents');
function snsf_call_admin_dependents(){

	wp_register_script('snsf_admin_javascript',plugins_url('newsletter-subcriptions-admin.js',__FILE__),array('jquery'),'2.5.1');
}

add_action('admin_enqueue_scripts','snsf_admin_scripts');

function snsf_admin_scripts(){

	wp_enqueue_script('snsf_admin_javascript');	
}

function simple_newsletter_menu_tlwm(){
	
	$pluginUrl = WP_PLUGIN_URL . '/newsletter-subcriptions/';
	add_menu_page('Simple Newsletter Page','My Subscriptions','manage_options','newsletter-subscriptions','simple_newsletter_page',$pluginUrl . "images/news_subscription.png");
	add_submenu_page( 'newsletter-subscriptions', 'Subscriptions Setting', 'Subscriptions Setting', 'manage_options', 'my-custom-submenu-page', 'setteing_view' ); 
	//add_options_page( 'Settings', 'Settings', 'manage_options', 'newsletter-subcriptions', 'simple_newsletter_page' );
}

function setteing_view(){

	echo '<div class="wrap"><div class="icon32 icon32-posts-page" id="icon-edit-pages"></div>';
		echo '<h2>Newsletter Subscriptions Setting<br/><br/><p><strong>[NEWSLETTER]</strong> Use Shortcode for newsletter subscriptions.</p></h2>';
	echo '</div>';
?>
<?php /*?> 
<!--<script type="text/javascript">
 jQuery( function() {
    jQuery( "#tabs" ).tabs();
  } );               
</script>-->

   
    
<div id="postbox-container-2" class="postbox-container" style="float:none;margin-right: 10px;">
<div id="contact-form-editor" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
<div class="keyboard-interaction" style="visibility: hidden;"><span class="dashicons dashicons-leftright" aria-hidden="true"></span> <span class="screen-reader-text">(left and right arrow)</span> keys switch panels</div>

<div id="tabs"><ul id="contact-form-editor-tabs" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist"><li id="form-panel-tab" class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="form-panel" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="#form-panel" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">Subscription Form</a></li><li id="mail-panel-tab" class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="mail-panel" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="#mail-panel" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">Mail</a></li><li id="messages-panel-tab" class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="messages-panel" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="#messages-panel" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3">Messages</a></li><li id="additional-settings-panel-tab" class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="additional-settings-panel" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false"><a href="#additional-settings-panel" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-4">Additional Settings</a></li></ul><div class="contact-form-editor-panel ui-tabs-panel ui-widget-content ui-corner-bottom" id="additional-settings-panel" aria-labelledby="ui-id-4" role="tabpanel" aria-hidden="true" style="display: none;"><div class="config-error"></div><h2>Additional Settings</h2>
<fieldset>
<legend>You can add customization code snippets here. For details, see <a href="https://contactform7.com/additional-settings/">Additional Settings</a>.</legend>
<textarea id="wpcf7-additional-settings" name="wpcf7-additional-settings" cols="100" rows="8" class="large-text" data-config-field="additional_settings.body"></textarea>
</fieldset>
</div><div class="contact-form-editor-panel ui-tabs-panel ui-widget-content ui-corner-bottom" id="form-panel" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false" style="display: block;"><div class="config-error"></div>
<fieldset>
<legend>You can edit the form template here.<br />In the following fields, you can use these shortcode-tags:</legend>
<legend><span class="mailtag code used">[text* your-name]</span><span class="mailtag code used">[email* your-email]</span><span class="mailtag code used">[submit "Send"]</span></legend>

<textarea id="wpcf7-form" name="wpcf7-form" cols="100" rows="24" class="large-text code" data-config-field="form.body">&lt;label&gt; Your Name (required)
    [text* your-name] &lt;/label&gt;

&lt;label&gt; Your Email (required)
    [email* your-email] &lt;/label&gt;

[submit "Send"]</textarea>
</fieldset>
</div><div class="contact-form-editor-panel ui-tabs-panel ui-widget-content ui-corner-bottom" id="mail-panel" aria-labelledby="ui-id-2" role="tabpanel" aria-hidden="true" style="display: none;"><div class="config-error"></div><div class="contact-form-editor-box-mail" id="wpcf7-mail">
<h2>Mail</h2>


<fieldset>
<legend>
You can edit the mail template here. For details, see <a href="https://contactform7.com/setting-up-mail/">Setting Up Mail</a>.<br>In the following fields, you can use these mail-tags:<br><span class="mailtag code used">[your-name]</span><span class="mailtag code used">[your-email]</span><span class="mailtag code used">[your-subject]</span><span class="mailtag code used">[your-message]</span></legend>
<table class="form-table">
<tbody>
	<tr>
	<th scope="row">
		<label for="wpcf7-mail-recipient">To</label>
	</th>
	<td>
		<input type="text" id="wpcf7-mail-recipient" name="wpcf7-mail[recipient]" class="large-text code" size="70" value="hanifcitpl@gmail.com" data-config-field="mail.recipient">
	</td>
	</tr>

	<tr>
	<th scope="row">
		<label for="wpcf7-mail-sender">From</label>
	</th>
	<td>
		<input type="text" id="wpcf7-mail-sender" name="wpcf7-mail[sender]" class="large-text code" size="70" value="[your-name] <hanifcitpl@gmail.com>" data-config-field="mail.sender">
	</td>
	</tr>

	<tr>
	<th scope="row">
		<label for="wpcf7-mail-subject">Subject</label>
	</th>
	<td>
		<input type="text" id="wpcf7-mail-subject" name="wpcf7-mail[subject]" class="large-text code" size="70" value="Theme Forest test &quot;[your-subject]&quot;" data-config-field="mail.subject">
	</td>
	</tr>

	<tr>
	<th scope="row">
		<label for="wpcf7-mail-additional-headers">Additional Headers</label>
	</th>
	<td>
		<textarea id="wpcf7-mail-additional-headers" name="wpcf7-mail[additional_headers]" cols="100" rows="4" class="large-text code" data-config-field="mail.additional_headers">Reply-To: [your-email]</textarea>
	</td>
	</tr>

	<tr>
	<th scope="row">
		<label for="wpcf7-mail-body">Message Body</label>
	</th>
	<td>
		<textarea id="wpcf7-mail-body" name="wpcf7-mail[body]" cols="100" rows="18" class="large-text code" data-config-field="mail.body">From: [your-name] &lt;[your-email]&gt;
Subject: [your-subject]

Message Body:
[your-message]

---
This e-mail was sent from a contact form on Theme Forest test (http://localhost/wordpress-4.2.4)</textarea>

		<p><label for="wpcf7-mail-exclude-blank"><input type="checkbox" id="wpcf7-mail-exclude-blank" name="wpcf7-mail[exclude_blank]" value="1"> Exclude lines with blank mail-tags from output</label></p>

		<p><label for="wpcf7-mail-use-html"><input type="checkbox" id="wpcf7-mail-use-html" name="wpcf7-mail[use_html]" value="1"> Use HTML content type</label></p>
	</td>
	</tr>

	<tr>
	<th scope="row">
		<label for="wpcf7-mail-attachments">File Attachments</label>
	</th>
	<td>
		<textarea id="wpcf7-mail-attachments" name="wpcf7-mail[attachments]" cols="100" rows="4" class="large-text code" data-config-field="mail.attachments"></textarea>
	</td>
	</tr>
</tbody>
</table>
</fieldset>
</div>
<br class="clear"><div class="contact-form-editor-box-mail" id="wpcf7-mail-2">
<h2>Mail (2)</h2>

<label for="wpcf7-mail-2-active"><input type="checkbox" id="wpcf7-mail-2-active" name="wpcf7-mail-2[active]" class="toggle-form-table" value="1"> Use Mail (2)</label>
<p class="description">Mail (2) is an additional mail template often used as an autoresponder.</p>

<fieldset class="hidden">
<legend>
You can edit the mail template here. For details, see <a href="https://contactform7.com/setting-up-mail/">Setting Up Mail</a>.<br>In the following fields, you can use these mail-tags:<br><span class="mailtag code unused">[your-name]</span><span class="mailtag code used">[your-email]</span><span class="mailtag code used">[your-subject]</span><span class="mailtag code used">[your-message]</span></legend>
<table class="form-table">
<tbody>
	<tr>
	<th scope="row">
		<label for="wpcf7-mail-2-recipient">To</label>
	</th>
	<td>
		<input type="text" id="wpcf7-mail-2-recipient" name="wpcf7-mail-2[recipient]" class="large-text code" size="70" value="[your-email]" data-config-field="mail_2.recipient">
	</td>
	</tr>

	<tr>
	<th scope="row">
		<label for="wpcf7-mail-2-sender">From</label>
	</th>
	<td>
		<input type="text" id="wpcf7-mail-2-sender" name="wpcf7-mail-2[sender]" class="large-text code" size="70" value="Theme Forest test <hanifcitpl@gmail.com>" data-config-field="mail_2.sender">
	</td>
	</tr>

	<tr>
	<th scope="row">
		<label for="wpcf7-mail-2-subject">Subject</label>
	</th>
	<td>
		<input type="text" id="wpcf7-mail-2-subject" name="wpcf7-mail-2[subject]" class="large-text code" size="70" value="Theme Forest test &quot;[your-subject]&quot;" data-config-field="mail_2.subject">
	</td>
	</tr>

	<tr>
	<th scope="row">
		<label for="wpcf7-mail-2-additional-headers">Additional Headers</label>
	</th>
	<td>
		<textarea id="wpcf7-mail-2-additional-headers" name="wpcf7-mail-2[additional_headers]" cols="100" rows="4" class="large-text code" data-config-field="mail_2.additional_headers">Reply-To: hanifcitpl@gmail.com</textarea>
	</td>
	</tr>

	<tr>
	<th scope="row">
		<label for="wpcf7-mail-2-body">Message Body</label>
	</th>
	<td>
		<textarea id="wpcf7-mail-2-body" name="wpcf7-mail-2[body]" cols="100" rows="18" class="large-text code" data-config-field="mail_2.body">Message Body:
[your-message]

-- 
This e-mail was sent from a contact form on Theme Forest test (http://localhost/wordpress-4.2.4)</textarea>

		<p><label for="wpcf7-mail-2-exclude-blank"><input type="checkbox" id="wpcf7-mail-2-exclude-blank" name="wpcf7-mail-2[exclude_blank]" value="1"> Exclude lines with blank mail-tags from output</label></p>

		<p><label for="wpcf7-mail-2-use-html"><input type="checkbox" id="wpcf7-mail-2-use-html" name="wpcf7-mail-2[use_html]" value="1"> Use HTML content type</label></p>
	</td>
	</tr>

	<tr>
	<th scope="row">
		<label for="wpcf7-mail-2-attachments">File Attachments</label>
	</th>
	<td>
		<textarea id="wpcf7-mail-2-attachments" name="wpcf7-mail-2[attachments]" cols="100" rows="4" class="large-text code" data-config-field="mail_2.attachments"></textarea>
	</td>
	</tr>
</tbody>
</table>
</fieldset>
</div>
</div><div class="contact-form-editor-panel ui-tabs-panel ui-widget-content ui-corner-bottom" id="messages-panel" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="true" style="display: none;"><div class="config-error"></div><h2>Messages</h2>
<fieldset>
<legend>You can edit messages used in various situations here. For details, see <a href="https://contactform7.com/editing-messages/">Editing Messages</a>.</legend>
<p class="description">
<label for="wpcf7-message-mail-sent-ok">Sender's message was sent successfully<br>
<input type="text" id="wpcf7-message-mail-sent-ok" name="wpcf7-messages[mail_sent_ok]" class="large-text" size="70" value="Thank you for your message. It has been sent." data-config-field="messages.mail_sent_ok">
</label>
</p>
<p class="description">
<label for="wpcf7-message-mail-sent-ng">Sender's message failed to send<br>
<input type="text" id="wpcf7-message-mail-sent-ng" name="wpcf7-messages[mail_sent_ng]" class="large-text" size="70" value="There was an error trying to send your message. Please try again later." data-config-field="messages.mail_sent_ng">
</label>
</p>
<p class="description">
<label for="wpcf7-message-validation-error">Validation errors occurred<br>
<input type="text" id="wpcf7-message-validation-error" name="wpcf7-messages[validation_error]" class="large-text" size="70" value="One or more fields have an error. Please check and try again." data-config-field="messages.validation_error">
</label>
</p>
<p class="description">
<label for="wpcf7-message-spam">Submission was referred to as spam<br>
<input type="text" id="wpcf7-message-spam" name="wpcf7-messages[spam]" class="large-text" size="70" value="There was an error trying to send your message. Please try again later." data-config-field="messages.spam">
</label>
</p>
<p class="description">
<label for="wpcf7-message-accept-terms">There are terms that the sender must accept<br>
<input type="text" id="wpcf7-message-accept-terms" name="wpcf7-messages[accept_terms]" class="large-text" size="70" value="You must accept the terms and conditions before sending your message." data-config-field="messages.accept_terms">
</label>
</p>
<p class="description">
<label for="wpcf7-message-invalid-required">There is a field that the sender must fill in<br>
<input type="text" id="wpcf7-message-invalid-required" name="wpcf7-messages[invalid_required]" class="large-text" size="70" value="The field is required." data-config-field="messages.invalid_required">
</label>
</p>
<p class="description">
<label for="wpcf7-message-invalid-too-long">There is a field with input that is longer than the maximum allowed length<br>
<input type="text" id="wpcf7-message-invalid-too-long" name="wpcf7-messages[invalid_too_long]" class="large-text" size="70" value="The field is too long." data-config-field="messages.invalid_too_long">
</label>
</p>
<p class="description">
<label for="wpcf7-message-invalid-too-short">There is a field with input that is shorter than the minimum allowed length<br>
<input type="text" id="wpcf7-message-invalid-too-short" name="wpcf7-messages[invalid_too_short]" class="large-text" size="70" value="The field is too short." data-config-field="messages.invalid_too_short">
</label>
</p>
<p class="description">
<label for="wpcf7-message-invalid-date">Date format that the sender entered is invalid<br>
<input type="text" id="wpcf7-message-invalid-date" name="wpcf7-messages[invalid_date]" class="large-text" size="70" value="The date format is incorrect." data-config-field="messages.invalid_date">
</label>
</p>
<p class="description">
<label for="wpcf7-message-date-too-early">Date is earlier than minimum limit<br>
<input type="text" id="wpcf7-message-date-too-early" name="wpcf7-messages[date_too_early]" class="large-text" size="70" value="The date is before the earliest one allowed." data-config-field="messages.date_too_early">
</label>
</p>
<p class="description">
<label for="wpcf7-message-date-too-late">Date is later than maximum limit<br>
<input type="text" id="wpcf7-message-date-too-late" name="wpcf7-messages[date_too_late]" class="large-text" size="70" value="The date is after the latest one allowed." data-config-field="messages.date_too_late">
</label>
</p>
<p class="description">
<label for="wpcf7-message-upload-failed">Uploading a file fails for any reason<br>
<input type="text" id="wpcf7-message-upload-failed" name="wpcf7-messages[upload_failed]" class="large-text" size="70" value="There was an unknown error uploading the file." data-config-field="messages.upload_failed">
</label>
</p>
<p class="description">
<label for="wpcf7-message-upload-file-type-invalid">Uploaded file is not allowed for file type<br>
<input type="text" id="wpcf7-message-upload-file-type-invalid" name="wpcf7-messages[upload_file_type_invalid]" class="large-text" size="70" value="You are not allowed to upload files of this type." data-config-field="messages.upload_file_type_invalid">
</label>
</p>
<p class="description">
<label for="wpcf7-message-upload-file-too-large">Uploaded file is too large<br>
<input type="text" id="wpcf7-message-upload-file-too-large" name="wpcf7-messages[upload_file_too_large]" class="large-text" size="70" value="The file is too big." data-config-field="messages.upload_file_too_large">
</label>
</p>
<p class="description">
<label for="wpcf7-message-upload-failed-php-error">Uploading a file fails for PHP error<br>
<input type="text" id="wpcf7-message-upload-failed-php-error" name="wpcf7-messages[upload_failed_php_error]" class="large-text" size="70" value="There was an error uploading the file." data-config-field="messages.upload_failed_php_error">
</label>
</p>
<p class="description">
<label for="wpcf7-message-invalid-number">Number format that the sender entered is invalid<br>
<input type="text" id="wpcf7-message-invalid-number" name="wpcf7-messages[invalid_number]" class="large-text" size="70" value="The number format is invalid." data-config-field="messages.invalid_number">
</label>
</p>
<p class="description">
<label for="wpcf7-message-number-too-small">Number is smaller than minimum limit<br>
<input type="text" id="wpcf7-message-number-too-small" name="wpcf7-messages[number_too_small]" class="large-text" size="70" value="The number is smaller than the minimum allowed." data-config-field="messages.number_too_small">
</label>
</p>
<p class="description">
<label for="wpcf7-message-number-too-large">Number is larger than maximum limit<br>
<input type="text" id="wpcf7-message-number-too-large" name="wpcf7-messages[number_too_large]" class="large-text" size="70" value="The number is larger than the maximum allowed." data-config-field="messages.number_too_large">
</label>
</p>
<p class="description">
<label for="wpcf7-message-quiz-answer-not-correct">Sender doesn't enter the correct answer to the quiz<br>
<input type="text" id="wpcf7-message-quiz-answer-not-correct" name="wpcf7-messages[quiz_answer_not_correct]" class="large-text" size="70" value="The answer to the quiz is incorrect." data-config-field="messages.quiz_answer_not_correct">
</label>
</p>
<p class="description">
<label for="wpcf7-message-invalid-email">Email address that the sender entered is invalid<br>
<input type="text" id="wpcf7-message-invalid-email" name="wpcf7-messages[invalid_email]" class="large-text" size="70" value="The e-mail address entered is invalid." data-config-field="messages.invalid_email">
</label>
</p>
<p class="description">
<label for="wpcf7-message-invalid-url">URL that the sender entered is invalid<br>
<input type="text" id="wpcf7-message-invalid-url" name="wpcf7-messages[invalid_url]" class="large-text" size="70" value="The URL is invalid." data-config-field="messages.invalid_url">
</label>
</p>
<p class="description">
<label for="wpcf7-message-invalid-tel">Telephone number that the sender entered is invalid<br>
<input type="text" id="wpcf7-message-invalid-tel" name="wpcf7-messages[invalid_tel]" class="large-text" size="70" value="The telephone number is invalid." data-config-field="messages.invalid_tel">
</label>
</p>
</fieldset>
</div></div></div><!-- #contact-form-editor -->

<p class="submit"><input type="submit" class="button-primary" name="wpcf7-save" value="Save" onclick="this.form._wpnonce.value = '19e0f42e94'; this.form.action.value = 'save'; return true;"></p>
</div>

<style>
#editor-tabs {
	display: block;
	width: 100%;
	padding: 0;
	margin: 0;
	background: #eee;
	border: 1px solid #ddd;
}
#editor-tabs li {
	display:inline-block ;
}

#editor-tabs li a {
	display: block;
	padding: 6px 7px;
	text-decoration: none;
	color: #555;
	border: 1px solid #ccc;
}
.editor-panel {
	display: none;
}
.editor-panel.active {
	display: block;
}
</style>
<?php */?>

<?php }

add_action ( 'admin_enqueue_scripts', function () {
	if (is_admin ())
		wp_enqueue_media ();
		wp_enqueue_script('jquery-ui-datepicker');
		//wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
} );

function simple_newsletter_page(){
	
	global $wpdb;
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	if (@fopen($plagin_uri.'export_csv.php', "r")){
		$snsf_allow_export = true;
	}
	else{
	$snsf_allow_export = false;	
	}

	require_once('newsletter-subscriptions-pager.php');
	$simple_p = new Simple_Newsletter_Signup_Pager;
 
	$simple_limit = 10;
	 
	$simple_start = $simple_p->findStart($simple_limit);

	if(isset($_GET['post_status']) && !empty($_GET['post_status'])){
		
		$results_count = $wpdb->get_results("SELECT * FROM tbl_news_letter where status = '".$_GET['post_status']."'");
	}else{
		
		$results_count = $wpdb->get_results("SELECT * FROM tbl_news_letter");	
	}
	
	$simple_count = $wpdb->num_rows;
	
	// to get pulish count 
	$results_publish = $wpdb->get_results("SELECT * FROM tbl_news_letter where status = 'publish'");
	$publish_count = $wpdb->num_rows;
	 
	// to get trash count 
	$results_trash = $wpdb->get_results("SELECT * FROM tbl_news_letter where status = 'trash'");
	$trash_count = $wpdb->num_rows;
	 
	$simple_pages = $simple_p->findPages($simple_count, $simple_limit);
	
	if(isset($_GET['post_status']) && !empty($_GET['post_status'])){
		
		$qry = "SELECT * FROM tbl_news_letter where status = '".$_GET['post_status']."' LIMIT ".$simple_start.", ".$simple_limit;
	}else{
		
		$qry = "SELECT * FROM tbl_news_letter LIMIT ".$simple_start.", ".$simple_limit;	
	}
	 
	$simple_result = $wpdb->get_results($qry);
	$simple_pagelist = $simple_p->pageList($_GET['start'], $simple_pages);

?>
<div class="wrap">

<?php if($simple_count > 0){
if(isset($_POST['export_subscribe_list']) && !empty($_POST['export_subscribe_list'])){
echo snsf_export();	
}
else{
?>
<!--<form name="export_the_list" action="" method="post">
<input type="hidden" name="export_subscribe_list" value="true" />
<input type="submit" value="Export List" class="button" />
</form>	-->
<?php $plagin_uri = WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) );?>
<p class="search-box"><a href="<?php echo $plagin_uri;?>export_csv.php" class="button">Export CSV</a></p>
<?php
}
 }?>
 <h2>Newsletter Subscriptions</h2>
 <!--<ul class="subsubsub">
	<li class="all"><a href="?page=newsletter-subscriptions" class="<?php echo ($_GET['post_status'] == '' ? 'current' : '');?>">All <span class="count">(<?php echo $simple_count;?>)</span></a></li>
	<li class="publish"><a href="?post_status=publish&amp;page=newsletter-subscriptions" <?php echo ($_GET['post_status'] == 'publish' ? 'class="current"' : '');?>>Published <span class="count">(<?php echo $publish_count;?>)</span></a> |</li>
	<li class="trash"><a href="?post_status=trash&amp;page=newsletter-subscriptions" <?php echo ($_GET['post_status'] == 'trash' ? 'class="current"' : '');?>>Trash <span class="count">(<?php echo $trash_count;?>)</span></a></li>
</ul>-->
<div class="tablenav top"><br />
<!--<div class="alignleft actions">
<select name="snsf-action-form" id="snsf-action-form">
<option value="0" selected="selected">Bulk Actions&nbsp;&nbsp;&nbsp;</option>
<option value='delete'>Delete</option>
<!--<option value='edit'>Edit</option>
</select>
<input type="submit" class="button-secondary action" name="snsf-perform-action" id="snsf-perform-action" value="Apply" />
</div>-->
<div class="tablenav-pages">
<span class="displaying-num"><?php echo $simple_count;?> items</span>
<span class="pagination-links"><?php echo $simple_pagelist;?></span>
</div>
</div><form name="snsf-actions-form" id="snsf-actions-form" method="post" action="">
<input type="hidden" name="snsf-the-do-action" id="snsf-the-do-action" />
<table class="wp-list-table widefat pages" cellpadding="2" cellspacing="">
  <thead>
    <tr>
      <!--<th width="20" class="check-column"><input type="checkbox" id="snsf-all-checkboxes" /></th>-->
      <th>S No</th>
      <th>Name</th>
      <th>Email</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
     <!-- <th width="20"> </th>-->
      <th>S No</th>
      <th>Name</th>
      <th>Email</th>
    </tr>
  </tfoot>
  <tbody>
    <?php // START the for each  
   if($simple_count < 1){
	?>
    <tr>
      <td colspan="3" align="center">There is no Subscribers!</td>
    </tr>
    <?php }else{
   
	   $snsf_check_the_rows = 0;
       //while($simple_rows = mysql_fetch_assoc($simple_result))
	foreach($simple_result as $simple_rows){
	?>
    <tr <?php if($snsf_check_the_rows % 2 != 0){ ?>bgcolor="#F2F2F2"<?php } ?>>
    <!--  <td><input type="checkbox" name="snsf-checkbox-value[]" class="all-checkable" value="<?php echo $simple_rows->id;?>" /></td>-->
    	
      <td><?php echo $simple_rows->id; ?></td>
      <td><?php echo ucfirst($simple_rows->name); ?></td>
      <td><?php echo $simple_rows->email;?></td>
    </tr>
    <?php $snsf_check_the_rows++; } // END the for each
   } ?>
   
  </tbody>
</table></form>
<div class="tablenav bottom"><!--<div class="alignleft actions"><select name="snsf-action-form" id="snsf-action-form1">
<option value="0" selected="selected">Bulk Actions&nbsp;&nbsp;&nbsp;</option>
<option value='delete'>Delete</option>
<!--<option value='edit'>Edit</option>
</select>
<input type="submit" class="button-secondary action" name="snsf-perform-action" id="snsf-perform-action1" value="Apply" />
</div>-->
<div class="tablenav-pages">
<span class="displaying-num"><?php echo $simple_count;?> items</span>
<span class="pagination-links">
<?php echo $simple_pagelist;?>
</span>
</div>
</div>
</div>
<?php
}


add_action('init', 'snsf_sessions', 1);
function snsf_sessions() {
    if(!session_id()) {
        session_start();
    }
}


function news_letter($name){

		$plagin_uri = WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) );
		
	?>
	<script type="text/javascript" src="<?php echo $plagin_uri;?>news_subcriber.js"></script>
			<div>
				<?php echo !empty($name) ? $name : 'Newsletter<br/><br/>';?>
				<div id="status"></div>
                <input type="text" name="name" id="name" required="required"/>
				<input type="text" name="newsletter" id="newsletter" required="required"/>&nbsp;&nbsp;&nbsp;&nbsp <a href="javascript:;" onclick="return newslettermail();" style="font-size:18px; font-weight:bold;">Subcriber</a>
				<input type="hidden" id="plugin_url" value="<?php echo $plagin_uri;?>" />
			</div>
	
<?php
}

add_shortcode('NEWSLETTER','news_letter');