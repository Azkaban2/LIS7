<form action="{{ route('validate-results') }}" method="POST">
    @csrf
    <input type="hidden" name="test_code" value="HEMA-T03">
    <div>
        <h3 class="text-lg font-semibold text-center mb-4">BLOOD TYPING</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="text-center py-2">TEST</th>
                    <th class="text-center py-2">RESULT</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="py-2 text-center">ABO RH</td>
                    <td class="py-2 text-center"><input type="text" name="results[ABO RH]" class="w-60 border border-gray-300 rounded p-2 text-center">
                </tr>
            </tbody>
        </table>
        <div class="flex justify-end">
            <button type="submit" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded">Validate</button>
        </div>
    </div>
</form>
