<?php

namespace App\Models;
use App\Models\Interfaces\PaymentInterface;
use Illuminate\Support\Facades\DB;

/**
 * 
 */
class DDPayment implements PaymentInterface
{
	
	public function __construct() {
		
	}

	public function charge($initial_amount) {
		$final_amount = $initial_amount * (1 + 7/100);

		$final_amount_format = number_format((float)$final_amount, 2, '.', '');

		return $final_amount_format;
	}
}

?>
