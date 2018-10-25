<?php
namespace App\Models\Interfaces;

interface PaymentInterface
{
    public function charge($initial_amount);
}

?>
