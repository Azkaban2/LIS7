<form action="{{ route('validate-results') }}" method="POST">
    @csrf
    <input type="hidden" name="test_code" value="CC-T04">
    <div>
        <h3 class="text-lg font-semibold text-center mb-4">LIPID PROFILE</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="text-center py-2">TEST</th>
                    <th class="text-center py-2">RESULT</th>
                    <th class="text-center py-2">UNIT</th>
                    <th class="text-center py-2">REFERENCE RANGE</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="py-2 text-center">Total Cholesterol</td>
                    <td class="py-2 text-center">
                        <input type="text" name="results[Total Cholesterol]" class="w-60 border border-gray-300 rounded p-2 text-center">
                    </td>
                    <td class="py-2 text-center">mg/dL</td>
                    <td class="py-2 text-center">140 - 200</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 text-center">Triglyceride</td>
                    <td class="py-2 text-center">
                        <input type="text" name="results[Triglyceride]" class="w-60 border border-gray-300 rounded p-2 text-center">
                    </td>
                    <td class="py-2 text-center">mg/dL</td>
                    <td class="py-2 text-center">67 - 157</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 text-center">HDL</td>
                    <td class="py-2 text-center">
                        <input type="text" name="results[HDL]" class="w-60 border border-gray-300 rounded p-2 text-center">
                    </td>
                    <td class="py-2 text-center">mg/dL</td>
                    <td class="py-2 text-center">29 - 60</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 text-center">LDL</td>
                    <td class="py-2 text-center">
                        <input type="text" name="results[LDL]" class="w-60 border border-gray-300 rounded p-2 text-center">
                    </td>
                    <td class="py-2 text-center">mg/dL</td>
                    <td class="py-2 text-center">57 - 130</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 text-center">VLDL</td>
                    <td class="py-2 text-center">
                        <input type="text" name="results[VLDL]" class="w-60 border border-gray-300 rounded p-2 text-center">
                    </td>
                    <td class="py-2 text-center">mg/dL</td>
                    <td class="py-2 text-center">0 - 30</td>
                </tr>
            </tbody>
        </table>
        <div class="flex justify-end">
            <button type="submit" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded">Validate</button>
        </div>
    </div>
</form>
