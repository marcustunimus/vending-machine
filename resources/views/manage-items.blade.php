<x-root title="Items - Manage">
    <h1 style="text-align: center; font-size: 50px;">MANAGE ALL ITEMS</h1>

    <div id="add-item-container">
        <h2 style="text-align: center; font-size: 25px;">ADD AN ITEM</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">vending_machine_id</div>
            <div class="manage-flex-items">name</div>
            <div class="manage-flex-items">price</div>
            <div class="manage-flex-items">remaining_quantity</div>
            <div class="manage-flex-items">Add</div>
        </div>

        <div class="manage-flex-container">
            <x-input id="add-item-vending_machine_id" type="number" value="" class="manage-flex-items" min="0">vending_machine_id</x-input>
            <x-input id="add-item-name" type="text" value="" class="manage-flex-items">name</x-input>
            <x-input id="add-item-price" type="number" value="" class="manage-flex-items" min="0" step=".0001">price</x-input>
            <x-input id="add-item-remaining_quantity" type="number" value="" class="manage-flex-items" min="0">remaining_quantity</x-input>

            <x-button id="add-item" value="" onclick="addItem()" class="manage-flex-button">Add</x-button>
        </div>
    </div>

    <div id="items-container">
        <h2 style="text-align: center; font-size: 25px;">EDIT ITEMS</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">id</div>
            <div class="manage-flex-items">vending_machine_id</div>
            <div class="manage-flex-items">name</div>
            <div class="manage-flex-items">price</div>
            <div class="manage-flex-items">remaining_quantity</div>
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
