<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Coupon') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Name</label>
                        <input type="text" name="name" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Coupon Number</label>
                        <input type="text" name="coupon_number" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Discount (%)</label>
                        <input type="number" name="percent_off" min="0" max="100" step="0.01" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Duration</label>
                        <select name="duration" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                            <option value="">Select duration</option>
                            <option value="once">Once</option>
                            <option value="forever">Forever</option>
                            <option value="repeating">Repeating</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.coupons.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-4">Cancel</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
