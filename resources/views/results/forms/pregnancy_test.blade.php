<form action="{{ route('validate-results') }}" method="POST">
    @csrf
    <input type="hidden" name="test_code" value="CM-T02">
    <div>
        <h3 class="text-lg font-semibold text-center mb-4">PREGNANCY TEST</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="text-center py-2">TEST</th>
                    <th class="text-center py-2">RESULT</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="py-2 text-center">Urine</td>
                    <td class="py-2 text-center"><input type="text" name="results[Urine]" class="w-60 border border-gray-300 rounded p-2 text-center"></td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 text-center">Plasma/Serum</td>
                    <td class="py-2 text-center"><input type="text" name="results[Plasma/Serum]" class="w-60 border border-gray-300 rounded p-2 text-center"></td>
                </tr>
            </tbody>
        </table>
        <div class="flex justify-end">
            <button type="submit" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded">Validate</button>
        </div>
    </div>
</form>
