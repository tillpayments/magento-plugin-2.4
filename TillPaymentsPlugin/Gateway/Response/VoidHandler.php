<?php

namespace TillPayments\TillPaymentsPlugin\Gateway\Response;

/**
 * Class VoidHandler
 * @package TillPayments\TillPaymentsPlugin\Gateway\Response
 */
class VoidHandler extends TxnIdHandler
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
