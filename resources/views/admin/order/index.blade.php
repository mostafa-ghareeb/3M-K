<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if($orders->isEmpty())
                    <p class="text-gray-600 dark:text-gray-300">No orders found.</p>
                @else
                    <table class="min-w-full table-auto text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">User</th>
                                <th class="px-4 py-2">Total</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Payment</th>
                                <th class="px-4 py-2">Created At</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            @foreach($orders as $order)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $order->id }}</td>
                                    <td class="px-4 py-2">{{ $order->name }}</td>
                                    <td class="px-4 py-2">${{ $order->total }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 rounded 
                                            @if($order->status === 'pending') bg-yellow-200 text-yellow-800
                                            @elseif($order->status === 'cancel') bg-red-200 text-red-800
                                            @else bg-green-200 text-green-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">{{ $order->payment_method ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('order.details', $order->id) }}" class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700">
                                                Details
                                            </a>
                                    
                                            <form action="{{ route('order.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
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
