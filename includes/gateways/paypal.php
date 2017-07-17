<?php

function donation_pro_paypal(){
			
	add_settings_section(
						'pp_main_section',
						__('Paypal Donations Settings','donation-pro'),
						'',
						'donation_pro_settings'
						); 					
	add_settings_field(
						'pp_check',
						__('Enable : ','donation-pro'),
						'pp_check_settings',
						'donation_pro_settings',
						'pp_main_section'
						);
			
	add_settings_field(
						'pp_testmode',
						__('Testmode : ','donation-pro'),
						'pp_testmode_settings',
						'donation_pro_settings',
						'pp_main_section'
						);
			
	add_settings_field(
						'pp_title',
						__('Title : ','donation-pro'),
						'pp_title_settings',
						'donation_pro_settings',
						'pp_main_section'
						);
			
	add_settings_field(
						'pp_email',
						__('Email : ','donation-pro'),
						'pp_email_settings',
						'donation_pro_settings',
						'pp_main_section'
						);
			
	add_settings_field(
						'pp_currency',
						__('Currency : ','donation-pro'),
						'pp_currency_settings',
						'donation_pro_settings',
						'pp_main_section'
						);
			
	add_settings_field(
						'pp_amt',
						__('Amount : ','donation-pro'),
						'pp_amt_settings',
						'donation_pro_settings',
						'pp_main_section'
						);
			
	add_settings_field(
						'pp_desc',
						__('Description : ','donation-pro'),
						'pp_desc_settings',
						'donation_pro_settings',
						'pp_main_section'
						);
			
}

add_action('add_donation_pro_gateway_settings', 'donation_pro_paypal');

function pp_check_settings() {  
	global $Donation_Pro;   

    echo "<input type='checkbox' name='donation_pro_data[pp_check]' id='pp_check' ".
	checked( 1 , isset($Donation_Pro->options['pp_check'] ),false)." />"; 
}
		
function pp_title_settings(){
	global $Donation_Pro;

	echo "<input name='donation_pro_data[pp_title]' type='text' value='{$Donation_Pro->options['pp_title'] }' />";

}
			
function pp_email_settings(){
		global $Donation_Pro;

		echo "<input name='donation_pro_data[pp_email]' type='email' value='{$Donation_Pro->options['pp_email'] }' />";

}
			
function pp_currency_settings(){
	global $Donation_Pro;

	$pp_curr = array(
					'USD','EUR','GBP','CAD','BRL','HKD','SGD','JPY','CHF','AUD','DKK','SEK',
					'NOK','ILS','MYR','HUF','CZK','NZD','TRY','TWD','PLN','PHP','THB','MXN'
					);

	echo "<select name='donation_pro_data[pp_currency]' value='{$Donation_Pro->options['pp_currency'] }' >";
		foreach ($pp_curr as $value){
			$selected = ($value == $Donation_Pro->options['pp_currency']) ? 'selected="selected"' : '';
			echo "<option {$selected}>$value</option>";
		};
	echo "</select>";

}
		
function pp_amt_settings(){
	global $Donation_Pro;

	echo "<input name='donation_pro_data[pp_amt]' type='number' value='{$Donation_Pro->options['pp_amt'] }' />";	

}
			
function pp_desc_settings(){
	global $Donation_Pro;

	echo "<textarea name='donation_pro_data[pp_desc]'>{$Donation_Pro->options['pp_desc']}</textarea>";

}
			
function pp_testmode_settings(){
	global $Donation_Pro;     

    echo "<input type='checkbox' name='donation_pro_data[pp_testmode]' id='pp_testmode' ".
	checked( 1 , isset($Donation_Pro->options['pp_testmode'] ),false)." />";  

}
		
function paypal_donation_shortcode($atts){
	global $Donation_Pro; 

	extract(shortcode_atts(array(
    							"amount" 	=> $Donation_Pro->options['pp_amt']
								), $atts));

	$content = "";

	if(isset($Donation_Pro->options['pp_testmode'])){
		$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	}

	else{
		$url = 'https://www.paypal.com/cgi-bin/webscr';	
	}

	if(isset($Donation_Pro->options['pp_check'])){
		$content 	.= 	$Donation_Pro->options['pp_desc'].'<br>Donation Amount :  <b>'.
						htmlspecialchars($Donation_Pro->curr_sym[$Donation_Pro->options['pp_currency']]).''.$amount.
						'</b><br><form action="'.$url.'" method="post" target="_blank">
						<input type="hidden" name="cmd" value="_donations">
						<input type="hidden" name="business" value="'.$Donation_Pro->options['pp_email'].'">
						<input type="hidden" name="lc" value="AU">
						<input type="hidden" name="item_name" value="'.$Donation_Pro->options['pp_title'].'">
						<input type="hidden" name="amount" value="'.$amount.'">
						<input type="hidden" value="'.$Donation_Pro->surl.'" name="return">
						<input type="hidden" value="'.$Donation_Pro->curl.'" name="cancel_return">
						<input type="hidden" value="Return to Home" name="cbt">
						<input type="hidden" name="currency_code" value="'.$Donation_Pro->options['pp_currency'].'">
						<input type="hidden" name="no_note" value="0">
						<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
						<input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_donateCC_LG.gif" '.
						'border="0" name="submit" alt="Donate Through PayPal">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
						</form>';	
	}
	
	else{
		$content 	= '<br><div class="error">'.__('Please enable Paypal gateway','donation-pro').'</div><br>';
	}		

	return $content;

}
		
add_shortcode('paypal', 'paypal_donation_shortcode' );		