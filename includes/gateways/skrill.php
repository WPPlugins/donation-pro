<?php

function donation_pro_skrill(){
			
	add_settings_section(
						'mb_main_section',
						__('Skrill Donations Settings','donation-pro'),
						'',
						'donation_pro_settings'
						); 				
			
	add_settings_field(
						'mb_check',
						__('Enable : ','donation-pro'),
						'mb_check_settings',
						'donation_pro_settings',
						'mb_main_section'
						);
			
	add_settings_field(
						'mb_title',
						__('Title : ','donation-pro'),
						'mb_title_settings',
						'donation_pro_settings',
						'mb_main_section'
						);
			
	add_settings_field(
						'mb_email',
						__('Email : ','donation-pro'),
						'mb_email_settings',
						'donation_pro_settings',
						'mb_main_section'
						);
			
	add_settings_field(
						'mb_currency',
						__('Currency : ','donation-pro'),
						'mb_currency_settings',
						'donation_pro_settings',
						'mb_main_section'
						);
		
	add_settings_field(
						'mb_amt',
						__('Amount : ','donation-pro'),	
						'mb_amt_settings',
						'donation_pro_settings',
						'mb_main_section'
						);
			
	add_settings_field(
						'mb_desc',
						__('Description : ','donation-pro'),
						'mb_desc_settings',
						'donation_pro_settings',
						'mb_main_section'
						);
			
}
		
add_action('add_donation_pro_gateway_settings', 'donation_pro_skrill');

function mb_check_settings(){
	global $Donation_Pro;   
    
	echo "<input type='checkbox' name='donation_pro_data[mb_check]' id='mb_check' ".
	checked( 1 , isset($Donation_Pro->options['mb_check'] ),false)." />";  

}
		
function mb_title_settings(){
	global $Donation_Pro;
	
	echo "<input name='donation_pro_data[mb_title]' type='text' value='{$Donation_Pro->options['mb_title'] }' />";

}
			
function mb_email_settings(){
	global $Donation_Pro;
	
	echo "<input name='donation_pro_data[mb_email]' type='email' value='{$Donation_Pro->options['mb_email'] }' />";

}
			
function mb_currency_settings(){
	global $Donation_Pro;
	
	$mb_curr = array(
					'USD','EUR','GBP','HKD','SGD','JPY','CAD','CHF','AUD','DKK','SEK','NOK','ILS',
					'MYR','NZD','TRY','MAD','QAR','SAR','TWD','THB','CZK','HUF','SKK','EEK','BGN',
					'PLN','ISK','INR','LVL','KRW','ZAR','RON','HRK','LTL','JOD','OMR','RSD','TND'
					);					
	
	echo "<select name='donation_pro_data[mb_currency]' value='{$Donation_Pro->options['mb_currency'] }' >";
		foreach ($mb_curr as $value){
			$selected = ($value == $Donation_Pro->options['mb_currency']) ? 'selected="selected"' : '';
			echo "<option {$selected}>$value</option>";
		};
	echo "</select>";

}
		
function mb_amt_settings(){
	global $Donation_Pro;
	
	echo "<input name='donation_pro_data[mb_amt]' type='number' value='{$Donation_Pro->options['mb_amt'] }' />";	

}
			
function mb_desc_settings(){
	global $Donation_Pro;
	
	echo "<textarea name='donation_pro_data[mb_desc]'>{$Donation_Pro->options['mb_desc']}</textarea>";

}	
		
function skrill_donation_shortcode($atts){
	global $Donation_Pro;
	
	extract(shortcode_atts(array(
    							"amount" 	=> $Donation_Pro->options['mb_amt']
								), $atts));
	
	$content = "";

	if(isset($Donation_Pro->options['mb_check'])){
		$content 	.= 	$Donation_Pro->options['mb_desc'].'<br>Donation Amount :  <b>'.
						htmlspecialchars($Donation_Pro->curr_sym[$Donation_Pro->options['mb_currency']]).''.$amount.
						'</b><form target="_blank" method="post" action="https://www.moneybookers.com/app/payment.pl">
	    				<input type="hidden" value="'.$Donation_Pro->options['mb_email'].'" name="pay_to_email"></input>
	    				<input type="hidden" value="'.$Donation_Pro->options['mb_email'].'" name="status_url"></input>
	    				<input type="hidden" value="EN" name="language"></input>
	    				<input type="hidden" value="'.$Donation_Pro->options['mb_currency'].'" name="currency"></input>
	    				<input type="hidden" value="'.$Donation_Pro->options['mb_title'].'" name="detail1_description"></input>
	    				<input type="hidden" value="'.$Donation_Pro->options['mb_desc'].'" name="detail1_text"></input>
	    				<input type="hidden" value="'.$Donation_Pro->surl.'" name="return_url"></input>
	    				<input type="hidden" value="'.$Donation_Pro->curl.'" name="cancel_url"></input>
	    				<input type="hidden" value="Return To Home Site" name="return_url_text"></input>
	    				<input type="hidden" name="amount" value="'.$amount.'"></input>
	    				<input type="submit" value="Donate" />
						</form>';
	}

	else{
		$content = '<br><div class="error">'.__('Please enable Moneybookers/Skrill gateway','donation-pro').'</div><br>';
	}

	return $content;

}	
			
add_shortcode('skrill', 'skrill_donation_shortcode' );	