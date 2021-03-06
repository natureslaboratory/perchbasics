<?php

use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;

class PerchShopGateway_default
{
	protected $api;
	protected $slug = 'default';
	public $omnipay_name = null;

	public $payment_method    = 'purchase';
	public $completion_method = 'completePurchase';

	public function __construct($api, $slug='default')
	{
		$this->api = $api;
		$this->slug = $slug;
		if (is_null($this->omnipay_name)) {
			$this->omnipay_name = ucfirst($slug);
		}
	}

	public function get_default_parameters()
	{
		$Omnipay = Omnipay::create($this->omnipay_name);
		return $Omnipay->getDefaultParameters();
	}

	public function take_payment($Order, $opts)
	{
		$payment_opts = [
				'amount'        => $Order->orderTotal(),
				'currency'      => $Order->get_currency_code(),
				'transactionId' => $Order->id(),
				'clientIp'		=> PerchUtil::get_client_ip(),
				'description'	=> 'Order #'.$Order->id(),
		    ];


		// optionally get the payment card (usually just customer details, not card numbers)
		$card = $this->get_payment_card($Order);
		if ($card) {
			$payment_opts['card'] = $card;
		}

		$opts = array_merge($opts, $payment_opts);

		$opts = $this->format_payment_options($Order, $opts);
	
		$Omnipay = $this->get_omnipay_instance();

		// Send purchase request
		$payment_method = $this->payment_method;
		$response = $Omnipay->$payment_method($opts)->send();

		// Process response
		if ($response->isSuccessful()) {

			$Order->set_transaction_reference($response->getTransactionReference());

		    // Payment was successful
		    PerchUtil::debug('Payment successful');
		    return $this->handle_successful_payment($Order, $response, $opts);

		} elseif ($response->isRedirect()) {

			$Order->set_transaction_reference($response->getTransactionReference());
			$this->store_data_before_redirect($Order, $response, $opts);

		    // Redirect to offsite payment gateway
		    PerchUtil::debug('Payment redirect response');
		    $response->redirect();

		} else {

		    // Payment failed
		    PerchUtil::debug('Payment failed', 'error');
		    PerchUtil::debug($response, 'error');
		    return $this->handle_failed_payment($Order, $response, $opts);
		}
	}

	public function complete_payment($Order, $opts)
	{
		$payment_opts = [
		        'amount'   => $Order->orderTotal(),
		        'currency' => $Order->get_currency_code(),
		    ];

		$opts = array_merge($opts, $payment_opts);

		$opts = $this->format_payment_options($Order, $opts);

		$config = PerchShop_Config::get('gateways', $this->slug);

		$Omnipay = Omnipay::create($this->omnipay_name);
		$this->set_credentials($Omnipay, $config);

		$payment_method = $this->completion_method;
		$response = $Omnipay->$payment_method($opts)->send();

		// Process response
		if ($response->isSuccessful()) {

		    // Payment was successful
		    PerchUtil::debug('Payment successful');
		    $Order->update(['orderGatewayRef'=>$response->getTransactionReference()]);
		    return $this->handle_successful_payment($Order, $response, $opts);

		} elseif ($response->isRedirect()) {

			$Order->set_transaction_reference($response->getTransactionReference());
			$this->store_data_before_redirect($Order, $response, $opts);

		    // Redirect to offsite payment gateway
		    PerchUtil::debug('Payment redirect response');
		    $response->redirect();

		} else {

		    // Payment failed
		    PerchUtil::debug('Payment failed', 'error');
		    PerchUtil::debug($response, 'error');
		    return $this->handle_failed_payment($Order, $response, $opts);
		}

		return false;
	}

	public function get_api_key($config)
	{
		if ($config['test_mode']) {
			return $config['test']['api_key'];
		}
		return $config['live']['api_key'];
	}

	public function get_public_api_key($config)
	{
		return false;
	}

	public function format_payment_options(PerchShop_Order $Order, array $opts)
	{
		return $opts;
	}

	public function produce_payment_response(array $args, array $gateway_opts)
	{
		return;
	}

	public function get_order_from_env($Orders, $get, $post)
	{
		return false;
	}

	public function callback_looks_valid($get, $post)
	{
		return false;
	}

	public function get_callback_args($get, $post)
	{
		return $get;
	}

	public function action_payment_callback($Order, $args, $gateway_opts)
	{
		return true;
	}

	public function finalize_as_paid($Order)
	{
		return true;
	}

	public function handle_successful_payment($Order, $response, $gateway_opts)
	{
		$Order->finalize_as_paid();
		return $response;
	}

	public function handle_failed_payment($Order, $response, $gateway_opts)
	{
		$Order->set_status('payment_failed');
		echo $response->getMessage();
		return false;
	}

	public function set_credentials(&$Omnipay, $config)
	{
		$api_key = $this->get_api_key($config);
		if ($api_key) {
			$Omnipay->setApiKey($api_key);
		}
	}

	public function store_data_before_redirect($Order, $response, $opts)
	{

	}

	public function get_card_address($Order)
	{
		return false;
	}

	public function get_omnipay_instance()
	{
		$config = PerchShop_Config::get('gateways', $this->slug);
		$Omnipay = Omnipay::create($this->omnipay_name);
		$this->set_credentials($Omnipay, $config);
		return $Omnipay;
	}

	public function get_transaction_data($Order)
	{
		$Omnipay = $this->get_omnipay_instance();

		$transaction = $Omnipay->fetchTransaction();
		$transaction->setTransactionReference($Order->orderGatewayRef());
		$response 	= $transaction->send();
		return $response->getData();
	}

	public function get_payment_card($Order)
	{
		$Customers = new PerchShop_Customers($this->api);
        $Customer = $Customers->find($Order->customerID());

        $Addresses = new PerchShop_Addresses($this->api);

        $ShippingAddr = $Addresses->find((int)$Order->orderShippingAddress());
        $BillingAddr  = $Addresses->find((int)$Order->orderBillingAddress());

		$data = [
			'firstName'        => $Customer->customerFirstName(),
			'lastName'         => $Customer->customerLastName(),
			'billingAddress1'  => $BillingAddr->get('address_1'),
			'billingAddress2'  => $BillingAddr->get('address_2'),
			'billingCity'      => $BillingAddr->get('city'),
			'billingPostcode'  => $BillingAddr->get('postcode'),
			'billingState'     => $BillingAddr->get('county'),
			'billingCountry'   => $BillingAddr->get_country_iso2(),
			'shippingAddress1' => $ShippingAddr->get('address_1'),
			'shippingAddress2' => $ShippingAddr->get('address_2'),
			'shippingCity'     => $ShippingAddr->get('city'),
			'shippingPostcode' => $ShippingAddr->get('postcode'),
			'shippingState'    => $ShippingAddr->get('county'),
			'shippingCountry'  => $ShippingAddr->get_country_iso2(),
			'company'		   => $BillingAddr->get('addressCompany'),
			'email'            => $Customer->customerEmail(),
		];

		$card = new CreditCard($data);

		return $card;
	}

	public function get_exchange_rate($Order)
	{
		return null;
	}
}