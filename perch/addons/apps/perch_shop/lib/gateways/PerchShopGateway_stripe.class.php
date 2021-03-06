<?php

class PerchShopGateway_stripe extends PerchShopGateway_default
{
	public function handle_successful_payment($Order, $response, $gateway_opts)
	{
		$Order->finalize_as_paid();
		if (isset($gateway_opts['return_url'])) {
			PerchUtil::redirect($gateway_opts['return_url']);
		}
	}

	public function handle_failed_payment($Order, $response, $gateway_opts)
	{
		$Order->set_status('payment_failed');

		if (isset($gateway_opts['cancel_url'])) {
			PerchUtil::redirect($gateway_opts['cancel_url']);
		}
	}

	public function get_api_key($config)
	{
		if ($config['test_mode']) {
			return $config['test']['secret_key'];
		}
		return $config['live']['secret_key'];
	}

	public function get_public_api_key($config)
	{
		if ($config['test_mode']) {
			return $config['test']['publishable_key'];
		}
		return $config['live']['publishable_key'];
	}

	public function get_card_address($Order)
	{
		$data = $this->get_transaction_data($Order);

		if (isset($data['source']) && isset($data['source']['country'])) {
			return [
				'country' => $data['source']['country']
			];
		}

		return false;
	}

	public function get_exchange_rate($Order)
	{
		$this->init_native_stripe_api();

		$Charge = \Stripe\Charge::retrieve($Order->orderGatewayRef());

		if ($Charge) {
			$BalanceTransaction = \Stripe\BalanceTransaction::retrieve($Charge->balance_transaction);

			$rate = ((float)$Charge->amount / (float)$BalanceTransaction->amount);
			return $rate;
		}

		return null;
	}

	private function init_native_stripe_api()
	{
		$config = PerchShop_Config::get('gateways', $this->slug);
		$api_key = $this->get_api_key($config);

		\Stripe\Stripe::setApiKey($api_key);
	}
}