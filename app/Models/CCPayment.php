<?php

namespace App\Models;
use App\Models\Interfaces\PaymentInterface;
use Illuminate\Support\Facades\DB;

/**
 * 
 */
class CCPayment implements PaymentInterface
{
	
	public function __construct() {
		
	}

	public function charge($initial_amount) {
		$final_amount = $initial_amount * (1 + 1/10);

		return $final_amount;
	}
}

?>