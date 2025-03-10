<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HL7ControllerTest;

// Define the POST route for handling HL7 messages
Route::post('/hl7-message', [HL7ControllerTest::class, 'parseHL7']);
