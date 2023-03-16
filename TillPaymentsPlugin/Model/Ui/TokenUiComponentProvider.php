<?php

namespace TillPayments\TillPaymentsPlugin\Model\Ui;

use Magento\Framework\UrlInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Model\Ui\TokenUiComponentInterface;
use Magento\Vault\Model\Ui\TokenUiComponentInterfaceFactory;
use Magento\Vault\Model\Ui\TokenUiComponentProviderInterface;
use TillPayments\TillPaymentsPlugin\Model\Ui\ConfigProvider as CommonConfigProvider;
use Psr\Log\LoggerInterface;

class TokenUiComponentProvider implements TokenUiComponentProviderInterface
{
    /**
     * @var TokenUiComponentInterfaceFactory
     */
    private $componentFactory;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param TokenUiComponentInterfaceFactory $componentFactory
     * @param UrlInterface $urlBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        TokenUiComponentInterfaceFactory $componentFactory,
        UrlInterface $urlBuilder,
        LoggerInterface $logger
    ) {
        $this->componentFactory = $componentFactory;
        $this->urlBuilder = $urlBuilder;
        $this->logger = $logger;
    }

    /**
     * Get UI component for token
     *
     * @param PaymentTokenInterface $paymentToken
     * @return TokenUiComponentInterface
     */
    public function getComponentForToken(PaymentTokenInterface $paymentToken): TokenUiComponentInterface
    {
        $jsonDetails = json_decode($paymentToken->getTokenDetails() ?: '{}', true);
        $this->logger->debug(print_r($jsonDetails, true));
        return $this->componentFactory->create(
            [
                'config' => [
                    'code' => ConfigProvider::CC_VAULT_CODE,
                    TokenUiComponentProviderInterface::COMPONENT_DETAILS => $jsonDetails,
                    TokenUiComponentProviderInterface::COMPONENT_PUBLIC_HASH => $paymentToken->getPublicHash()
                ],
                'name' => 'TillPayments_TillPaymentsPlugin/js/view/payment/method-renderer/cc-vault'
            ]
        );
    }
}
