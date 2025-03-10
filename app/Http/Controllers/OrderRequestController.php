<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderRequest;
use App\Models\ActivityLog;
use Aranyasen\HL7\Message;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OrderRequestController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        return view(
            $this->resolveView('order-requests.create'),
            [
                'user' => $user,
                'medtechFullName' => $user->name,
                'medtechLicNo' => $user->licensed_number,
            ]
        );
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_name' => 'required|string|max:255|unique:order_requests,patient_name',
            'patient_id' => 'required|string|max:255|unique:order_requests,patient_id',
            'birthday' => 'required|date',
            'age' => 'required|integer',
            'gender' => 'required|string|max:10',
            'programs' => 'required|array',
            'programs.*' => 'string',
            'order' => 'required|array',
            'order.*' => 'string',
            'sample_type' => 'required|string|max:255',
            'sample_container' => 'required|string|max:255',
            'collection_date' => 'required|date',
            'test_code' => 'nullable|string|max:255',
            'pathologist_full_name' => 'nullable|string|max:255',
            'pathologist_lic_no' => 'nullable|string|max:255',
            'physician_full_name' => 'nullable|string|max:255',
            'date_performed' => 'required|date',   // Added validation for date_performed
            'date_released' => 'required|date',    // Added validation for date_released
        ]);

        $validatedData['user_id'] = auth()->id();
        $validatedData['medtech_full_name'] = auth()->user()->name;
        $validatedData['medtech_lic_no'] = auth()->user()->licensed_number;

        $validatedData['programs'] = json_encode($validatedData['programs']);
        $validatedData['order'] = json_encode($validatedData['order']);

        $orderRequest = OrderRequest::create($validatedData);

        Log::info('OrderRequest created successfully:', ['id' => $orderRequest->id]);

        ActivityLog::create([
            'action' => 'Created a new order request for patient: ' . $orderRequest->patient_name,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route($this->resolveView('order-requests.requestlog'))->with('success', 'Order request created successfully.');
    }

    public function requestlog()
    {
        // Log the action of viewing the request log
        ActivityLog::create([
            'action' => 'Viewed the order request log',
            'user_id' => auth()->id(),
        ]);
    
        // Fetch and display the order requests
        return view(
            $this->resolveView('order-requests.requestlog'),
            ['orderRequests' => OrderRequest::latest()->paginate(10)]
        );
    }
    
    

    public function edit($id)
    {
        $orderRequest = OrderRequest::findOrFail($id);
        $orderRequest->programs = json_decode($orderRequest->programs, true) ?? [];
        $orderRequest->order = json_decode($orderRequest->order, true) ?? [];

        return view($this->resolveView('order-requests.edit'), compact('orderRequest'));
    }
    public function update(Request $request, $id)
    {
        // Validate the incoming data, including the new fields
        $validatedData = $request->validate([
            'patient_name' => 'required|string|max:255|unique:order_requests,patient_name,' . $id,
            'patient_id' => 'required|string|max:255|unique:order_requests,patient_id,' . $id,
            'birthday' => 'required|date',
            'age' => 'required|integer|min:0',
            'gender' => 'required|string|max:10',
            'programs' => 'nullable|array',
            'programs.*' => 'string|max:255',
            'order' => 'nullable|array',
            'order.*' => 'string|max:255',
            'sample_type' => 'required|string|max:255',
            'sample_container' => 'required|string|max:255',
            'collection_date' => 'required|date',
            'test_code' => 'nullable|string|max:255',
            'pathologist_full_name' => 'nullable|string|max:255',
            'pathologist_lic_no' => 'nullable|string|max:255',
            'physician_full_name' => 'nullable|string|max:255',
        ]);
    
        // Encode programs and order arrays to JSON format
        $validatedData['programs'] = json_encode($validatedData['programs'] ?? []);
        $validatedData['order'] = json_encode($validatedData['order'] ?? []);
    
        // Find and update the order request
        $orderRequest = OrderRequest::findOrFail($id);
        $orderRequest->update($validatedData);
    
        // Log the update action
        ActivityLog::create([
            'action' => 'Updated order request for patient: ' . $orderRequest->patient_name,
            'user_id' => auth()->id(),
        ]);
    
        // Redirect with a success message
        return redirect()->route($this->resolveView('order-requests.requestlog'))->with('success', 'Order request updated successfully.');
    }
    

    public function destroy($id)
    {
        $orderRequest = OrderRequest::findOrFail($id);
        $orderRequest->delete();

        ActivityLog::create([
            'action' => 'Deleted order request for patient: ' . $orderRequest->patient_name,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route($this->resolveView('order-requests.requestlog'))->with('success', 'Order deleted successfully.');
    }

    private function generatePatientId()
    {
        do {
            $today = now()->format('Ymd');
            $count = OrderRequest::whereDate('created_at', today())->count() + 1;
            $patientId = 'P-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        } while (OrderRequest::where('patient_id', $patientId)->exists());

        return $patientId;
    }

    private function resolveView($baseView)
    {
        return auth()->user()->usertype === 'admin' ? "admin.{$baseView}" : $baseView;
    }

    public function parseHL7AndSave(Request $request)
    {
        try {
            $hl7Message = $request->input('hl7_message');
            Log::info('Received HL7 message:', ['data' => $hl7Message]);
    
            // Parse the HL7 message
            $message = new Message($hl7Message);
            if (!$message) {
                throw new \Exception('HL7 Parsing Failed');
            }
    
            Log::info('HL7 Message Parsed Successfully', ['raw' => $message->toString()]);
    
            // Extract the PID segment
            $pidSegment = $message->getSegmentByName('PID');
            if (!$pidSegment) {
                throw new \Exception('Missing PID segment in the HL7 message');
            }
    
            // Correctly extract PID-3 (Patient ID)
            $pid3Field = $pidSegment->getField(3);  // This may be an array or string
    
            // Ensure we handle multiple components correctly
            if (is_array($pid3Field)) {
                $patientId = $pid3Field[0] ?? 'N/A';
            } else {
                $patientId = explode('^', $pid3Field)[0];
            }
    
            Log::info('Extracted Patient ID:', ['patient_id' => $patientId]);
    
            // Extract and log other details for verification
            $lastName = $pidSegment->getField(5, 0) ?? '';
            $firstName = $pidSegment->getField(5, 1) ?? '';
            $patientName = trim("$lastName $firstName");
            $dob = $pidSegment->getField(7);
            $formattedDob = $dob ? Carbon::createFromFormat('Ymd', $dob)->format('Y-m-d') : null;
            $gender = $pidSegment->getField(8) ?? 'Unknown';
    
            Log::info('Extracted Patient Details:', [
                'patient_id' => $patientId,
                'patient_name' => $patientName,
                'dob' => $formattedDob,
                'gender' => $gender,
            ]);
    
            // Save to the OrderRequest table
            $orderRequest = OrderRequest::create([
                'patient_name' => $patientName ?: 'Unknown',
                'patient_id' => $patientId,
                'birthday' => $formattedDob,
                'age' => $formattedDob ? now()->diffInYears(Carbon::createFromFormat('Y-m-d', $formattedDob)) : 0,
                'gender' => $gender,
                'sample_type' => 'Blood',  // Replace with actual field from message
                'collection_date' => now(),  // Replace with actual field if needed
                'test_code' => 'CBC',  // Replace with actual field if needed
                'programs' => json_encode(['Hematology']),
                'order' => json_encode(['CBC']),
                'user_id' => auth()->id() ?? 1,
            ]);
    
            Log::info('OrderRequest created successfully', ['id' => $orderRequest->id]);
    
            return response()->json(['status' => 'success', 'order' => $orderRequest], 201);
        } catch (\Exception $e) {
            Log::error('HL7 Parsing Error:', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'HL7 Parsing Failed'], 500);
        }
    }    
}
