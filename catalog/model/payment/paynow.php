<?php 
/**
 * catalog/model/payment/paynow.php
 */

class ModelPaymentPayNow extends Model {
    public function getMethod($address, $total) {
        $this->load->language('payment/paynow');
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('paynow_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
        
        if ($this->config->get('paynow_total') > $total) {
            $status = false;
        } elseif (!$this->config->get('paynow_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }   
        $this->load->model('localisation/currency');

        $supportedCurrencies = $this->model_localisation_currency->getCurrencies();

        $currencies = array_keys($supportedCurrencies);
        
        if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
            $status = false;
        }           
                    
        $method_data = array();
    
        if ($status) {  
            $method_data = array( 
                'code'       => 'paynow',
                'title'      => $this->language->get('text_pay_method').$this->language->get('text_logo'),
                'sort_order' => $this->config->get('paynow_sort_order')
            );
        }
   
        return $method_data;
    }
}
?>