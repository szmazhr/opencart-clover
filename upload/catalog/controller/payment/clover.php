<?php
class ControllerPaymentClover extends Controller {
	public function index() {
		$this->load->language('payment/clover');

		$data['text_credit_card'] = $this->language->get('text_credit_card');

		$data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$data['entry_cc_cvv'] = $this->language->get('entry_cc_cvv');
		$data['entry_cc_postal'] = $this->language->get('entry_cc_postal');
		$data['button_confirm'] = $this->language->get('button_confirm');
		
		$data['clover_pub_key'] = $this->config->get('clover_pub_key');

		$data['months'] = array();
		
		$data['polyfill'] = 'https://cdn.polyfill.io/v3/polyfill.min.js';

        if($this->config->get('clover_test')){
		    $data['text_testing'] = $this->language->get('text_testing');
		    
		    // Testing sdk clover
		    $data['clover_sdk'] = 'https://checkout.sandbox.dev.clover.com/sdk.js';
        }else{
            // Production sdk clover
            $data['clover_sdk'] = 'https://checkout.clover.com/sdk.js';
        }

		return $this->load->view('payment/clover', $data);
	}

	public function send() {
	    $this->load->language('payment/clover');
		if ($this->config->get('clover_test')) {
			$url = 'https://scl-sandbox.dev.clover.com/v1/charges';
		} else{
			$url = 'https://scl-sandbox.dev.clover.com/v1/charges';
		}

		//$url = 'https://secure.networkmerchants.com/gateway/transact.dll';

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);



		$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);

		

		$curl = curl_init();
					
		$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'Authorization: Bearer ' . $this->config->get('clover_pri_key');
		$headers[] = 'Content-Type: application/json';
		
		$body = json_encode(array(
			'ecomind' => 'ecom',
			'amount' => $amount * 100,
			'currency' => strtolower($this->session->data['currency']),
			'source' => $this->request->post['cloverToken'],
			'external_reference_id' => $this->session->data['order_id'],
		));

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $body);


		$response = json_decode(curl_exec($curl));

        
        if ($this->config->get('clover_debug')) {
				$this->log->write('CLOVER :: CURL BODY: ' . $body);
				$this->log->write('CLOVER :: CURL RESPONSE: ' . $response);
		}
		
		$json = array();

		if (curl_error($curl)) {
		    
			$this->log->write('CLOVER :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
			$json['error'] = 'CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl);

		} elseif ($response) {


			if (isset($response->paid) && $response->paid == true) {
			    $order_status_id = $this->config->get('clover_completed_status_id');
			    $json['redirect'] = $this->url->link('checkout/success', '', true);
			    
			}else if(isset($response->error)){
			    
			    $order_status_id = $this->config->get('clover_failed_status_id');
			    $json['error'] = $response->error->message . ' ' . $this->language->get('text_try_again');
			}
			    


			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id);
		} else {
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));
			$json['error'] = 'Empty Gateway Response';
			$this->log->write('CLOVER CURL ERROR: Empty Gateway Response');
		}

	    
		curl_close($curl);

		$this->response->addHeader('Content-Type: application/json');
 		$this->response->setOutput(json_encode($json));
		//$this->response->setOutput($response);
	}
}