<x-root title="Logs - Manage">
    <h1 style="text-align: center; font-size: 50px;">MANAGE ALL LOGS</h1>

    <div id="add-log-container">
        <h2 style="text-align: center; font-size: 25px;">ADD A LOG</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">vending_machine_id</div>
            <div class="manage-flex-items">type</div>
            <div class="manage-flex-items">text</div>
            <div class="manage-flex-items">Add</div>
        </div>

        <div class="manage-flex-container">
            <x-input id="add-log-vending_machine_id" type="number" value="" class="manage-flex-items" min="0">vending_machine_id</x-input>
            <x-input id="add-log-type" type="text" value="" class="manage-flex-items">type</x-input>
            <x-input id="add-log-text" type="text" value="" class="manage-flex-items">text</x-input>

            <x-button id="add-log" value="" onclick="addLog()" class="manage-flex-button">Add</x-button>
        </div>
    </div>

    <div id="logs-container">
        <h2 style="text-align: center; font-size: 25px;">EDIT LOGS</h2>

        <div class="manage-flex-container">
            <div class="manage-flex-items">id</div>
            <div class="manage-flex-items">vending_machine_id</div>
            <div class="manage-flex-items">type</div>
            <div class="manage-flex-items">text</div>
            <div class="manage-flex-items">Update</div>
            <div class="manage-flex-items">Delete</div>
        </div>

        @foreach ($logs as $log)
        <div id="log-{{ $log->id }}">
            <div class="manage-flex-container">
                <div class="manage-flex-items">{{ $log->id }}</div>
                <x-input id="log-{{ $log->id }}-vending_machine_id" type="number" value="{{ $log->vending_machine_id }}" class="manage-flex-items" min="0">vending_machine_id</x-input>
                <x-input id="log-{{ $log->id }}-type" type="text" value="{{ $log->type }}" class="manage-flex-items">type</x-input>
                <x-input id="log-{{ $log->id }}-text" type="text" value="{{ $log->text }}" class="manage-flex-items">text</x-input>

                <x-button id="update-log-{{ $log->id }}" value="{{ $log->id }}" onclick="updateLog({{ $log->id }})" class="manage-flex-button">Update</x-button>

                <x-button id="delete-log-{{ $log->id }}" value="{{ $log->id }}" onclick="deleteLog({{ $log->id }})" class="manage-flex-button">Delete</x-button>
            </div>
        </div>
        @endforeach
    </div>
</x-root>
