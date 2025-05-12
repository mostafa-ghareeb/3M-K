<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Coupons') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4">
                    <a href="{{ route('admin.coupons.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        + Add Coupon
                    </a>
                </div>

                @if($coupons->isEmpty())
                    <p class="text-gray-600 dark:text-gray-300">No coupons found.</p>
                @else
                    <table class="min-w-full table-auto text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-xs uppercase">
                            <tr>
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Code</th>
                                <th class="px-4 py-2">Discount</th>
                                <th class="px-4 py-2">Duration</th>
                                <th class="px-4 py-2">Used</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            @foreach($coupons as $coupon)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $coupon->id }}</td>
                                    <td class="px-4 py-2">{{ $coupon->name }}</td>
                                    <td class="px-4 py-2">{{ $coupon->coupon_id }}</td>
                                    <td class="px-4 py-2">{{ $coupon->discount }}%</td>
                                    <td class="px-4 py-2 capitalize">{{ $coupon->duration }}</td>
                                    <td class="px-4 py-2">
                                        @if($coupon->is_used)
                                            <span class="text-red-600">Used</span>
                                        @else
                                            <span class="text-green-600">Available</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 space-x-2">
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
