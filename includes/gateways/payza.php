<?php

function donation_pro_payza(){
			
	add_settings_section(
						'payza_main_section',
						__('Payza Donations Settings','donation-pro'),
						'',
						'donation_pro_settings'
						); 						
			
	add_settings_field(
						'payza_check',
						__('Enable : ','donation-pro'),
						'payza_check_settings',
						'donation_pro_settings',
						'payza_main_section'
						);
			
	add_settings_field(
						'payza_testmode',
						__('Testmode : ','donation-pro'),
						'payza_testmode_settings',
						'donation_pro_settings',
						'payza_main_section'
						);
			
	add_settings_field(
						'payza_title',
						__('Title : ','donation-pro'),
						'payza_title_settings',
						'donation_pro_settings',
						'payza_main_section'
						);
			
	add_settings_field(
						'payza_email',
						__('Email : ','donation-pro'),
						'payza_email_settings',
						'donation_pro_settings',
						'payza_main_section'
						);
			
	add_settings_field(
						'payza_currency',
						__('Currency : ','donation-pro'),
						'payza_currency_settings',
						'donation_pro_settings',
						'payza_main_section'
						);
			
	add_settings_field(
						'payza_amt',
						__('Amount : ','donation-pro'),
						'payza_amt_settings',
						'donation_pro_settings',
						'payza_main_section'
						);
			
	add_settings_field(
						'payza_desc',
						__('Description : ','donation-pro'),
						'payza_desc_settings',
						'donation_pro_settings',
						'payza_main_section'
						);
			
}
		
add_action('add_donation_pro_gateway_settings', 'donation_pro_payza');

function payza_check_settings(){ 
	global $Donation_Pro;    

    echo "<input type='checkbox' name='donation_pro_data[payza_check]' id='payza_check' ".
	checked( 1 ,isset($Donation_Pro->options['payza_check'] ),false)." />";  
}
		
function payza_title_settings(){
	global $Donation_Pro;

	echo "<input name='donation_pro_data[payza_title]' type='text' value='{$Donation_Pro->options['payza_title'] }' />";
}
			
function payza_email_settings(){
	global $Donation_Pro;

	echo "<input name='donation_pro_data[payza_email]' type='email' value='{$Donation_Pro->options['payza_email'] }' />";

}
			
function payza_currency_settings(){
	global $Donation_Pro;

	$payza_curr = array(
						'AUD','BGN','CAD','CHF','CZK','DKK','EEK','EUR','GBP','HKD','HUF',
						'LTL','MYR','MKD','NOK','NZD','PLN','RON','SEK','SGD','USD','ZAR'
						);

	echo "<select name='donation_pro_data[payza_currency]' value='{$Donation_Pro->options['payza_currency'] }' >";
		foreach ($payza_curr as $value){
			$selected = ($value == $Donation_Pro->options['payza_currency']) ? 'selected="selected"' : '';
			echo "<option {$selected}>$value</option>";
		};
	echo "</select>";

}
		
function payza_amt_settings(){
	global $Donation_Pro;

	echo "<input name='donation_pro_data[payza_amt]' type='number' value='{$Donation_Pro->options['payza_amt'] }' />";	

}
		
function payza_desc_settings(){
	global $Donation_Pro;

	echo "<textarea name='donation_pro_data[payza_desc]'>{$Donation_Pro->options['payza_desc']}</textarea>";

}	
			
function payza_testmode_settings() {
	global $Donation_Pro;     
    
	echo "<input type='checkbox' name='donation_pro_data[payza_testmode]' id='payza_testmode' ".
	checked( 1 , isset($Donation_Pro->options['payza_testmode'] ),false).
	" />";  

}
		
function payza_donation_shortcode($atts){
	global $Donation_Pro; 
	
	extract(shortcode_atts(array(
    							"amount" 	=> $Donation_Pro->options['payza_amt']
								), $atts));
	
	$content = "";

	if(isset($Donation_Pro->options['payza_testmode'])){
		$url = 'https://sandbox.payza.com/sandbox/payprocess.aspx';
	}
	
	else
		$url = 'https://secure.payza.com/checkout';

	if(isset($Donation_Pro->options['payza_check'])){
		$content 	.= 	$Donation_Pro->options['payza_desc'].'<br>Donation Amount :  <b>'.
						htmlspecialchars($Donation_Pro->curr_sym[$Donation_Pro->options['payza_currency']]).
						''.$amount.'</b><br><form method="post" action="'.$url.'" target="_blank">
	    				<input type="hidden" name="ap_merchant" value="'.$Donation_Pro->options['payza_email'].'"/>
	   					<input type="hidden" name="ap_purchasetype" value="item"/>
	    				<input type="hidden" name="ap_itemname" value="'.$Donation_Pro->options['payza_title'].'"/>
	    				<input type="hidden" name="ap_amount" value="'.$amount.'"/>
	    				<input type="hidden" name="ap_currency" value="'.$Donation_Pro->options['payza_currency'].'"/>
	    				<input type="hidden" name="ap_description" value="'.$Donation_Pro->options['payza_desc'].'"/>
	    				<input type="hidden" name="ap_returnurl" value="'.$Donation_Pro->surl.'"/>
	    				<input type="hidden" name="ap_cancelurl" value="'.$Donation_Pro->curl.'"/>
	     				<input type="image" src="https://secure.payza.com/PayNow/BA7E252183D34765B944D3BCEC831106d0en.gif"/>
						</form>';
	}		
	
	else{
		$content = '<br><div class="error">'.__('Please enable Payza gateway','donation-pro').'</div><br>';
	}

	return $content;

}
		
add_shortcode('payza', 'payza_donation_shortcode' );		