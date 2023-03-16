<?php

namespace TillPayments\TillPaymentsPlugin\Gateway\Http\Client;

use Exception;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;
use TillPayments\TillPaymentsPlugin\Services\Service;

class TransactionAuthorization implements ClientInterface
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
     * TransactionAuthorization constructor.
     * @param Service $service
     * @param Logger $logger
     */
    public function __construct(Logger $logger, Service $service)
    {
        $this->logger = $logger;
        $this->service = $service;
    }

    /**
     * @param TransferInterface $transferObject
     * @return array|void
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        $request = $transferObject->getBody();
        try {
            $response = $this->service->authorize($request);
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }
        $this->logger->debug(['request' => $request, 'response' => $response]);
        return $response;
    }
}
