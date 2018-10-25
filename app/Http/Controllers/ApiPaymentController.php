<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\CCPayment;

use App\Models\DDPayment;

class ApiPaymentController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

	}

	public function createPayment() {
		$db = DB::connection('mysql');

		$response_data = [];
		$response_code = 200;
		if (request()->isMethod('post')) {
			$data = [];
			if (request()->has('data') && request('data') != '') {
				$json_data = request('data');
				$data = json_decode($json_data);

				$name = '';
				if (isset($data->name) && !empty($data->name)) {
					$name = $data->name;
				} else {
					return response()->json([
						'message' => 'Missing name.',
						'code' => 400,
					], 400);
				}

				$type = ''; // cc or dd

				$iban = ''; // only for dd
				$expiry = ''; // only for cc
				$cc = ''; // only for cc
				$ccv = ''; // only for cc

				if (isset($data->type) && !empty($data->type)) {
					$type = $data->type;
				} else {
					return response()->json([
						'message' => 'Missing type.',
						'code' => 400,
					], 400);
				}

				if ($type == 'cc') {
					if (isset($data->cc) && !empty($data->cc)) {
						$cc = $data->cc;
					} else {
						return response()->json([
							'message' => 'Missing credit card (cc).',
							'code' => 400,
						], 400);
					}

					if (isset($data->expiry) && !empty($data->expiry)) {
						$expiry = $data->expiry;
					} else {
						return response()->json([
							'message' => 'Missing expiry.',
							'code' => 400,
						], 400);
					}

					if (isset($data->ccv) && !empty($data->ccv)) {
						$ccv = $data->ccv;
					} else {
						return response()->json([
							'message' => 'Missing ccv.',
							'code' => 400,
						], 400);
					}
				} elseif ($type == 'dd') {
					if (isset($data->iban) && !empty($data->iban)) {
						$iban = $data->iban;
					} else {
						return response()->json([
							'message' => 'Missing iban.',
							'code' => 400,
						], 400);
					}
				} else {
					return response()->json([
						'message' => 'Invalid value supplied for type.',
						'code' => 400,
					], 400);
				}


				$new_payment = [
					'name' => $name,
					'type' => $type,
					'iban' => $iban,
					'expiry' => $expiry,
					'cc' => $cc,
					'ccv' => $ccv,
				];
				$db->table('payments')->insert($new_payment);

				$id = $db->getPdo()->lastInsertId();
				if ($id) {
					$new_payment['id'] = $id;
					$response_data = $new_payment;
					$response_code = 200;
				} else {
					$response_data = [
						'message' => 'Could not save the new payment.',
						'error' => 400,
					];
					$response_code = 400;
				}
			} else {
				return response()->json([
					'message' => 'Missing Payment object.',
					'code' => 400,
				], 400);
			}
		} else {
			$response_data = [
				'message' => 'Wrong method.',
				'code' => 400,
			];
			$response_code = 400;
		}

		return response()->json($response_data, $response_code);
	}

	public function getCharges() {
		$db = DB::connection('mysql');

		$response_data = [];
		$response_code = 200;

		$charges = $db->table('charges')->get();

		foreach ($charges as $charge) {
			$new_charge = $charge;
			unset($new_charge['id']);
			$response_data[] = $new_charge;
		}

		return response()->json($response_data, $response_code);
	}

	public function createCharge() {
		$db = DB::connection('mysql');

		$response_data = [];
		$response_code = 200;
		if (request()->isMethod('post')) {
			$data = [];
			if (request()->has('data') && request('data') != '') {
				$json_data = request('data');
				$data = json_decode($json_data);

				$payment_id = '';
				if (isset($data->payment_id) && !empty($data->payment_id)) {
					$payment_id = $data->payment_id;
				} else {
					return response()->json([
						'message' => 'Missing payment ID (payment_id).',
						'code' => 400,
					], 400);
				}

				$initial_amount = 0;
				if (isset($data->amount) && !empty($data->amount)) {
					$initial_amount = $data->amount;
				} else {
					return response()->json([
						'message' => 'Missing amount.',
						'code' => 400,
					], 400);
				}

				$payment_data = $this->getPaymentById($payment_id);
				$amount = 0; // new amount
				if (isset($payment_data->type) && !empty($payment_data->type)) {
					switch ($payment_data->type) {
						case 'cc':
							$ccPaymentObj = new CCPayment();
							$amount = $ccPaymentObj->charge($initial_amount);
							break;
						case 'dd':
							$ddPaymentObj = new DDPayment();
							$amount = $ddPaymentObj->charge($initial_amount);
							break;
						default:
							return response()->json([
								'message' => 'Invalid payment type.',
								'code' => 400,
							], 400);
							break;
					}
				} else {
					return response()->json([
						'message' => 'Missing payment type.',
						'code' => 400,
					], 400);
				}

				$new_charge = [
					'payment_id' => $payment_id,
					'amount' => $amount,
				];
				$db->table('charges')->insert($new_charge);

				$id = $db->getPdo()->lastInsertId();
				if ($id) {
					$new_charge['id'] = $id;
					$response_data = $new_charge;
					$response_code = 200;
				} else {
					$response_data = [
						'message' => 'Could not save the new charge.',
						'error' => 400,
					];
					$response_code = 400;
				}
			} else {
				return response()->json([
					'message' => 'Missing Charge object.',
					'code' => 400,
				], 400);
			}
		} else {
			$response_data = [
				'message' => 'Wrong method.',
				'code' => 400,
			];
			$response_code = 400;
		}

		return response()->json($response_data, $response_code);
	}

	public function getChargeById($id) {
		$db = DB::connection('mysql');

		$response_data = [];
		$response_code = 200;

		$charges = $db->table('charges')->where('id', $id)->get();

		if ($charges->count()) {
			foreach ($charges as $charge) { // once
				$new_charge = $charge;
				unset($new_charge->id);
				$response_data = $new_charge;
			}
		} else {
			$response_data = [
				'message' => 'Charge doesn\'t exist.',
				'code' => 400,
			];
			$response_code = 400;
		}

		return response()->json($response_data, $response_code);
	}

	private function getPaymentById($id) { //auxiliary function
		$db = DB::connection('mysql');

		$response_data = null;
		$response_code = 200;

		$payments = $db->table('payments')->where('id', $id)->get();

		foreach ($payments as $payment) { // once
			$response_data = $payment;
		}

		return $response_data;
	}
}

?>
