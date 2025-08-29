<x-root title="Coin Stacks - Manage">
    <h1 style="text-align: center; font-size: 50px;">MANAGE ALL COIN STACKS</h1>

    <div id="add-coinStack-container">
        <h2 style="text-align: center; font-size: 25px;">ADD A COIN STACK</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">vending_machine_id</div>
            <div class="manage-flex-items">type</div>
            <div class="manage-flex-items">remaining_amount</div>
            <div class="manage-flex-items">Add</div>
        </div>

        <div class="manage-flex-container">
            <x-input id="add-coinStack-vending_machine_id" type="number" value="" class="manage-flex-items" min="0">vending_machine_id</x-input>
            <x-input id="add-coinStack-type" type="text" value="" class="manage-flex-items">type</x-input>
            <x-input id="add-coinStack-remaining_amount" type="number" value="" class="manage-flex-items" min="0">remaining_amount</x-input>

            <x-button id="add-coinStack" value="" onclick="addCoinStack()" class="manage-flex-button">Add</x-button>
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
</x-root>
