<?php
/**
 * Epay.bg
 */

class CampTix_Payment_Method_EpayBG extends CampTix_Payment_Method {

	/**
	 * The following variables are required for every payment method.
	 */
	public $id = 'epaybg';
	public $name = 'Epay.bg';
	public $description = 'Epay.bg Standard Checkout';
	public $supported_currencies = array( 'EUR' );
	
	const EUR_TO_BGN = 2;
	
	function camptix_init() {
		$this->options = array_merge( array( 'secret' => '', 'min' => '', 'url_ok' => '', 'url_cancel' => '', 'sandbox' => true ), $this->get_payment_options() );
		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
	}
	
	function payment_settings_fields() {
		$this->add_settings_field_helper( 'secret', 'Secret', array( $this, 'field_text' ) );
		$this->add_settings_field_helper( 'min', 'MIN', array( $this, 'field_text' ) );
		$this->add_settings_field_helper( 'url_ok', 'URL to redirect on success', array( $this, 'field_text' ) );
		$this->add_settings_field_helper( 'url_cancel', 'URL to redirect after failure', array( $this, 'field_text' ) );		
		$this->add_settings_field_helper( 'sandbox', __( 'Demo Mode', 'camptix' ), array( $this, 'field_yesno' ) );
	}
	
	function validate_options( $input ) {
		$output = array_merge( $this->options, $input );
		if ( isset( $output['sandbox'] ) ) {
			$output['sandbox'] = (bool)$output['sandbox'];
		}
		return $output;
	}

	function payment_checkout( $payment_token ) {
		$order = $this->get_order( $payment_token );
		$order['token'] = $payment_token;
		return $this->form_to_epay( $order );
	}
	
	function template_redirect() {
		if ( 'epay-response' == get_query_var( 'tix_action' ) || ( isset( $_REQUEST['encoded'] ) && isset( $_REQUEST['checksum'] ) ) ) {
			$this->payment_process_response();
		}
	}
	
	function payment_process_response() {
		$encoded  = $_REQUEST['encoded'];
		$checksum = $_REQUEST['checksum'];
		$secret = $this->options['secret'];
		$hmac   = $this->hmac( 'sha1', $encoded, $secret );
		if ( $hmac != $checksum ) {
			die( "ERR=Not valid CHECKSUM\n" );
		}
		$data = base64_decode( $encoded );
	    $lines = split( "\n", $data );

		foreach( $lines as $line ) {
			$line = trim( $line );
			if ( !preg_match('/^INVOICE=(\d+):STATUS=(PAID|DENIED|EXPIRED)(\:PAY_TIME=(\d+):STAN=(\d+):BCODE=([0-9a-zA-Z]+))?$/', $line, $match ) ) {
				continue;
			}
			$attendee_id = $match[1];
			$order = $this->get_order_by_attendee_id( $attendee_id );
			if ( !$order ) {
				echo "INVOICE=$attendee_id:STATUS=NO\n";
				continue;
			}
			$token = get_post_meta( $attendee_id, 'tix_payment_token', true );
			if ( !$token ) {
				error_log( "Missing toeken for attendee '$attendee_id'." );
				echo "INVOICE=$attendee_id:STATUS=ERR\n";
				continue;
			}
			$details = array();
			$code = '';
			switch ( $match[2] ) {
				case 'PAID':
					$code = CampTix_Plugin::PAYMENT_STATUS_COMPLETED;
					$details = array(
						'transaction_details' => array(
							'date' => $match[4],
							'stan' => $match[5],
							'bcode' => $match[6],
						),
						'transaction_id' => $match[5],
					);
					break;
				case 'DENIED':
					$code = CampTix_Plugin::PAYMENT_STATUS_FAILED;
					break;
				case 'EXPIRED':
					$code = CampTix_Plugin::PAYMENT_STATUS_TIMEOUT;
					break;
				default:
					error_log( "Unknown epay.bg status: {$match[2]} in $line" );
			}
			if ( $code ) {
				$this->payment_result( $token, $code, $details, 'no-redirect' );
			}
			echo "INVOICE=$attendee_id:STATUS=OK\n";			
		}
		exit;
	}
	
	function form_to_epay( $order ) {
		ob_start();
		$payload = $this->get_payload( $order );
?>
<p>За да платите през Epay.bg, моля натиснете този бутон. Той ще ви отведе в сайта на Epay, където ще може да платите билетите си:</p>

<form action="<?php echo $this->options['sandbox']? 'https://devep2.datamax.bg/ep2/epay2_demo/' : 'https://www.epay.bg/'; ?>" method=post>
	<input type=hidden name=PAGE value="paylogin">
	<input type=hidden name=ENCODED value="<?php echo $payload['encoded']; ?>">
	<input type=hidden name=CHECKSUM value="<?php echo $payload['checksum']; ?>">
<?php if ( $this->options['url_ok'] ): ?>
	<input type=hidden name=URL_OK value="<?php echo esc_url( $this->options['url_ok']); ?>">
<?php endif; ?>
<?php if ( $this->options['url_cancel'] ): ?>
	<input type=hidden name=URL_CANCEL value="<?php echo esc_url( $this->options['url_cancel']); ?>">
<?php endif; ?>
	<input type="submit" value="Към Epay.bg →" />
</form>
<?php
		return ob_get_clean();
	}
	
	function get_payload( $order ) {
		$secret     = $this->options['secret'];

		$min        = $this->options['min'];
		$invoice    = sprintf("%.0f", $order['attendee_id']);
		$sum        = $order['total'] * self::EUR_TO_BGN;
		$exp_date   = date('d.m.Y', strtotime('+1day'));
		$descr      = $order['items'][0]['description'];

		$data = <<<DATA
MIN={$min}
INVOICE={$invoice}
AMOUNT={$sum}
EXP_TIME={$exp_date}
DESCR={$descr}
ENCODING=utf-8
DATA;

		$result = array();
		$result['encoded']  = base64_encode($data);
		$result['checksum'] = $this->hmac('sha1', $result['encoded'], $secret);
		return $result;
	}
	
	function hmac($algo,$data,$passwd){
	        /* md5 and sha1 only */
	        $algo=strtolower($algo);
	        $p=array('md5'=>'H32','sha1'=>'H40');
	        if(strlen($passwd)>64) $passwd=pack($p[$algo],$algo($passwd));
	        if(strlen($passwd)<64) $passwd=str_pad($passwd,64,chr(0));

	        $ipad=substr($passwd,0,64) ^ str_repeat(chr(0x36),64);
	        $opad=substr($passwd,0,64) ^ str_repeat(chr(0x5C),64);

	        return($algo($opad.pack($p[$algo],$algo($ipad.$data))));
	}
}

camptix_register_addon( 'CampTix_Payment_Method_EpayBG' ); 
