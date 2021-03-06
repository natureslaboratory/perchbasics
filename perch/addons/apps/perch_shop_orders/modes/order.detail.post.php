<?php

    $opts = $Statuses->get_select_options();

    $statuses = $Statuses->get_list(); 
    $value = 'processing';

    for($i=0; $i<count($statuses); $i++) {
        if ($statuses[$i] == $Order->orderStatus() && isset($statuses[$i+1])) {
            $value = $statuses[$i+1];
        } 
    }

    echo $HTML->title_panel([
        'heading' => $Lang->get('Viewing order'),
        'form' => [
            'action' => $Form->action(),
            'button' => $Form->select_field('status', 'Change status to', $opts, $value).$Form->submit('btnSubmit', 'Update', 'button button-small')
        ]
    ], $CurrentUser);

    include('_order_smartbar.php');

echo $HTML->heading2('Order');

    echo '<div class="inner">';
    echo '<table class="d factsheet">';

    echo '<tr>';
        echo '<th>'.$Lang->get('Invoice').'</th>';
        echo '<td>'.$HTML->encode($Order->orderInvoiceNumber()).'</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<th>'.$Lang->get('Order ID').'</th>';
        echo '<td>'.$HTML->encode($Order->id()).'</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<th>'.$Lang->get('Received').'</th>';
        echo '<td>'.$HTML->encode(PerchShop_Date::format($Order->orderCreated(), PERCH_DATE_SHORT.' '.PERCH_TIME_SHORT)).'</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<th>'.$Lang->get('Status').'</th>';
        echo '<td>'.$HTML->encode(ucfirst($Order->orderStatus())).'</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<th>'.$Lang->get('Discounts').'</th>';
        echo '<td>'.$HTML->encode($Currency->format_display($Order->orderDiscountsTotal())).'</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<th>'.$Lang->get('Total').'</th>';
        echo '<td>'.$HTML->encode($Currency->format_display($Order->orderTotal())).'</td>';
    echo '</tr>';

    if ($Order->orderTaxID()) {
        echo '<tr>';
            echo '<th>'.$Lang->get('Tax ID').'</th>';
            echo '<td>'.$HTML->encode($Order->orderTaxID()).'</td>';
        echo '</tr>';
    }   

    echo '<tr>';
        echo '<th>'.$Lang->get('Gateway').'</th>';
        echo '<td>'.$HTML->encode($Order->orderGateway()).'</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<th>'.$Lang->get('Shipping method').'</th>';
        $Shipping = $Order->get_shipping();
        echo '<td>';

        if ($Shipping) {
            echo '<a href="'.$API->app_path('perch_shop').'/shippings/edit/?id='.$HTML->encode($Shipping->id()).'">'.$HTML->encode($Shipping->title()).'</a>';
        }else{
            echo $HTML->encode($Lang->get('No shipping'));
        }
        echo '</td>';
    echo '</tr>';

    $promotions = $Order->get_promotions();
    if ($promotions) {
        echo '<tr>';
            echo '<th>'.$Lang->get('Promotions').'</th>';
            echo '<td>';
            $out = [];
            foreach($promotions as $Promotion) {
                $out[] = $Promotion->title();
            }
            echo $HTML->encode(implode(', ', $out));
            echo '</td>';
        echo '</tr>';
    }

    echo '</table>';

    echo '</div>';

