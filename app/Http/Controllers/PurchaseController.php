<?php

namespace App\Http\Controllers;

use App\Enum\CardEnum;
use App\Enum\EmployeeEnum;
use App\Enum\MachineEnum;
use App\Enum\SlotEnum;
use App\Enum\TransactionEnum;
use App\Models\Card;
use App\Models\Employee;
use App\Models\Machine;
use App\Models\Slot;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'card_number' => 'required|integer',
            'machine_id' => 'required|integer',
            'slot_id' => 'required|integer',
        ]);

        $employee = Employee::where('card_number', $validated['card_number'])->first();
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], Response::HTTP_NOT_FOUND);
        }

        if ($employee->status !== EmployeeEnum::STATUS_ACTIVE->value) {
            return response()->json(['message' => 'Employee is not active'], Response::HTTP_BAD_REQUEST);
        }

        $machine = Machine::find($validated['machine_id']);
        if (!$machine) {
            return response()->json(['message' => 'Machine not found'], Response::HTTP_NOT_FOUND);
        }

        if ($machine->status !== MachineEnum::STATUS_ACTIVE->value) {
            return response()->json(['message' => 'Machine is not active'], Response::HTTP_BAD_REQUEST);
        }

        $slot = Slot::find($validated['slot_id']);
        if (!$slot) {
            return response()->json(['message' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$slot->status) {
            return response()->json(['message' => 'Item is not available'], Response::HTTP_BAD_REQUEST);
        }

        $card = Card::where('card_number', $validated['card_number'])->first();
        if (!$card) {
            return response()->json(['message' => 'Card not found'], Response::HTTP_NOT_FOUND);
        }

        if ($card->status !== CardEnum::STATUS_ACTIVE->value) {
            return response()->json(['message' => 'Card is not active'], Response::HTTP_BAD_REQUEST);
        }

        if ($card->point < $slot->point) {
            return response()->json(['message' => 'Insufficient points'], Response::HTTP_BAD_REQUEST);
        }

        // TODO: check classification

        try {
            $response = DB::transaction(function () use ($card, $employee, $machine, $slot) {
                $transaction = Transaction::create([
                    'transaction_code' => uniqid('', true),
                    'employee_id' => $employee->id,
                    'slot_id' => $slot->id,
                    'point' => $slot->point,
                    'status' => TransactionEnum::STATUS_PENDING->value,
                    'machine_location' => $machine->location,
                ]);

                try {
                    $card->decrement('point', $slot->point);
                    $slot->update(['status' => SlotEnum::STATUS_FALSE->value]);
                    $transaction->update(['status' => TransactionEnum::STATUS_COMPLETED->value]);

                    return ['transaction_code' => $transaction->transaction_code, 'message' => 'Purchase successful'];
                } catch (\Exception $e) {
                    $transaction->update(['status' => TransactionEnum::STATUS_FAILED->value]);
                    throw $e;
                }
            });

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Transaction failed: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
