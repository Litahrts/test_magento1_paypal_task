<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 09.10.18
 * Time: 17:49
 */

class Test_Task_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param $order
     */
    public function sendOrderData($order)
    {
        if (!$order->getId()) {
            Mage::log('NO ORDER', null, 'test_task.log');
            return;
        }

        $orderData = $this->transformOrderData($order);

        $url = Mage::getStoreConfig('task/test_group/test_test_url');
        try {
            /* @var Varien_Http_Adapter_Curl */
            $http = new Varien_Http_Adapter_Curl();
            $config = array('timeout' => 10);

            $http->setConfig($config);

            $http->write(Zend_Http_Client::POST, $url, '1.1', array(), json_encode($orderData));

            $response = $http->read();
            $http->close();
            Mage::log($response, null, 'test_task.log');
            Mage::log(print_r($orderData, true), null, 'test_task.log');
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, 'test_task.log');
            return;
        }

        return;
    }

    /**
     * @param $order
     * @return array
     */
    public function transformOrderData($order)
    {
        $orderAddress = $order->getShippingAddress();

        $data = array(
            'email' => $order->getCustomerEmail(),
            'first_name' => $orderAddress->getFirstname(),
            'company' => $orderAddress->getCompany(),
            'created_at' => $order->getCreatedAt(),
            'sku' => array()
        );

        foreach ($order->getAllItems() as $item) {
            $data['sku'][] = $item->getSku();
        }

        if (count($data['sku']) == 1) {
            $data['sku'] = $data['sku'][0];
        }

        return $data;
    }
}