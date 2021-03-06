<?php
	PerchUI::set_subnav([
		['page'=>[
						'perch_shop',
						],
				'label'=>'Dashboard'],
		['page'=>[
						'perch_shop/shippings',
						'perch_shop/shippings/edit',
						'perch_shop/shippings/delete',
						'perch_shop/shippings/zones',
						'perch_shop/shippings/zones/edit',
						'perch_shop/shippings/zones/delete',
						],
				'label'=>'Shipping'],
		['page'=>[
				'perch_shop/promos',
				'perch_shop/promos/edit',
				'perch_shop/promos/delete',
				],
				'label'=>'Promotions'],
		['page'=>[
				'perch_shop/tax',
				'perch_shop/tax/edit',
				'perch_shop/tax/delete',
				'perch_shop/tax/locations',
				'perch_shop/tax/locations/edit',
				'perch_shop/tax/locations/delete',
				'perch_shop/tax/groups',
				'perch_shop/tax/groups/edit',
				'perch_shop/tax/groups/delete',
				],
				'label'=>'Tax'],
		['page'=>[
				'perch_shop/emails',
				'perch_shop/emails/edit',
				'perch_shop/emails/delete',
				],
				'label'=>'Emails'],
		['page'=>[
				'perch_shop/currencies',
				'perch_shop/currencies/edit',
				'perch_shop/currencies/delete',
				],
				'label'=>'Currencies'],
		['page'=>[
				'perch_shop/countries',
				'perch_shop/countries/edit',
				'perch_shop/countries/delete',
				],
				'label'=>'Countries'],
		['page'=>[
				'perch_shop/statuses',
				'perch_shop/statuses/edit',
				'perch_shop/statuses/delete',
				],
				'label'=>'Statuses'],


	], $CurrentUser);