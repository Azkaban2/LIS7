<form action="{{ route('validate-results') }}" method="POST">
    @csrf
    <input type="hidden" name="test_code" value="HEMA-T02">
    
    <div>
        <h3 class="text-lg font-semibold text-center mb-4">COMPLETE BLOOD COUNT WITH CT/BT</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="text-center py-2">PARAMETER</th>
                    <th class="text-center py-2">RESULTS</th>
                    <th class="text-center py-2">UNIT</th>
                </tr>
            </thead>
            <tbody>
                @foreach(['RBC' => '10^12/L', 'Hemoglobin' => 'g/L', 'Hematocrit' => 'L/L', 'WBC' => '10^9/L', 'Platelet Count' => '10^9/L', 'Neutrophil' => '%', 'Lymphocyte' => '%', 'Monocyte' => '%', 'Stabs' => '%', 'Eosinophil' => '%', 'Basophil' => '%', 'MCV' => 'fL', 'MCH' => 'pg', 'MCHC' => 'g/L', 'Clotting Time' => 'Minutes', 'Bleeding Time' => 'Minutes'] as $parameter => $unit)
                <tr class="border-b">
                    <td class="py-2 text-center">{{ $parameter }}</td>
                    <td class="py-2 text-center"><input type="text" name="results[{{ $parameter }}]" class="w-60 border border-gray-300 rounded p-2 text-center"></td>
                    <td class="py-2 text-center">{{ $unit }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="flex justify-end">
            <button type="submit" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded">Validate</button>
        </div>
    </div>
</form>
