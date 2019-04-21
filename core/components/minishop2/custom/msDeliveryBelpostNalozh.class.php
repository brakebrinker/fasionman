<?php
if(!class_exists('msDeliveryInterface')) {
    require_once dirname(dirname(__FILE__)) . '/model/minishop2/msdeliveryhandler.class.php';
}

class msDeliveryBelpostNalozh extends msDeliveryHandler implements msDeliveryInterface{

    public function getCost(msOrderInterface $order, msDelivery $delivery, $cost = 0) {

        $freedeliverysumm = 1000;
        $declaredvaluecost = 0.036;
        $weightcost = 0.066;
        $standartdeliveryprice = 3.9;
        $cart = $order->ms2->cart->status();
        $cart_cost = $cart['total_cost'];
        if($cart['total_weight'] == 0) {
          $cart_weight = 1000;
        }else{
          $cart_weight = $cart['total_weight'];
        }

        if($cart_cost >= $freedeliverysumm){
            return $cost;
        }else{
            $cost = round($cart_cost, 2, PHP_ROUND_HALF_EVEN) * $declaredvaluecost + ceil($cart_weight/100)*$weightcost + $standartdeliveryprice + $cart_cost;
            $delivery_cost = parent::getCost($order, $delivery, $cost);
            return $delivery_cost;
        }
    }
} 