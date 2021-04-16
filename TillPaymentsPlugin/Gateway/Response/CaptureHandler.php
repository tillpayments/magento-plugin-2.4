<?php

namespace TillPayments\TillPaymentsPlugin\Gateway\Response;

/**
 * Class CaptureHandler
 * @package TillPayments\TillPaymentsPlugin\Gateway\Response
 */
class CaptureHandler extends TxnIdHandler
{
    /**
     * Whether transaction should be closed
     *
     * @return bool
     */
    protected function shouldCloseTransaction(): bool
    {
        return true;
    }
}
