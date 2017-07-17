<?php

function donation_pro_okpay(){

	add_settings_section(
						'ok_main_section',
						__('OKPay Donations Settings','donation-pro'),
						'',
						'donation_pro_settings'
						); 				

	add_settings_field(
						'ok_check',
						__('Enable : ','donation-pro'),
						'ok_check_settings',
						'donation_pro_settings',
						'ok_main_section'
						);
			
	add_settings_field(
						'ok_title',
						__('Title : ','donation-pro'),
						'ok_title_settings',
						'donation_pro_settings',
						'ok_main_section'
						);
			
	add_settings_field(
						'ok_email',
						__('Email/Phone/WalletID : ','donation-pro'),
						'ok_email_settings',
						'donation_pro_settings',
						'ok_main_section'
						);
			
	add_settings_field(
						'ok_currency',
						__('Currency : ','donation-pro'),
						'ok_currency_settings',
						'donation_pro_settings',
						'ok_main_section'
						);
			
	add_settings_field(
						'ok_amt',
						__('Amount : ','donation-pro'),
						'ok_amt_settings',
						'donation_pro_settings',
						'ok_main_section'
						);
			
	add_settings_field(
						'ok_desc',
						__('Description : ','donation-pro'),
						'ok_desc_settings',
						'donation_pro_settings',
						'ok_main_section'
						);
			
}
		
add_action('add_donation_pro_gateway_settings', 'donation_pro_okpay');

function ok_check_settings() { 
	global $Donation_Pro;    

    echo "<input type='checkbox' name='donation_pro_data[ok_check]' id='ok_check' ".
	checked( 1 , isset($Donation_Pro->options['ok_check'] ),false)." />"; 

}
		
function ok_title_settings(){
	global $Donation_Pro;

	echo "<input name='donation_pro_data[ok_title]' type='text' value='{$Donation_Pro->options['ok_title'] }' />";

}
			
function ok_email_settings(){
	global $Donation_Pro;

	echo "<input name='donation_pro_data[ok_email]' type='text' value='{$Donation_Pro->options['ok_email'] }' />";

}
			
function ok_currency_settings(){
	global $Donation_Pro;

	$ok_curr = array(
					'USD','EUR','GBP','CHF','AUD','PLN','JPY',
					'ILS','DKK','CAD','RUB','CZK','MYR','MXN',
					'NOK','NZD','PHP','SGD','TWD','CNY','NGN'
					);

	echo "<select name='donation_pro_data[ok_currency]' value='{$Donation_Pro->options['ok_currency'] }' >";
		foreach ($ok_curr as $value){
			$selected = ($value == $Donation_Pro->options['ok_currency']) ? 'selected="selected"' : '';
			echo "<option {$selected}>$value</option>";
		};
	echo "</select>";

}
		
function ok_amt_settings(){
	global $Donation_Pro;

	echo "<input name='donation_pro_data[ok_amt]' type='number' value='{$Donation_Pro->options['ok_amt'] }' />";	

}
			
function ok_desc_settings(){
	global $Donation_Pro;

	echo "<textarea name='donation_pro_data[ok_desc]'>{$Donation_Pro->options['ok_desc']}</textarea>";

}	
		
function okpay_donation_shortcode($atts){
	global $Donation_Pro;

	extract(shortcode_atts(array(
    							"amount" 	=> $Donation_Pro->options['ok_amt']
								), $atts));
	
	$content = "";

	if(isset($Donation_Pro->options['ok_check'])){
		$content	.=	$Donation_Pro->options['ok_desc'].'<br>Donation Amount :  <b>'.
						htmlspecialchars($Donation_Pro->curr_sym[$Donation_Pro->options['ok_currency']]).
						''.$amount."</b><a target='_blank' href=\"https://www.okpay.com/process.html?cmd=_xclick&ok_receiver=".
						$Donation_Pro->options['ok_email']."&ok_currency=".$Donation_Pro->options['ok_currency']."&ok_item_1_price=".$amount.
						"&return=".$Donation_Pro->surl."&ok_item_1_name=".$Donation_Pro->options['ok_title'].
						"\"><br><img src='https://www.okpay.com/img/buttons/en/donate/d14o186x54en.png'></a></p>";
	}
	
	else{
		$content = '<br><div class="error">'.__('Please enable Okpay gateway','donation-pro').'</div><br>';	
	}	

	return $content;

}
		
add_shortcode('okpay', 'okpay_donation_shortcode' );	