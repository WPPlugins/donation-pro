<?php

function donation_pro_2checkout(){
			
	add_settings_section(
						'co_main_section',
						__('2Checkout Donations Settings','donation-pro'),
						'',
						'donation_pro_settings'
						);
			
	add_settings_field(
						'co_check',
						__('Enable : ','donation-pro'),
						'co_check_settings',
						'donation_pro_settings',
						'co_main_section'
						);
		
	add_settings_field(
						'co_testmode',
						__('Testmode : ','donation-pro'),
						'co_testmode_settings',
						'donation_pro_settings',
						'co_main_section'
						);
					
	add_settings_field(
						'co_title',
						__('Title : ','donation-pro'),
						'co_title_settings',
						'donation_pro_settings',
						'co_main_section'
						);
		
	add_settings_field(
						'co_sid',
						__('2Checkout account number : ','donation-pro'),
						'co_sid_settings',
						'donation_pro_settings',
						'co_main_section'
						);
		
	add_settings_field(
						'co_currency',
						__('Currency : ','donation-pro'),	
						'co_currency_settings',
						'donation_pro_settings',
						'co_main_section'
						);
		
	add_settings_field(
						'co_amt',
						__('Amount : ','donation-pro'),
						'co_amt_settings',
						'donation_pro_settings',
						'co_main_section'
						);
		
	add_settings_field(
						'co_desc',
						__('Description : ','donation-pro'),
						'co_desc_settings',
						'donation_pro_settings',
						'co_main_section'
						);
		
}
add_action('add_donation_pro_gateway_settings', 'donation_pro_2checkout');

function co_check_settings() { 
	global $Donation_Pro;    

    echo "<input type='checkbox' name='donation_pro_data[co_check]' id='co_check' ".
	checked( 1 , isset($Donation_Pro->options['co_check'] ),false)." />";  
		
}
		
function co_title_settings(){
	global $Donation_Pro;

	echo "<input name='donation_pro_data[co_title]' type='text' value='{$Donation_Pro->options['co_title'] }' />";
		
}
			
function co_sid_settings(){
	global $Donation_Pro;

	echo "<input name='donation_pro_data[co_sid]' type='text' value='{$Donation_Pro->options['co_sid'] }' />";		
		
}
			
function co_currency_settings(){
	global $Donation_Pro;

	$co_curr = array(
					'USD','ARS','AUD','BRL','GBP','CAD','DKK','EUK','HKD','INR','ILS','JPY','LTL',
					'MYR','MXN','NZD','NOK','PHP','RON','RUB','SGD','ZAR','SEK','CHF','TRY','AED'
					);
					
	echo "<select name='donation_pro_data[co_currency]' value='{$Donation_Pro->options['co_currency'] }' >";
		foreach ($co_curr as $value){
			$selected = ($value == $Donation_Pro->options['co_currency']) ? 'selected="selected"' : '';
			echo "<option {$selected}>$value</option>";
		};
	echo "</select>";
		 
}
		
function co_amt_settings(){
	global $Donation_Pro;

	echo "<input name='donation_pro_data[co_amt]' type='number' value='{$Donation_Pro->options['co_amt'] }' />";	
		
}
			
function co_desc_settings(){
	global $Donation_Pro;

	echo "<textarea name='donation_pro_data[co_desc]'>{$Donation_Pro->options['co_desc']}</textarea>";
		
}
			
function co_testmode_settings() {     
    global $Donation_Pro;

	echo "<input type='checkbox' name='donation_pro_data[co_testmode]' id='co_testmode' ".
	checked( 1 , isset($Donation_Pro->options['co_testmode'] ),false)." />";  
		
}
		
function checkout_donation_shortcode($atts){
	global $Donation_Pro;
	
	extract(shortcode_atts(array(
    							"amount" 	=> $Donation_Pro->options['co_amt']
								), $atts));
	
	$content = "";	

	if(isset($Donation_Pro->options['co_testmode'])){
		$demo = 'Y';
	}
	else{
		$demo = 'N';
	}

	if(isset($Donation_Pro->options['co_check'])){
  		$content 	.= 	$Donation_Pro->options['co_desc'].'<br>Donation Amount :  <b>'.
						htmlspecialchars($Donation_Pro->curr_sym[$Donation_Pro->options['co_currency']]).
						''.$amount."</b><br><form action='https://www.2checkout.com/checkout/purchase' method='post' target='_blank'>
		 				<input type='hidden' name='sid' value='$Donation_Pro->options[co_sid]' />
		  				<input type='hidden' name='mode' value='2co' />
		  				<input type='hidden' name='li_0_type' value='product' />
		  				<input type='hidden' name='li_0_name' value='$Donation_Pro->options[co_title]' />
		  				<input type='hidden' name='demo' value='$demo' />
		  				<input type='hidden' name='currency_code' value='$Donation_Pro->options[co_currency]' />
		  				<input type='hidden' name='li_0_price' value='$amount' />
		  				<input type='hidden' name='x_receipt_link_url' value='".$Donation_Pro->surl."' />
		  				<input name='submit' type='submit' value='Donate' />
						</form>";	
	}
	
	else{
		$content 	= 	'<br><div class="error">'.__('Please enable 2Checkout gateway','donation-pro').'</div><br>';
	}
			
	return $content;
		
}
	
add_shortcode('2checkout', 'checkout_donation_shortcode' );		