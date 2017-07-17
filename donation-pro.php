<?php
/* 
Plugin Name: Donation Pro 
Plugin URI: http://www.patsatech.com 
Description: Wordpress plugin for accepting donations from visitors of your website
Author: PatSaTECH, Rohit Mane
Version: 1.0 
Author URI: http://www.patsatech.com 
Text Domain: donation-pro 
Domain Path: /lang/
*/
	
if(!class_exists('Donation_Pro')) {

	include_once('includes/gateways/paypal.php');

	include_once('includes/gateways/payza.php');

	include_once('includes/gateways/2checkout.php');

	include_once('includes/gateways/okpay.php');

	include_once('includes/gateways/skrill.php');

	load_plugin_textdomain('donation-pro', false, dirname( plugin_basename( 'donation_pro_settings' ) ) . '/lang');

	class Donation_Pro {
	
		public $options,$surl,$curl,$curr_sym;
		
	    public function __construct(){
	
			$this->options 	= get_option( 'donation_pro_data' ); 
			$this->surl 	= get_home_url();
			$this->curl 	= get_home_url();
			if(isset($this->options['return_rurl'])){
				$this->surl = $this->options['return_rurl'];
			}
	
			if(isset($this->options['return_curl'])){
				$this->curl = $this->options['return_curl'];
			}
				
			$this->curr_sym = array(
									'ALL' => 'Lek',
									'AFN' => '؋',
									'ARS' => '$',
									'AUD' => '$',
									'AZN' => 'ман',
									'BSD' => '$',
									'BBD' => '$',
									'BYR' => 'p.',
									'BZD' => 'BZ$',
									'BMD' => '$',
									'BOB' => '$b',
									'BAM' => 'KM',
									'BWP' => 'P',
									'BGN' => 'лв',
									'BRL' => 'R$',
									'BND' => '$',
									'KHR' => '៛',
									'CAD' => '$',
									'KYD' => '$',
									'CLP' => '$',
									'CNY' => '¥',
									'coP' => '$',
									'CRC' => '₡',
									'HRK' => 'kn',
									'CUP' => '₱',
									'CZK' => 'Kč',
									'DKK' => 'kr',
									'DOP' => 'RD$',
									'XCD' => '$',
									'EGP' => '£',
									'SVC' => '$',
									'EEK' => 'kr',
									'EUR' => '€',
									'FKP' => '£',
									'FJD' => '$',
									'GHC' => '¢',
									'GIP' => '£',
									'GTQ' => 'Q',
									'GGP' => '£',
									'GYD' => '$',
									'HNL' => 'L',
									'HKD' => '$',
									'HUF' => 'Ft',
									'ISK' => 'kr',
									'INR' => 'Rs',
									'IDR' => 'Rp',
									'IRR' => '﷼',
									'IMP' => '£',
									'ILS' => '₪',
									'JMD' => 'J$',
									'JPY' => '¥',
									'JEP' => '£',
									'KZT' => 'лв',
									'KPW' => '₩',
									'KRW' => '₩',
									'KGS' => 'лв',
									'LAK' => '₭',
									'LVL' => 'Ls',
									'LBP' => '£',
									'LRD' => '$',
									'LTL' => 'Lt',
									'MKD' => 'ден',
									'MYR' => 'RM',
									'MUR' => '₨',
									'MXN' => '$',
									'MNT' => '₮',
									'MZN' => 'MT',
									'NAD' => '$',
									'NPR' => '₨',
									'ANG' => 'ƒ',
									'NZD' => '$',
									'NIO' => 'C$',
									'NGN' => '₦',
									'NOK' => 'kr',
									'OMR' => '﷼',
									'PKR' => '₨',
									'PAB' => 'B/.',
									'PYG' => 'Gs',
									'PEN' => 'S/.',
									'PHP' => '₱',
									'PLN' => 'zł',
									'QAR' => '﷼',
									'RON' => 'lei',
									'RUB' => 'руб',
									'SHP' => '£',
									'SAR' => '﷼',
									'RSD' => 'Дин.',
									'SCR' => '₨',
									'SGD' => '$',
									'SBD' => '$',
									'SOS' => 'S',
									'ZAR' => 'S',
									'LKR' => '₨',
									'SEK' => 'kr',
									'CHF' => 'CHF',
									'SRD' => '$',
									'SYP' => '£',
									'TWD' => 'NT$',
									'THB' => '฿',
									'TTD' => 'TT$',
									'TRL' => '₤',
									'TVD' => '$',
									'UAH' => '₴',
									'GBP' => '£',
									'USD' => '$',
									'UYU' => '$U',
									'UZS' => 'лв',
									'VEF' => 'Bs',
									'VND' => '₫',
									'YER' => '﷼',
									'ZWD' => 'Z$'
									);
			
	        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
	        add_action( 'admin_init', array( $this, 'register_setting_and_fields' ));
			add_action( 'wp_enqueue_scripts', array( $this, 'donation_pro_style' ));
	    }
	
	 	public function donation_pro_style() {
		
			wp_enqueue_style( 'Donation-Pro-Style', plugins_url('/assets/style.css', __FILE__) );
		
		}
	 
	    public function add_menu_page(){	
		
			if(!function_exists('current_user_can') || !current_user_can('manage_options'))
				return;
		
		   	if( function_exists( 'add_options_page')){
				add_options_page(
		           	'Donation Pro', 
		           	'Donation Options', 
		           	'manage_options', 
		           	'donation_pro_settings', 
		           	array( $this, 'display_option_page' )
		       	);	
			}
	   	}
	    
	   	public function display_option_page(){
		
	       	$this->options = get_option( 'donation_pro_data' ); 
			?>
				<div class="wrap">
					<?php screen_icon(); ?>
					<h2>Donation Settings</h2>
					<form action="options.php" method="post" enctype="multipart/form-data"> 
						<?php 
							settings_fields( 'donation_pro_group' );
						 	do_settings_sections( 'donation_pro_settings' ); 
						?>
						<p class="submit">
							<input name="submit" type="submit" class="button-primary" value="Save" />
						</p>
					</form>						
				</div>
			<?php
	   	}
	
	   	public function register_setting_and_fields(){
			 	
			register_setting(
				'donation_pro_group',
				'donation_pro_data'
			);
			    		
			do_action('add_donation_pro_gateway_settings');
				
			add_settings_section(
				'return_main_section',
				__('Return Page Settings','donation-pro'),
				'',
				'donation_pro_settings'
			);
				
			add_settings_field(
				'return_rurl',
				__('Thank You Page : ','donation-pro'),
				array($this,'return_rurl_settings'),
				'donation_pro_settings',
				'return_main_section'
			);
				
			add_settings_field(
				'return_curl',
				__('Cancelled Page : ','donation-pro'),
				array($this,'return_curl_settings'),
				'donation_pro_settings',
				'return_main_section'
			);
		}	 	
	
		public function return_rurl_settings(){
		
			echo "<input name='donation_pro_data[return_rurl]' type='text' value='{$this->options['return_rurl'] }' ".
			" placeholder='Enter complete Url'  style='width: 200px;' />";
		
		}
				
		public function return_curl_settings(){
	
			echo "<input name='donation_pro_data[return_curl]' type='text' value='{$this->options['return_curl'] }'".
			" placeholder='Enter complete Url'  style='width: 200px;' />";
		
		}	
	
	}
	$GLOBALS['Donation_Pro'] = new Donation_Pro();
}