echo $HTML->heading2('Customer');

    echo '<div class="inner"> <table class="d factsheet">';

    echo '<tr>';
        echo '<th>'.$Lang->get('Customer ID').'</th>';
        echo '<td><a href="'.$API->app_path('perch_shop_orders').'/customers/edit/?id='.$HTML->encode($Order->customerID()).'">'.$HTML->encode($Order->customerID()).'</a></td>';
    echo '</tr>';


    echo '<tr>';
        echo '<th>'.$Lang->get('First name').'</th>';
        echo '<td>'.$HTML->encode($Customer->customerFirstName()).'</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<th>'.$Lang->get('Last name').'</th>';
        echo '<td>'.$HTML->encode($Customer->customerLastName()).'</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<th>'.$Lang->get('Email').'</th>';
        echo '<td>'.$HTML->encode($Customer->customerEmail()).'</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<th>'.$Lang->get('Billing address').'</th>';
        echo '<td>';
            echo $HTML->encode($BillingAdr->addressFirstName().' '.$BillingAdr->addressLastName()).'<br>';
            echo_if($BillingAdr->addressCompany(), $HTML);
            echo_if($BillingAdr->get('address_1'), $HTML);
            echo_if($BillingAdr->get('address_2'), $HTML);
            echo_if($BillingAdr->get('city'), $HTML);
            echo_if($BillingAdr->get('county'), $HTML);
            echo_if($BillingAdr->get('postcode'), $HTML);
            echo_if($BillingAdr->get_country_name(), $HTML);
        echo '</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<th>'.$Lang->get('Shipping address').'</th>';
        echo '<td>';
            echo $HTML->encode($ShippingAdr->addressFirstName().' '.$ShippingAdr->addressLastName()).'<br>';
            echo_if($ShippingAdr->addressCompany(), $HTML);
            echo_if($ShippingAdr->get('address_1'), $HTML);
            echo_if($ShippingAdr->get('address_2'), $HTML);
            echo_if($ShippingAdr->get('city'), $HTML);
            echo_if($ShippingAdr->get('county'), $HTML);
            echo_if($ShippingAdr->get('postcode'), $HTML);
            echo_if($ShippingAdr->get_country_name(), $HTML);
        echo '</td>';
    echo '</tr>';

    echo '</table>';




    if (PerchUtil::count($items)) {

        //echo $HTML->heading2('Order items');

        echo '<table class="">';

        echo '<thead>';
        echo '<tr>';
                echo '<th>'.$Lang->get('SKU').'</th>';
                echo '<th>'.$Lang->get('Item').'</th>';
                echo '<th>'.$Lang->get('Desc').'</th>';
                echo '<th>'.$Lang->get('Qty').'</th>';
                echo '<th>'.$Lang->get('Price').'</th>';
                echo '<th>'.$Lang->get('Tax').'</th>';
                echo '<th>'.$Lang->get('Total').'</th>';
        echo '</tr>';
        echo '</thead>';

        foreach($items as $Item) {
            #PerchUtil::debug($Item);
            echo '<tr>';
                echo '<td>'.$Item->sku().'</td>';
                echo '<td>'.$Item->title().'</td>';
                echo '<td>'.($Item->is_variant() ? $Item->productVariantDesc() : '').'</td>';
                echo '<td>'.$Item->itemQty().'</td>';
                echo '<td>'.$Item->itemPrice().'</td>';
                echo '<td>'.$Item->itemTax().'</td>';
                echo '<td>'.$Currency->format_display($Item->itemTotal()*$Item->itemQty()).'</td>';
            echo '</tr>';
        }

        echo '</table>';

    }

    echo '</div>';

    $properties = PerchUtil::json_safe_decode($Order->orderDynamicFields(), true);
    if (PerchUtil::count($properties)) {
        echo $HTML->heading2('Additional information');

        echo '<div class="inner"><table class="d factsheet">';

        foreach($properties as $key => $val) {
            echo '<tr>';
                echo '<th>'.$HTML->encode($key).'</th>';
                echo '<td>'.$HTML->encode($val).'</td>';
            echo '</tr>';    
        }

        echo '</table>';

    }
    

    echo '</div>';


    function echo_if($val, $HTML)
    {
        if (isset($val) && $val) {
            echo $HTML->encode($val).'<br>';
        }
    }
?>



<style type="text/css">
.holdem {
    min-width: 40px;
}

.topadd {
    float: right;
}

form.topadd .field {
    display: inline-block;
}

form.topadd .field label {
    display: inline;
    width: auto;
}

.topadd p.submit {
    display: inline-block;
    padding: 0;
    margin: 0;
    border: 0;
}

.topadd p.submit .button {
    padding: 2px 10px;
}
</style>