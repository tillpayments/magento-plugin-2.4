<?php

namespace TillPayments\TillPaymentsPlugin\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;
use Psr\Log\LoggerInterface;

class DataAssignObserver extends AbstractDataAssignObserver
{
    const TRANSACTION_TOKEN = 'transactionToken';

    /**
     * @var array
     */
    protected $additionalInformationList = [
        self::TRANSACTION_TOKEN,
    ];

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * DataAssignObserver constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $data = $this->readDataArgument($observer);
        $paymentInfo = $this->readPaymentModelArgument($observer);

        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        $this->logger->debug(print_r($additionalData, true));
        if (!is_array($additionalData)) {
            return;
        }

        foreach ($this->additionalInformationList as $additionalInformationKey) {
            if (isset($additionalData[$additionalInformationKey])) {
                $paymentInfo->setAdditionalInformation(
                    $additionalInformationKey,
                    $additionalData[$additionalInformationKey]
                );
            }
        }
    }
}
