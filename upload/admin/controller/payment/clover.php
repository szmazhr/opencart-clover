<?php
class ControllerPaymentClover extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/clover');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('clover', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_sale'] = $this->language->get('text_sale');

		$data['entry_public'] = $this->language->get('entry_public');
		$data['entry_private'] = $this->language->get('entry_private');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_canceled_reversal_status'] = $this->language->get('entry_canceled_reversal_status');
		$data['entry_completed_status'] = $this->language->get('entry_completed_status');
		$data['entry_denied_status'] = $this->language->get('entry_denied_status');
		$data['entry_expired_status'] = $this->language->get('entry_expired_status');
		$data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$data['entry_processed_status'] = $this->language->get('entry_processed_status');
		$data['entry_refunded_status'] = $this->language->get('entry_refunded_status');
		$data['entry_reversed_status'] = $this->language->get('entry_reversed_status');
		$data['entry_voided_status'] = $this->language->get('entry_voided_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_test'] = $this->language->get('help_test');
		$data['help_debug'] = $this->language->get('help_debug');
		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_order_status'] = $this->language->get('tab_order_status');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}
		
		if (isset($this->error['public'])) {
			$data['error_public'] = $this->error['public'];
		} else {
			$data['error_public'] = '';
		}
		
		if (isset($this->error['private'])) {
			$data['error_private'] = $this->error['private'];
		} else {
			$data['error_private'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/clover', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('payment/clover', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->post['clover_pub_key'])) {
			$data['clover_pub_key'] = $this->request->post['clover_pub_key'];
		} else {
			$data['clover_pub_key'] = $this->config->get('clover_pub_key');
		}
		
		if (isset($this->request->post['clover_pri_key'])) {
			$data['clover_pri_key'] = $this->request->post['clover_pri_key'];
		} else {
			$data['clover_pri_key'] = $this->config->get('clover_pri_key');
		}

		if (isset($this->request->post['clover_test'])) {
			$data['clover_test'] = $this->request->post['clover_test'];
		} else {
			$data['clover_test'] = $this->config->get('clover_test');
		}

		if (isset($this->request->post['clover_debug'])) {
			$data['clover_debug'] = $this->request->post['clover_debug'];
		} else {
			$data['clover_debug'] = $this->config->get('clover_debug');
		}

		if (isset($this->request->post['clover_total'])) {
			$data['clover_total'] = $this->request->post['clover_total'];
		} else {
			$data['clover_total'] = $this->config->get('clover_total');
		}

		if (isset($this->request->post['clover_reversal_status_id'])) {
			$data['clover_reversal_status_id'] = $this->request->post['clover_reversal_status_id'];
		} else {
			$data['clover_reversal_status_id'] = $this->config->get('clover_reversal_status_id');
		}

		if (isset($this->request->post['clover_completed_status_id'])) {
			$data['clover_completed_status_id'] = $this->request->post['clover_completed_status_id'];
		} else {
			$data['clover_completed_status_id'] = $this->config->get('clover_completed_status_id');
		}

		if (isset($this->request->post['clover_denied_status_id'])) {
			$data['clover_denied_status_id'] = $this->request->post['clover_denied_status_id'];
		} else {
			$data['clover_denied_status_id'] = $this->config->get('clover_denied_status_id');
		}

		if (isset($this->request->post['clover_expired_status_id'])) {
			$data['clover_expired_status_id'] = $this->request->post['clover_expired_status_id'];
		} else {
			$data['clover_expired_status_id'] = $this->config->get('clover_expired_status_id');
		}

		if (isset($this->request->post['clover_failed_status_id'])) {
			$data['clover_failed_status_id'] = $this->request->post['clover_failed_status_id'];
		} else {
			$data['clover_failed_status_id'] = $this->config->get('clover_failed_status_id');
		}

		if (isset($this->request->post['clover_pending_status_id'])) {
			$data['clover_pending_status_id'] = $this->request->post['clover_pending_status_id'];
		} else {
			$data['clover_pending_status_id'] = $this->config->get('clover_pending_status_id');
		}

		if (isset($this->request->post['clover_processed_status_id'])) {
			$data['clover_processed_status_id'] = $this->request->post['clover_processed_status_id'];
		} else {
			$data['clover_processed_status_id'] = $this->config->get('clover_processed_status_id');
		}

		if (isset($this->request->post['clover_refunded_status_id'])) {
			$data['clover_refunded_status_id'] = $this->request->post['clover_refunded_status_id'];
		} else {
			$data['clover_refunded_status_id'] = $this->config->get('clover_refunded_status_id');
		}

		if (isset($this->request->post['clover_reversed_status_id'])) {
			$data['clover_reversed_status_id'] = $this->request->post['clover_reversed_status_id'];
		} else {
			$data['clover_reversed_status_id'] = $this->config->get('clover_reversed_status_id');
		}

		if (isset($this->request->post['clover_voided_status_id'])) {
			$data['clover_voided_status_id'] = $this->request->post['clover_voided_status_id'];
		} else {
			$data['clover_voided_status_id'] = $this->config->get('clover_voided_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['clover_geo_zone_id'])) {
			$data['clover_geo_zone_id'] = $this->request->post['clover_geo_zone_id'];
		} else {
			$data['clover_geo_zone_id'] = $this->config->get('clover_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['clover_status'])) {
			$data['clover_status'] = $this->request->post['clover_status'];
		} else {
			$data['clover_status'] = $this->config->get('clover_status');
		}

		if (isset($this->request->post['clover_sort_order'])) {
			$data['clover_sort_order'] = $this->request->post['clover_sort_order'];
		} else {
			$data['clover_sort_order'] = $this->config->get('clover_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/clover', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/clover')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['clover_pri_key']) {
			$this->error['private'] = $this->language->get('error_private');
		}
		
		if (!$this->request->post['clover_pub_key']) {
			$this->error['public'] = $this->language->get('error_public');
		}

		return !$this->error;
	}
}