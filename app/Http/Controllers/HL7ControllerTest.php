<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aranyasen\HL7\Message;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class HL7ControllerTest extends Controller
{
    public function parseHL7(Request $request)
    {
        try {
            // Receive HL7 message
            $hl7Message = $request->input('hl7_message');
            Log::info('Received HL7 message:', ['data' => $hl7Message]);

            // Parse HL7 message
            $message = new Message($hl7Message);
            if (!$message) {
                throw new \Exception('HL7 Parsing Failed');
            }
            Log::info('HL7 Message Parsed Successfully', ['raw' => $message->toString()]);

            // Extract PID segment
            $pidSegments = $message->getSegmentsByName('PID');
            $pidSegment = $pidSegments[0] ?? null;

            if (!$pidSegment) {
                throw new \Exception('PID Segment Missing');
            }

            // Extract fields
            $patientId = $pidSegment->getField(2) ?? 'N/A';
            $patientNameField = $pidSegment->getField(5) ?? 'Unknown';

            Log::info('Raw PID-5 (Patient Name Field):', ['raw' => $patientNameField]);

            // Handle patient name parsing
            $patientName = 'Unknown';
            if (!empty($patientNameField)) {
                if (is_array($patientNameField)) {
                    $patientNameField = implode('^', $patientNameField);
                }
                $nameParts = explode('^', $patientNameField);
                $lastName = $nameParts[0] ?? '';
                $firstName = $nameParts[1] ?? '';
                $patientName = trim("$firstName $lastName");
            }

            // Extract DOB and gender
            $dob = $pidSegment->getField(7);
            $formattedDob = $dob ? Carbon::createFromFormat('Ymd', $dob)->format('Y-m-d') : 'N/A';
            $gender = trim($pidSegment->getField(8) ?? 'Unknown');

            // Log extracted data
            Log::info('Extracted HL7 Patient Data', [
                'patient_id' => $patientId,
                'patient_name' => $patientName,
                'dob' => $dob,
                'gender' => $gender,
            ]);

            // Send message to Mirth
            $mirthResponse = $this->sendToMirth($hl7Message);

            // Return response
            return response()->json([
                'status' => 'success',
                'patient_id' => $patientId,
                'patient_name' => $patientName,
                'dob' => $dob,
                'gender' => $gender,
                'mirth_status' => $mirthResponse['status'],
                'mirth_message' => $mirthResponse['message'],
                'raw_hl7_message' => $hl7Message
            ]);

        } catch (\Exception $e) {
            Log::error('HL7 Parsing Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'HL7 Parsing Failed'
            ], 500);
        }
    }

    private function sendToMirth($hl7Message)
    {
        $host = '127.0.0.1';  // Mirth server address
        $port = 6661;         // Mirth TCP Listener port

        try {
            // Wrap HL7 message with MLLP frame
            $mlpMessage = "\x0B" . $hl7Message . "\x1C\x0D";

            // Connect to Mirth server
            $socket = fsockopen($host, $port, $errno, $errstr, 10);
            if (!$socket) {
                throw new \Exception("Could not connect to Mirth: $errstr ($errno)");
            }

            // Send HL7 message to Mirth
            fwrite($socket, $mlpMessage);

            // Read response from Mirth (if applicable)
            $response = '';
            if (!feof($socket)) {
                $response = fread($socket, 8192);
            }

            // Close the socket
            fclose($socket);

            Log::info('HL7 message sent to Mirth successfully.', ['response' => $response]);

            return [
                'status' => 'success',
                'message' => 'HL7 message sent to Mirth successfully. Response: ' . $response
            ];
        } catch (\Exception $e) {
            Log::error('Failed to send HL7 message to Mirth: ' . $e->getMessage());

            return [
                'status' => 'error',
                'message' => 'Failed to send HL7 message to Mirth: ' . $e->getMessage()
            ];
        }
    }
}
