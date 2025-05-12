<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Order #{{ $order->id }} Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Customer: {{ $order->name }}</h3>
            <p class="mb-4">Total: ${{ $order->total }}</p>

            <table class="min-w-full table-auto text-sm text-left text-gray-500 dark:text-gray-300">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Image</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->order_detail as $detail)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $detail->product->Name ?? 'Deleted Product' }}</td>
                            <td class="px-4 py-2">
                                <img src="{{ $detail->product->PictureUrl ?? '' }}" alt="Product Image" class="w-16 h-16 object-cover rounded">
                            </td>
                            <td class="px-4 py-2">{{ $detail->quantity }}</td>
                            <td class="px-4 py-2">${{ $detail->price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
