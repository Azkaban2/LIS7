<form action="{{ route('validate-results') }}" method="POST">
    @csrf
    <input type="hidden" name="test_code" value="SERO-T04">
    <div>
        <h3 class="text-lg font-semibold text-center mb-4">SARS-CoV-2 (COVID-19) RAPID ANTIGEN TEST</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="text-center py-2">RESULT</th>
                </tr>
            </thead>
        </table>
        <div class="mt-4">
            <label for="brand" class="block text-sm font-medium text-gray-700">SARS-CoV-2 (COVID-19) RAPID ANTIGEN TEST:</label>
            <input type="text" id="brand" name="results[SARS-CoV-2 (COVID-19) RAPID ANTIGEN TEST]" class="w-full border border-gray-300 rounded p-2 text-center">
        </div>
        <div class="mt-4">
            <label for="brand" class="block text-sm font-medium text-gray-700">Brand Used and Principle:</label>
            <input type="text" id="brand" name="results[Brand Used and Principle]" class="w-full border border-gray-300 rounded p-2 text-center">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded">Validate</button>
        </div>
    </div>
</form>
