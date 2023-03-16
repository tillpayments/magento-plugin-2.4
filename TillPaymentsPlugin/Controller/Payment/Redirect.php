<?php

namespace TillPayments\TillPaymentsPlugin\Controller\Payment;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect as RedirectResult;
use Magento\Framework\Controller\ResultFactory;
use TillPayments\TillPaymentsPlugin\Services\Service;

class Redirect extends Action implements CsrfAwareActionInterface
{
    const CHECKOUT_URL = 'checkout/cart';

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var Service
     */
    private $service;

    /**
     * Redirect constructor.
     * @param Context $context
     * @param Session $checkoutSession
     * @param Service $service
     */
    public function __construct(Context $context, Session $checkoutSession, Service $service)
    {
	$this->checkoutSession = $checkoutSession;
	$this->service = $service;
        parent::__construct($context);
    }

    public function execute(): RedirectResult
    {
        /**
         * @var $resultRedirect RedirectResult
         */
	$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $status = $this->getRequest()->getParam('status');

	switch ($status) {
            case 'cancel':
		    $this->messageManager->addNoticeMessage(__('Payment was canceled by the user'));
		    break;
	    case 'error':
		$merchantkey = $this->getRequest()->getParam('merchantkey');
		//$this->messageManager->addNoticeMessage(__('An error occurred while processing the payment'));
		try {
                    $response = $this->service->getOrderStatus($merchantkey);
		    if(isset($response['transactionStatus']) && $response['transactionStatus'] == 'ERROR' && isset($response['errors'][0])) {
			$errors = $response['errors'][0];
			$errormsg = isset($errors->adapterMessage) ? $errors->adapterMessage : $errors->message;
			$this->messageManager->addNoticeMessage($errormsg);
		    } elseif(isset($response['success']) && !$response['success']) {
			$this->messageManager->addNoticeMessage($response['errorMessage']);
		    } else {
                        $this->messageManager->addNoticeMessage(__('An error occurred while processing the payment'));
		    }
                } catch (Exception $e) {
                    $this->messageManager->addNoticeMessage(__('An error occurred while processing the payment'));
		}
		break;
            default:
                $this->messageManager->addNoticeMessage(__('An error occurred while processing the payment'));
        }
        //$methodName = $this->getRequest()->getParam('method');

        $this->checkoutSession->restoreQuote();

        //$this->messageManager->addNoticeMessage(__('order_error'));
	$resultRedirect->setPath(self::CHECKOUT_URL, ['_secure' => true]);

        return $resultRedirect;
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
