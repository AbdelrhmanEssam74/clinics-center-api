<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal;
use App\Models\Appointment;
use App\Models\Doctor;

class PayPalController extends Controller
{
    /**
     * Create a PayPal order using the doctor's appointment fee.
     */
    public function createTransaction(Request $request)
    {
        $appointmentId = $request->appointment_id;

        $appointment = Appointment::with('doctor')->find($appointmentId);

        if (!$appointment || !$appointment->doctor || !$appointment->doctor->appointment_fee) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Invalid appointment or doctor not found.'
            ]);
        }

        $egpAmount = number_format($appointment->doctor->appointment_fee, 2);
        $exchangeRate = 30.9;
        $usdAmount = number_format($egpAmount / $exchangeRate, 2);

        $provider = new PayPal();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success', ['appointment_id' => $appointmentId]),
                "cancel_url" => route('paypal.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $usdAmount
                    ],
                    "description" => "Payment for appointment #$appointmentId"
                ]
            ]
        ]);

        if (isset($response['links'])) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return response()->json([
                        'status' => 'success',
                        'url' => $link['href'],
                        'appointment_id' => $appointmentId,
                        'total_egp' => $egpAmount,
                        'converted_usd' => $usdAmount
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Unable to create PayPal transaction.'
        ]);
    }

    public function captureTransaction(Request $request)
    {
        if (!$request->has('token') || !$request->has('appointment_id')) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Missing required parameters.'
            ]);
        }

        $provider = new PayPal();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);

        $status = $response['status'] ?? 'unknown';

        if ($status === 'COMPLETED') {
            $appointment = Appointment::find($request->appointment_id);
            if ($appointment) {
                $appointment->payment_status = 'paid';
                $appointment->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Payment completed successfully.',
                'payment_status' => 'paid'
            ]);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Payment not completed.',
            'paypal_status' => $status
        ]);
    }

    public function cancelTransaction()
    {
        return response()->json([
            'status' => 'cancelled',
            'message' => 'Payment was cancelled by the user.'
        ]);
    }
}
