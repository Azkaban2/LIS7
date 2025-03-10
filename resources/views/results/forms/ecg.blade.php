@extends('layouts.app')

@section('content')
<form action="{{ route('validate-results') }}" method="POST">
    @csrf
    <input type="hidden" name="test_code" value="PHY-ECG">
    
    <div>
        <h3 class="text-lg font-semibold text-center mb-4">ELECTROCARDIOGRAM (ECG) RESULTS</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="text-center py-2">PARAMETER</th>
                    <th class="text-center py-2">RESULTS</th>
                </tr>
            </thead>
            <tbody>
                @foreach([
                    'MDC_ECG_LEAD_I', 
                    'MDC_ECG_LEAD_II', 
                    'MDC_ECG_LEAD_III', 
                    'ECG_HEART_RATE', 
                    'ECG_TTHOR_RESP_RATE'
                ] as $parameter)
                <tr class="border-b">
                    <td class="py-2 text-center">{{ $parameter }}</td>
                    <td class="py-2 text-center">
                        <input type="text" name="results[{{ $parameter }}]" class="w-60 border border-gray-300 rounded p-2 text-center">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="flex justify-end">
            <button type="submit" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded">Validate</button>
        </div>
    </div>
</form>
@endsection
