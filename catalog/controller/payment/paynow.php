<?php
/**
 * catalog/controller/payment/paynow.php
 */
include ('paynow_common.inc');
/**
 * @author Catalog Controller for Sage Pay Now
 *
 */
class ControllerPaymentPayNow extends Controller {
	var $pnHost = '';
	function __construct($registry) {
		parent::__construct ( $registry );
		
		if ($this->config->get ( 'paynow_debug' )) {
			$debug = true;
		} else {
			$debug = false;
		}
		define ( 'PN_DEBUG', $debug );
	}
	
	/**
	 * Page entry
	 */
	protected function index() {
		$config = new Config ();
		
		// session_destroy();
		$this->language->load ( 'payment/paynow' );
		
		$this->data ['button_confirm'] = $this->language->get ( 'button_confirm' );
		
		$this->data ['action'] = 'https://paynow.sagepay.co.za/site/paynow.aspx';
		
		$this->load->model ( 'checkout/order' );
		
		$order_info = $this->model_checkout_order->getOrder ( $this->session->data ['order_id'] );
		
		if ($order_info) {
			
			$order_info ['currency_code'] = 'ZAR';
			$secure = '';
			
			$service_key = $this->config->get ( 'paynow_service_key' );
			$software_vendor_key = '24ade73c-98cf-47b3-99be-cc7b867b3080';
			
			$return_url = HTTP_SERVER . "index.php?route=checkout/success";
			$cancel_url = HTTP_SERVER . "index.php?route=checkout/checkout";
			$notify_url = HTTP_SERVER . "index.php?route=payment/paynow/callback";
			$name_first = html_entity_decode ( $order_info ['payment_firstname'], ENT_QUOTES, 'UTF-8' );
			$name_last = html_entity_decode ( $order_info ['payment_lastname'], ENT_QUOTES, 'UTF-8' );
			$email_address = $order_info ['email'];
			$m_payment_id = $this->session->data ['order_id'];
			$amount = $this->currency->format ( $order_info ['total'], $order_info ['currency_code'], '', false );			
			$item_name = urlencode($this->config->get('config_name') . ' - #' . $this->session->data['order_id']);
			// $custom_str1 = $this->session->data['order_id'];
			
			$payArray = array (
					'm1' => $service_key,
					'm2' => $software_vendor_key,
					'return_url' => $return_url,
					'cancel_url' => $cancel_url,
					'notify_url' => $notify_url,
					'name_first' => $name_first,
					'name_last' => $name_last,
					'email_address' => $email_address,
					'p2' => $m_payment_id,
					'p4' => $amount,
					'p3' => $item_name 
			);
			
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/payment/paynow.tpl' )) {
				$this->template = $this->config->get ( 'config_template' ) . '/template/payment/paynow.tpl';
			} else {
				$this->template = 'default/template/payment/paynow.tpl';
			}
			
			$this->render ();
		}
		pnlog ( "payArray: " . print_r ( $payArray, true ) );
	}
	
	/**
	 * Callback after transaction is verified at Sage Pay Now 
	 */
	public function callback() {
		$pnError = false;
		$pnErrMsg = '';
		$pnDone = false;
		$pnData = array ();
		$pnParamString = '';
		if (isset ( $this->request->post ['Reference'] )) {
			$order_id = $this->request->post ['Reference'];
		} else {
			$order_id = 0;
		}
		
		pnlog ( 'Sage Pay Now IPN call received' );
		
		// Notify Sage Pay Now that information has been received
		if (! $pnError && ! $pnDone) {
			header ( 'HTTP/1.0 200 OK' );
			flush ();
		}
		
		// Get data sent by Sage Pay Now
		if (! $pnError && ! $pnDone) {
			pnlog ( 'Get posted data' );
			
			// Posted variables from IPN
			$pnData = pnGetData ();
			
			pnlog ( 'Sage Pay Now Data: ' . print_r ( $pnData, true ) );
			
			if ($pnData === false) {
				$pnError = true;
				$pnErrMsg = PN_ERR_BAD_ACCESS;
			}
		}
		
		// Get internal cart
		if (! $pnError && ! $pnDone) {
			// Get order data
			$this->load->model ( 'checkout/order' );
			$order_info = $this->model_checkout_order->getOrder ( $order_id );
			
			pnlog ( "Purchase:\n" . print_r ( $order_info, true ) );
		}
		
		// // Check data against internal order
		if (! $pnError && ! $pnDone) {
			pnlog ( 'Check data against internal order' );
			
			$amount = $this->currency->format ( $order_info ['total'], 'ZAR', '', false );
			// Check order amount
			if (! pnAmountsEqual ( $pnData ['amount_gross'], $amount )) {
				$pnError = true;
				$pnErrMsg = PN_ERR_AMOUNT_MISMATCH;
			}
		}
		
		// // Check status and update order
		if (! $pnError && ! $pnDone) {
			pnlog ( 'Check status and update order' );
			
			// TODO Replace pn_payment_id from callback here
			$transaction_id = $pnData ['pn_payment_id'];
			
			switch ($pnData ['payment_status']) {
				case 'COMPLETE' :
					pnlog ( '- Complete' );
					
					// Update the purchase status
					$order_status_id = $this->config->get ( 'paynow_completed_status_id' );
					
					break;
				
				case 'FAILED' :
					pnlog ( '- Failed' );
					
					// If payment fails, delete the purchase log
					$order_status_id = $this->config->get ( 'paynow_failed_status_id' );
					
					break;
				
				case 'PENDING' :
					pnlog ( '- Pending' );
					
					// Need to wait for "Completed" before processing
					break;
				
				default :
					// If unknown status, do nothing (safest course of action)
					break;
			}
			if (! $order_info ['order_status_id']) {
				$this->model_checkout_order->confirm ( $order_id, $order_status_id );
			} else {
				$this->model_checkout_order->update ( $order_id, $order_status_id );
			}
		} else {
			pnlog ( "Errors:\n" . print_r ( $pnErrMsg, true ) );
		}
	}
}
?>
