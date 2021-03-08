<?php

class ControllerCheckoutSuccess extends Controller {

	const API_ENDPOINT = 'https://connect.gls-croatia.com/webservices/soap_server.php?wsdl&ver=16.12.15.01';


	public function index() {
		$this->load->language('checkout/success');

		if (isset($this->session->data['order_id'])) {




            // gls napravi naljepnicu
            //$this->load->model('sale/order/setting');
         /*   $json = array();
            
            $params = array(
                'api_user' => 'BalidooAPI',
                'api_key'  => '380006417'
            );
            
            // load data from order_id
            $order_id = $this->session->data['order_id'];
            
            $pcount     = 1;
            $brojracuna = $order_id;
            $iznos      = isset($this->request->get['iznos']) ? $this->request->get['iznos'] : 0;
            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($order_id);
            
            $_HTTP = ! empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
            // change this by country (gls-hungary.com, gls-slovakia.sk, gls-czech.com, gls-romania.ro, gls-slovenia.com, gls-croatia.com)
            //$wsdl_path = $_HTTP.'gls-slovenia.com'.'/webservices/webservices/soap_server.php?wsdl&ver=16.12.15.01';
            
            $wsdl_path = 'https://connect.gls-croatia.com/webservices/soap_server.php?wsdl&ver=16.12.15.01';
            $client    = new SoapClient($wsdl_path);
            
            if ($order_info['payment_code'] == 'cod') {
                if ($iznos == '') {
                    $mani = $order_info['total'];
                    $mani = number_format((float)$mani, 2, '.', '');
                } else {
                    $mani = $iznos;
                }
            } else {
                $mani = 0;
            }
            
            $in = array(
                'username'       => 'BalidooAPI',
                'password'       => '380006417',
                'senderid'       => '380006417',
                'sender_name'    => 'Bali d.o.o.',
                'sender_address' => 'Trg Republike 3',
                'sender_city'    => 'Donja Dubrava',
                'sender_zipcode' => '40328',
                'sender_country' => 'HR',
                'sender_contact' => '',
                'sender_phone'   => '',
                'sender_email'   => 'info@balidoo.de',
                'consig_name'    => $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'] . '-' . $brojracuna,
                'consig_address' => $order_info['shipping_address_1'],
                'consig_city'    => $order_info['shipping_city'],
                'consig_zipcode' => $order_info['shipping_postcode'],
                'consig_country' => $order_info['shipping_country'],
                'consig_contact' => '',
                'consig_phone'   => $order_info['telephone'],
                'consig_email'   => $order_info['email'],
                'pcount'         => $pcount,
                'pickupdate'     => date('Y-m-d'),
                'content'        => '',
                'clientref'      => '',
                'codamount'      => $mani,
                'codref'         => $brojracuna,
                'services' => array(
                    array('code' => 'FDS', 'info' => $order_info['email'])
                ),
                'printertemplate' => 'A6',
                'printit'         => false,
                'timestamp'       => date('YmdHis', strtotime('+1 hours')),
                'hash'            => 'xsd:string',
                'customlabel'     => false
            );
            
            $in['hash'] = $this->getHash($in);
            
            $return = $client->__soapCall('printlabel', $in);
            
            if ($return) {
                if ($return->successfull) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "order` SET printed = 1 WHERE order_id = '" . (int)$order_id . "'");
                    $this->response->setOutput(json_encode('OK'));
                } else {
                    var_dump($return);
                    $this->response->setOutput(json_encode('ERROR'));
                }
            }
            
            */
            //gls kraj





			$this->cart->clear();

			

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);

		
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}