<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Sale;

class B2BAjaxBasket extends CBitrixComponent
{

    private Sale\BasketBase $basket;

    /**
     * @throws Main\ArgumentTypeException
     * @throws Main\NotImplementedException
     * @throws Main\ArgumentException
     */
    public function __construct($args)
    {

        CModule::IncludeModule('sale');
        CModule::IncludeModule('catalog');
        parent::__construct($args);
        $this->basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(),
            Bitrix\Main\Context::getCurrent()->getSite());
    }

    public function onPrepareComponentParams($arParams): array
    {
        return array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"] ?? 36000000,
        );
    }

    /**
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     * @throws Main\ArgumentException
     * @throws Main\NotImplementedException
     * @throws Main\NotSupportedException
     */
    public function updateItem($productId, $quantity)
    {
        if ($item = $this->basket->getExistsItem('catalog', $productId)) {
            $item->setField('QUANTITY', $quantity);
        } else {
            $item = $this->basket->createItem('catalog', $productId);
            $item->setFields(array(
                'QUANTITY' => $quantity,
                'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
            ));
        }
    }

    /**
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentException
     * @throws Main\NotImplementedException
     */
    public function deleteItem($productId){
        $basketItem = $this->basket->getExistsItem('catalog', $productId);
        $basketItem -> delete();
    }

    public function commit(){
        $this->basket->save();
    }

    /**
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentException
     * @throws Main\NotImplementedException
     */
    public function echoSelf()
    {
        echo json_encode(['quantity' =>array_sum($this->basket->getQuantityList()),
            'sum' =>strval($this->basket->getPrice()) . ' ₽']);
    }

    public function getBasketArray()
    {
        $basketItems = $this->basket->getBasketItems();


    }
}