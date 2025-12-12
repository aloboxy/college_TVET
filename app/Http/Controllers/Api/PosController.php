<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Models\PaymentRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PosController extends Controller
{
    // Verify Student by ID or Admission Number
    public function verifyStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $id = $request->student_id;
        // Search by User ID or Admission Number (adm_no)
        $student = StudentRecord::where('user_id', $id)
                    ->orWhere('adm_no', $id)
                    ->with(['user', 'my_class', 'section'])
                    ->first();

        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Student not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $student->user_id,
                'name' => $student->user->name,
                'adm_no' => $student->adm_no,
                'class' => $student->my_class->name,
                'section' => $student->section->name,
                'photo' => $student->user->photo,
            ]
        ]);
    }

    // Process Payment from POS
    public function processPayment(Request $request)
    {
        // Placeholder for POS Payment Logic
        // In a real scenario, this would integrate with the PaymentRecord model
        // to record a transaction or deduct from a wallet.
        
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'amount' => 'required|numeric',
            'ref_no' => 'required|string',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
             return response()->json(['error' => $validator->errors()], 400);
        }

        // Logic to save the transaction goes here.
        // For now, we simulate success.

        return response()->json([
            'status' => 'success',
            'message' => 'Payment processed successfully',
            'transaction_id' => 'TXN-' . time(),
            'amount' => $request->amount
        ]);
    }

    public function getBalance($student_id)
    {
        // Placeholder for balance retrieval
         return response()->json([
            'status' => 'success',
            'balance' => 0.00 // Default to 0 for now
        ]);
    }
}
