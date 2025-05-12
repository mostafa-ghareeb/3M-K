<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="text-gray-600 dark:text-gray-300">Products</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{$products->count()}}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="text-gray-600 dark:text-gray-300">Orders</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{$orders->count()}}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="text-gray-600 dark:text-gray-300">Users</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{$users->count()}}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="text-gray-600 dark:text-gray-300">Revenue</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">${{$revenue}}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                <a href="{{ route('admin.products.index') }}" class="block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-4 px-6 rounded-lg text-center">
                    Manage Products
                </a>
                <a href="{{ route('admin.orders.index') }}" class="block bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-lg text-center">
                    Manage Orders
                </a>
                <a href="{{ route('admin.users.index') }}" class="block bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-4 px-6 rounded-lg text-center">
                    Manage Users
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="block bg-red-600 hover:bg-red-700 text-white font-semibold py-4 px-6 rounded-lg text-center">
                    Manage Coupons
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
