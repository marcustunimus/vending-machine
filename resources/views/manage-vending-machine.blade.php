<x-root title="Vending Machine - Manage">
    <h1 style="text-align: center; font-size: 50px;">MANAGE VENDING MACHINE</h1>

    <div id="vending-machine-container">
        <h2 style="text-align: center; font-size: 25px;">EDIT VENDING MACHINE</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">id</div>
            <div class="manage-flex-items">currency_code</div>
            <div class="manage-flex-items">key</div>
            <div class="manage-flex-items">location</div>
            <div class="manage-flex-items">balance_amount</div>
            <div class="manage-flex-items">Update</div>
            <div class="manage-flex-items">Delete</div>
        </div>

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
    </div>



    <div id="coinStacks-container">
        <h2 style="text-align: center; font-size: 25px;">EDIT COIN STACKS</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">id</div>
            <div class="manage-flex-items">vending_machine_id</div>
            <div class="manage-flex-items">type</div>
            <div class="manage-flex-items">remaining_amount</div>
            <div class="manage-flex-items">Update</div>
            <div class="manage-flex-items">Delete</div>
        </div>

        @foreach ($coinStacks as $coinStack)
        <div id="coinStack-{{ $coinStack->id }}">
            <div class="manage-flex-container">
                <div class="manage-flex-items">{{ $coinStack->id }}</div>
                <x-input id="coinStack-{{ $coinStack->id }}-vending_machine_id" type="number" value="{{ $coinStack->vending_machine_id }}" class="manage-flex-items" min="0">vending_machine_id</x-input>
                <x-input id="coinStack-{{ $coinStack->id }}-type" type="text" value="{{ $coinStack->type }}" class="manage-flex-items">type</x-input>
                <x-input id="coinStack-{{ $coinStack->id }}-remaining_amount" type="number" value="{{ $coinStack->remaining_amount }}" class="manage-flex-items" min="0">remaining_amount</x-input>

                <x-button id="update-coinStack-{{ $coinStack->id }}" value="{{ $coinStack->id }}" onclick="updateCoinStack({{ $coinStack->id }})" class="manage-flex-button">Update</x-button>

                <x-button id="delete-coinStack-{{ $coinStack->id }}" value="{{ $coinStack->id }}" onclick="deleteCoinStack({{ $coinStack->id }})" class="manage-flex-button">Delete</x-button>
            </div>
        </div>
        @endforeach
    </div>



    <div id="items-container">
        <h2 style="text-align: center; font-size: 25px;">EDIT ITEMS</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">id</div>
            <div class="manage-flex-items">vending_machine_id</div>
            <div class="manage-flex-items">name</div>
            <div class="manage-flex-items">price</div>
            <div class="manage-flex-items">remaining_amount</div>
            <div class="manage-flex-items">Update</div>
            <div class="manage-flex-items">Delete</div>
        </div>

        @foreach ($items as $item)
        <div id="item-{{ $item->id }}">
            <div class="manage-flex-container">
                <div class="manage-flex-items">{{ $item->id }}</div>
                <x-input id="item-{{ $item->id }}-vending_machine_id" type="number" value="{{ $item->vending_machine_id }}" class="manage-flex-items" min="0">vending_machine_id</x-input>
                <x-input id="item-{{ $item->id }}-name" type="text" value="{{ $item->name }}" class="manage-flex-items">name</x-input>
                <x-input id="item-{{ $item->id }}-price" type="number" value="{{ $item->price }}" class="manage-flex-items" min="0" step=".0001">price</x-input>
                <x-input id="item-{{ $item->id }}-remaining_quantity" type="number" value="{{ $item->remaining_quantity }}" class="manage-flex-items" min="0">remaining_quantity</x-input>

                <x-button id="update-item-{{ $item->id }}" value="{{ $item->id }}" onclick="updateItem({{ $item->id }})" class="manage-flex-button">Update</x-button>

                <x-button id="delete-item-{{ $item->id }}" value="{{ $item->id }}" onclick="deleteItem({{ $item->id }})" class="manage-flex-button">Delete</x-button>
            </div>
        </div>
        @endforeach
    </div>
</x-root>
