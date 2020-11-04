<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2020. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */

namespace App\Http\ValidationRules\Credit;

use App\Libraries\MultiDB;
use App\Models\Credit;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use App\Utils\Traits\MakesHash;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class CreditsSumRule.
 */
class CreditsSumRule implements Rule
{
    use MakesHash;


    private $input;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function passes($attribute, $value)
    {
        return $this->checkCreditTotals();
    }

    private function checkCreditTotals()
    {

        if( array_sum(array_column($this->input['credits'],'amount')) > array_sum(array_column($this->input['invoices'], 'amount')))
            return false;

        return true;

    }

    /**
     * @return string
     */
    public function message()
    {
        return "Total credits applied cannot be MORE than total of invoices";
    }
}