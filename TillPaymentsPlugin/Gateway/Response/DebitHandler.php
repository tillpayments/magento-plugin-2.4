<?php

namespace TillPayments\TillPaymentsPlugin\Gateway\Response;


class DebitHandler extends TxnIdHandler
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
