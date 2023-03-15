<?php

namespace TillPayments\TillPaymentsPlugin\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;

/**
 * Class SendCancelEmail
 *
 * This class will send an email if order will cancelled
 */
class SendCancelEmail implements ObserverInterface
{
    /**
    * @var LoggerInterface
    */
    protected $logger;

    /**
    * @var OrderSender
    */
    protected $orderSender;

    /**
    * Observer constructor.
    * @param LoggerInterface $logger
    * @param OrderSender
    */
    public function __construct(LoggerInterface $logger, OrderSender $orderSender)
    {
	$this->logger = $logger;
        $this->orderSender = $orderSender;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
	$order = $observer->getEvent()->getOrder();
	$payment = $order->getPayment()->getMethodInstance()->getCode();
	if($payment == 'tillpayments_creditcard') {
	    $this->logger->error("mail not send");
	    $this->stopNewOrderEmail($order);
	    if ($order->getState() == 'processing') {
		$this->orderSender->send($order, true);
                $this->logger->error("mail  send ");
	    }
	}
    }

    public function stopNewOrderEmail(\Magento\Sales\Model\Order $order)
    {
        $order->setCanSendNewEmailFlag(false);
        $order->setSendEmail(false);
        $this->logger->error("mail not send 1");
        try {
            $order->save();
        }
        catch (\ErrorException $ee) {

        }
        catch (\Exception $ex) {

        }
        catch (\Error $error) {

        }
    }

}

