<x-root title="Currencies - Manage">
    <h1 style="text-align: center; font-size: 50px;">MANAGE ALL CURRENCIES</h1>

    <div id="add-currency-container">
        <h2 style="text-align: center; font-size: 25px;">ADD A CURRENCY</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">code</div>
            <div class="manage-flex-items">euro_rate</div>
            <div class="manage-flex-items">format</div>
            <div class="manage-flex-items">Add</div>
        </div>

        <div class="manage-flex-container">
            <x-input id="add-currency-code" type="text" value="" class="manage-flex-items" maxlength="3">code</x-input>
            <x-input id="add-currency-euro_rate" type="number" value="" class="manage-flex-items" min="0" step=".0001">euro_rate</x-input>
            <x-input id="add-currency-format" type="text" value="" class="manage-flex-items">format</x-input>

            <x-button id="add-currency" value="" onclick="addCurrency()" class="manage-flex-button">Add</x-button>
        </div>
    </div>

    <div id="currencies-container">
        <h2 style="text-align: center; font-size: 25px;">EDIT CURRENCIES</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">code</div>
            <div class="manage-flex-items">euro_rate</div>
            <div class="manage-flex-items">format</div>
            <div class="manage-flex-items">Update</div>
            {{-- <div class="manage-flex-items">Delete</div> --}}
        </div>

        @foreach ($currencies as $currency)
        <div id="currency-{{ $currency->code }}">
            <div class="manage-flex-container">
                <div class="manage-flex-items">{{ $currency->code }}</div>
                <x-input id="currency-{{ $currency->code }}-euro_rate" type="number" value="{{ $currency->euro_rate }}" class="manage-flex-items" min="0" step=".0001">euro_rate</x-input>
                <x-input id="currency-{{ $currency->code }}-format" type="text" value="{{ $currency->format }}" class="manage-flex-items">format</x-input>

                <x-button id="update-currency-{{ $currency->code }}" value="{{ $currency->code }}" onclick="updateCurrency('{{ $currency->code }}')" class="manage-flex-button">Update</x-button>

                {{-- <x-button id="delete-currency-{{ $currency->code }}" value="{{ $currency->code }}" onclick="deleteCurrency('{{ $currency->code }}')" class="manage-flex-button">Delete</x-button> --}}
            </div>
        </div>
        @endforeach
    </div>
</x-root>
