<?php

class Test_Task_Model_Observer
{

    /* @var Test_Task_Helper_Data */
    protected $_helper;

    public function __construct()
    {
        $this->_helper = Mage::helper('test_task/data');
    }

    public function sendOrderData($observer)
    {
        $event = $observer->getEvent();
        $invoice = $event->getInvoice();
        $order = $invoice->getOrder();

        $this->_helper->sendOrderData($order);

        return true;
    }
}