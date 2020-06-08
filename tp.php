<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");?>
<?
//1
use Bitrix\Sale;
Bitrix\Main\Loader::includeModule("webnauts.ordermerger");
\Bitrix\Main\Loader::IncludeModule("sale");


echo Date(CDatabase::DateFormatToPHP(CLang::GetDateFormat("FULL", LANG)));


$a = new \ReflectionClass('Bitrix\Sale\Payment');
//echo '<pre>'.print_r(mydump($a->getFileName()), true).'</pre>';
//echo '<pre>'.print_r(mydump(get_class_methods('Bitrix\Main\Type\DateTime')), true).'</pre>';


$order = Sale\Order::load(24);
$orderMain = Sale\Order::load(3);


/*
	$paymentCollection = $orderMain->getPaymentCollection();
    $extPayment = $paymentCollection->createItem(
  Bitrix\Sale\PaySystem\Manager::getObjectById(1)
);
    $extPayment->setFields(array(
        'PAY_SYSTEM_ID' => 1,
		//'PAY_SYSTEM_NAME' => 'Наличные',
        'SUM' => $orderMain->getPrice()
    ));
*/
//$orderMain->save();

$paymentCollection = $order->getPaymentCollection();

//$payment = $order->getPaymentSystemId();
//echo '<pre>'.print_r(mydump($payment), true).'</pre>';

foreach ($paymentCollection as $payment)
{


$paymentfields = $payment->getFieldValues();

if ($paymentfields['DATE_PAID'] instanceof Bitrix\Main\Type\DateTime) {
	$paymentfields['DATE_PAID'] = $paymentfields['DATE_PAID']->toString();
}
if ($paymentfields['PAY_VOUCHER_DATE'] instanceof Bitrix\Main\Type\Date) {
	$paymentfields['PAY_VOUCHER_DATE'] = $paymentfields['PAY_VOUCHER_DATE']->toString();
}
if ($paymentfields['DATE_PAY_BEFORE'] instanceof Bitrix\Main\Type\DateTime) {
	$paymentfields['DATE_PAY_BEFORE'] = $paymentfields['DATE_PAY_BEFORE']->toString();
}
if ($paymentfields['DATE_BILL']instanceof Bitrix\Main\Type\DateTime) {
	$paymentfields['DATE_BILL'] = $paymentfields['DATE_BILL']->toString();
}
unset(
 $paymentfields['ID'],
 $paymentfields['ORDER_ID'],
 $paymentfields['ACCOUNT_NUMBER']
);
echo '<pre>'.print_r(mydump($paymentfields), true).'</pre>';

$paymentMainCollection = $orderMain->getPaymentCollection();
    $extPayment = $paymentCollection->createItem();
    $extPayment->setFields(
$paymentfields
		/*
		array(
        'PAY_SYSTEM_ID' => $paymentfields['PAY_SYSTEM_ID'],
        'PAY_SYSTEM_NAME' => $paymentfields['PAY_SYSTEM_NAME'],
        'SUM' => $paymentfields['SUM']
    	)
		*/
);

$orderMain->save();

}




/*
$statusResult = \Bitrix\Sale\Internals\StatusLangTable::getList(array(

    'order' => array('STATUS.SORT'=>'ASC'),

    'filter' => array('STATUS.TYPE'=>'O','LID'=>LANGUAGE_ID),

    'select' => array('STATUS_ID','NAME'),

));

$status=$statusResult->fetchAll();

$arStatus = array_column($status, 'NAME', 'STATUS_ID');
echo '<pre>'.print_r(mydump($arStatus), true).'</pre>';
*/
/*
while($status=$statusResult->fetch())

{

    echo '<pre>'.print_r(mydump($status), true).'</pre>';

}
*/

/*
$res =  AdminMenuShow::GetUserOrderList(1);
echo '<pre>'.print_r(mydump($res), true).'</pre>';



$orderId = "1";
$order = Sale\Order::load($orderId);
$userId = $order->getUserId();
$parameters = [
	//'select' => array("ID", "ACCOUNT_NUMBER","DATE_INSERT", "STATUS_ID", "USER_ID", "PRICE", "CURRENCY"),
    'filter' => [
        array(
            "USER_ID" => $userId,
            "!=ID" => $orderId,
			"=STATUS_ID" => array("F", "P")
        ),
    ],
    'order' => ["DATE_INSERT" => "ASC"]
];

$dbRes = Sale\Order::getList($parameters);

while ($arOrder = $dbRes->fetch())
{	//echo CurrencyFormat($arOrder['PRICE'], $arOrder['CURRENCY']);
 echo '<pre>'.print_r(mydump($arOrder), true).'</pre>';
	//echo '<pre>'.print_r(mydump($order->getField('ACCOUNT_NUMBER')), true).'</pre>';
	//echo '<pre>'.print_r(mydump($order->getPrice()), true).'</pre>';

}


//$res = Cb2cplDelivery::GetProfiles();
//$res = Cb2cplDelivery::GetPVZ();

//echo '<pre>'.print_r(mydump($res), true).'</pre>';


$order = CSaleOrder::GetByID(6);
//$order = CSaleOrderPropsValue::GetOrderProps(6);
                        if ($order) {
                            $status = isset($order['STATUS_ID']) ? $order['STATUS_ID'] : null;
                            $totalAmount = isset($order['PRICE']) ? $order['PRICE'] : null;
                            if (isset($order['PRICE_DELIVERY'])) {
                                $totalAmount -= $order['PRICE_DELIVERY'];
                            }

                            $params = array(
                                'order_id' => $order['ID'],
                                'status' => ($status === 'F' || $status === 'P') ? 'confirmed' : 'rejected',
                                'amount_total' => $totalAmount
                            );

							//  self::QueryApi('gift/updateOrderStatus', $params);
                        } 
//echo '<pre>'.print_r($order, true).'</pre>';
//echo '<pre>'.print_r($params, true).'</pre>';

$order = Bitrix\Sale\Order::load(7);
GiftdDiscountManager::ChargeCouponOnBeforeOrderAddD7Events($order);

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?> 
