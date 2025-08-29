<x-root title="Vending Machines">
    <h1 style="text-align: center; font-size: 50px;">SELECT A VENDING MACHINE BASED ON LOCATION</h1>

    <div style="display:flex;">
        @foreach ($vendingMachines as $vendingMachine)
        <div>
            <x-button id="{{ $vendingMachine->id }}" value="{{ $vendingMachine->id }}" onclick="redirectToVendingMachinePage({{ $vendingMachine->id }})" class="select-vending-machine-button">{{ $vendingMachine->location }}</x-button>
        </div>
        @endforeach
    </div>
</x-root>
