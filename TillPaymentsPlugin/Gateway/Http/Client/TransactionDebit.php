<?php

namespace TillPayments\TillPaymentsPlugin\Gateway\Http\Client;

use Exception;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;
use TillPayments\TillPaymentsPlugin\Services\Service;

class TransactionDebit implements ClientInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Service
     */
    private $service;

    /**
     * TransactionDebit constructor.
     * @param Logger $logger
     * @param Service $service
     */
    public function __construct(Logger $logger, Service $service)
    {
        $this->service = $service;
        $this->logger = $logger;
    }

    /**
     * @param TransferInterface $transferObject
     * @return array|void
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        $request = $transferObject->getBody();
        try {
            $response = $this->service->debit($request);
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }
        $this->logger->debug(['request' => $request, 'response' => $response]);

        return $response;
    }
}
