<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Sale;
use DJ\B2B\GUIDController;
use DJ\B2B\Bitrix1C\Api;
use Bitrix\Sale\Order;


Main\Loader::includeModule("dj.b2b");

class B2BOrder extends CBitrixComponent
{
    private Order $order;
    private array $orderData;

    /**
     * @return array
     */
    public function getOrderData(): array
    {
        return $this->orderData;
    }
    /**
     * @throws Main\ArgumentTypeException
     * @throws Main\NotImplementedException
     * @throws Main\ArgumentException
     */
    public function __construct($args)
    {
        parent::__construct($args);
    }

    public function load($order_id){
        $api = new Api();
        $order = Order::load($order_id);
        $orderProps = $order->getPropertyCollection();
        $order_guid = $orderProps->getItemByOrderPropertyCode('order_guid')->getValue();
        $this -> order = $order;
        if ($order_guid) {
            $orderData = json_decode($api->GetOrder($order_guid)->getResponseBody(), true);
            $this -> orderData = $orderData;
            $this -> updateOrder();
        }

    }

    public function updateOrder()
    {
        $basket = $this->order -> getBasket();
        $basket -> clearCollection();

        $this->orderData['sum'] = 0;
        foreach ($this -> orderData['bucket'] as $bucket){
            $product_id = $this -> getProductIdByGuid($bucket['guid']);
            $item = $basket->createItem('catalog', $product_id);
            $item->setFields(array(
                'QUANTITY' => $bucket['quantity'],
                'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
                'PRICE' => $bucket['price'] / $bucket['quantity'],
                'CUSTOM_PRICE' => 'Y',
            ));
            $this->orderData['sum'] += $bucket['price'];
        }
        $basket->save();
        $this->order -> save();
    }

    private function getProductIdByGuid($productGuid){
        return (new GUIDController()) -> getRowByGUID($productGuid)['UF_ELEMENT_ID'];
    }
}