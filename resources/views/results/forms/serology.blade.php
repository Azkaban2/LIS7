<form action="{{ route('validate-results') }}" method="POST">
    @csrf
    <input type="hidden" name="test_code" value="SERO-T02">

    <div>
        <h3 class="text-lg font-semibold text-center mb-4">SEROLOGY</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b bg-blue-100">
                    <th class="text-center py-2">TEST</th>
                    <th class="text-center py-2">RESULT</th>
                    <th class="text-center py-2">UNIT</th>
                </tr>
            </thead>
            <tbody>
                <!-- Main Serology Tests -->
                @foreach(['Troponin I' => 'ng/mL', 'Hepatitis B Surface Antigen' => '', 'Hepatitis B Surface Antibody' => '', 'Anti-HCV' => '', 'Anti-HAV' => '', 'HIV 1/2 Antibody (Screening)' => '', 'Syphilis (VDRL)' => ''] as $parameter => $unit)
                <tr class="border-b">
                    <td class="py-2 text-center">{{ $parameter }}</td>
                    <td class="py-2 text-center">
                        <input type="text" name="results[{{ $parameter }}]" class="w-60 border border-gray-300 rounded p-2 text-center">
                    </td>
                    <td class="py-2 text-center">{{ $unit }}</td>
                </tr>
                @endforeach

                <!-- Thyroid Panel Section -->
                <tr class="bg-gray-200">
                    <td class="py-2 text-left font-bold" colspan="3">THYROID PANEL</td>
                </tr>
                @foreach(['TSH' => 'mIU/L', 'FT3' => 'pmol/L', 'FT4' => 'pmol/L', 'T3' => 'nmol/L', 'T4' => 'nmol/L'] as $parameter => $unit)
                <tr class="border-b">
                    <td class="py-2 text-center">{{ $parameter }}</td>
                    <td class="py-2 text-center">
                        <input type="text" name="results[{{ $parameter }}]" class="w-60 border border-gray-300 rounded p-2 text-center">
                    </td>
                    <td class="py-2 text-center">{{ $unit }}</td>
                </tr>
                @endforeach

                <!-- Tumor Marker Section -->
                <tr class="bg-gray-200">
                    <td class="py-2 text-left font-bold" colspan="3">TUMOR MARKER</td>
                </tr>
                @foreach(['CEA' => 'ng/mL', 'CA19-9' => 'U/mL', 'PSA' => 'ng/mL'] as $parameter => $unit)
                <tr class="border-b">
                    <td class="py-2 text-center">{{ $parameter }}</td>
                    <td class="py-2 text-center">
                        <input type="text" name="results[{{ $parameter }}]" class="w-60 border border-gray-300 rounded p-2 text-center">
                    </td>
                    <td class="py-2 text-center">{{ $unit }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end">
            <button type="submit" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Validate</button>
        </div>
    </div>
</form>
