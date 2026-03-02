<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Expense') }} - {{ $colocation->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('expenses.store', $colocation->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                            :value="old('title')" required autofocus placeholder="e.g. Groceries, Electricity bill" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="amount" :value="__('Amount (€)')" />
                        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full"
                            :value="old('amount')" required placeholder="0.00" />
                        <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                    </div>

                    <div>
                        <x-input-label for="date" :value="__('Date')" />
                        <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', date('Y-m-d'))" required />
                        <x-input-error class="mt-2" :messages="$errors->get('date')" />
                    </div>

                    <div>
                        <x-input-label for="category_id" :value="__('Category')" />
                        <select id="category_id" name="category_id"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required>
                            @foreach($colocation->categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        @if($colocation->categories->isEmpty())
                            <p class="text-sm text-red-600 mt-1">No categories available. Please contact colocation owner.
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('colocations.show', $colocation) }}"
                            class="text-sm text-gray-600 hover:text-gray-900 underline mr-4">
                            {{ __('Cancel') }}
                        </a>
                        <x-primary-button>
                            {{ __('Save Expense') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>