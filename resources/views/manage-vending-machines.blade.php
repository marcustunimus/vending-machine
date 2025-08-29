<x-root title="Vending Machines - Manage">
    <h1 style="text-align: center; font-size: 50px;">MANAGE ALL VENDING MACHINES</h1>

    <div id="add-vendingMachine-container">
        <h2 style="text-align: center; font-size: 25px;">ADD A VENDING MACHINE</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">currency_code</div>
            <div class="manage-flex-items">key</div>
            <div class="manage-flex-items">location</div>
            <div class="manage-flex-items">balance_amount</div>
            <div class="manage-flex-items">Add</div>
        </div>

        <div class="manage-flex-container">
            <x-input id="add-vendingMachine-currency_code" type="text" value="" class="manage-flex-items" maxlength="3">currency_code</x-input>
            <x-input id="add-vendingMachine-key" type="text" value="" class="manage-flex-items">key</x-input>
            <x-input id="add-vendingMachine-location" type="text" value="" class="manage-flex-items">location</x-input>
            <x-input id="add-vendingMachine-balance_amount" type="number" value="" class="manage-flex-items" min="0" step=".01">balance_amount</x-input>

            <x-button id="add-vendingMachine" value="" onclick="addVendingMachine()" class="manage-flex-button">Add</x-button>
        </div>
    </div>

    <div id="vendingMachines-container">
        <h2 style="text-align: center; font-size: 25px;">EDIT VENDING MACHINES</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">id</div>
            <div class="manage-flex-items">currency_code</div>
            <div class="manage-flex-items">key</div>
            <div class="manage-flex-items">location</div>
            <div class="manage-flex-items">balance_amount</div>
            <div class="manage-flex-items">Update</div>
            <div class="manage-flex-items">Delete</div>
        </div>

        @foreach ($vendingMachines as $vendingMachine)
        <div id="vendingMachine-{{ $vendingMachine->id }}">
            <div class="manage-flex-container">
                <div class="manage-flex-items">{{ $vendingMachine->id }}</div>
                <x-input id="vendingMachine-{{ $vendingMachine->id }}-currency_code" type="text" value="{{ $vendingMachine->currency_code }}" class="manage-flex-items" maxlength="3">currency_code</x-input>
                <x-input id="vendingMachine-{{ $vendingMachine->id }}-key" type="text" value="{{ $vendingMachine->key }}" class="manage-flex-items">key</x-input>
                <x-input id="vendingMachine-{{ $vendingMachine->id }}-location" type="text" value="{{ $vendingMachine->location }}" class="manage-flex-items">location</x-input>
                <x-input id="vendingMachine-{{ $vendingMachine->id }}-balance_amount" type="number" value="{{ $vendingMachine->balance_amount }}" class="manage-flex-items" min="0" step=".01">balance_amount</x-input>

                <x-button id="update-vendingMachine-{{ $vendingMachine->id }}" value="{{ $vendingMachine->id }}" onclick="updateVendingMachine({{ $vendingMachine->id }})" class="manage-flex-button">Update</x-button>

                <x-button id="delete-vendingMachine-{{ $vendingMachine->id }}" value="{{ $vendingMachine->id }}" onclick="deleteVendingMachine({{ $vendingMachine->id }})" class="manage-flex-button">Delete</x-button>
            </div>
        </div>
        @endforeach
    </div>
</x-root>
