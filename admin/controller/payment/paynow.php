<?php
/**
 * admin/controller/payment/paynow.php
 */

class ControllerPaymentPayNow extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/paynow');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paynow', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_sale'] = $this->language->get('text_sale');

		$this->data['entry_sandbox'] = $this->language->get('entry_sandbox');
		$this->data['entry_debug'] = $this->language->get('entry_debug');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_completed_status'] = $this->language->get('entry_completed_status');
		$this->data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$this->data['entry_cancelled_status'] = $this->language->get('entry_cancelled_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');        
        $this->data['entry_service_key'] = $this->language->get('entry_service_key');
        $this->data['text_debug'] = $this->language->get('text_debug');
        
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/paynow', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('payment/paynow', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
        if (isset($this->request->post['paynow_service_key'])) {
			$this->data['paynow_service_key'] = $this->request->post['paynow_service_key'];
		} else {
			$this->data['paynow_service_key'] = $this->config->get('paynow_service_key');
		}

		if (isset($this->request->post['paynow_transaction'])) {
			$this->data['paynow_transaction'] = $this->request->post['paynow_transaction'];
		} else {
			$this->data['paynow_transaction'] = $this->config->get('paynow_transaction');
		}

		if (isset($this->request->post['paynow_debug'])) {
			$this->data['paynow_debug'] = $this->request->post['paynow_debug'];
		} else {
			$this->data['paynow_debug'] = $this->config->get('paynow_debug');
		}
		
		if (isset($this->request->post['paynow_total'])) {
			$this->data['paynow_total'] = $this->request->post['paynow_total'];
		} else {
			$this->data['paynow_total'] = $this->config->get('paynow_total'); 
		} 
		
		if (isset($this->request->post['paynow_completed_status_id'])) {
			$this->data['paynow_completed_status_id'] = $this->request->post['paynow_completed_status_id'];
		} else {
			$this->data['paynow_completed_status_id'] = $this->config->get('paynow_completed_status_id');
		}	
						
		if (isset($this->request->post['paynow_failed_status_id'])) {
			$this->data['paynow_failed_status_id'] = $this->request->post['paynow_failed_status_id'];
		} else {
			$this->data['paynow_failed_status_id'] = $this->config->get('paynow_failed_status_id');
		}	
								
		if (isset($this->request->post['paynow_cancelled_status_id'])) {
			$this->data['paynow_cancelled_status_id'] = $this->request->post['paynow_cancelled_status_id'];
		} else {
			$this->data['paynow_cancelled_status_id'] = $this->config->get('paynow_cancelled_status_id');
		}
		
		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['paynow_geo_zone_id'])) {
			$this->data['paynow_geo_zone_id'] = $this->request->post['paynow_geo_zone_id'];
		} else {
			$this->data['paynow_geo_zone_id'] = $this->config->get('paynow_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paynow_status'])) {
			$this->data['paynow_status'] = $this->request->post['paynow_status'];
		} else {
			$this->data['paynow_status'] = $this->config->get('paynow_status');
		}
		
		if (isset($this->request->post['paynow_sort_order'])) {
			$this->data['paynow_sort_order'] = $this->request->post['paynow_sort_order'];
		} else {
			$this->data['paynow_sort_order'] = $this->config->get('paynow_sort_order');
		}
        
        
		$this->template = 'payment/paynow.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paynow')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>