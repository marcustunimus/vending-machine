<x-root title="Vending Machine - {{ $vendingMachine->location }}">
    <div style="display: flex; justify-content: center;">
        <div style="max-width: 550px;">
            <h1 style="text-align: center; font-size: 50px;">VENDING MACHINE - {{ $vendingMachine->location }}</h1>

            <h2 style="text-align: center; font-size: 25px;">INFORMATION</h2>

            <div id="vendingMachine-key" style="display: none;">{{ $vendingMachine->key }}</div>

            <div class="view-flex-container">
                <div>Currency: {{ $vendingMachine->currency->code }}</div>
                <div>Location: {{ $vendingMachine->location }}</div>
            </div>

            <h2 style="text-align: center; font-size: 25px;">DISPLAY</h2>

            <div id="display-{{ $vendingMachine->id }}">
                <div id="display-message" style="text-align: center; font-size: 20px; background-color: lightblue;">Vending Machine Loaded.</div>
            </div>

            <h2 style="text-align: center; font-size: 25px;">BALANCE AMOUNT</h2>

            <div>
                <div id="balance-amount" style="text-align: center; font-size: 20px; font-weight: bold;">
                    {{ App\Services\VendingMachine\Helpers::formatPriceForCurrency($vendingMachine->balance_amount, $vendingMachine->currency) }}
                </div>
            </div>

            <x-button id="return-balance-button" value="{{ $vendingMachine->id }}" onclick="returnBalance({{ $vendingMachine->id }})" class="return-change-button" containerClass="return-change-button-container">RETURN BALANCE</x-button>

            <div style="display: grid; columns: 2; grid-template-columns: 60% 40%;">
                <div style="display: flex; flex-direction: column;">
                    <h2 style="text-align: center; font-size: 25px;">ITEMS</h2>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));">
                        @foreach ($items as $item)
                        <div>
                            <x-button name="{{ $item->id }}" id="purchaseItem-{{ $item->id }}" type="button" value="{{ $item->id }}" onclick="purchaseItem({{ $vendingMachine->id }}, {{ $item->id }})" class="purchase-item-button">
                                <div id="purchaseItem-{{ $item->id }}-name">{{ $item->name }}</div>
                                <div id="purchaseItem-{{ App\Services\VendingMachine\Helpers::formatPriceForCurrency($item->price, $vendingMachine->currency, true) }}-price">
                                    {{ App\Services\VendingMachine\Helpers::formatPriceForCurrency($item->price, $vendingMachine->currency, true) }}
                                </div>
                            </x-button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div style="display: flex; flex-direction: column;">
                    <h2 style="text-align: center; font-size: 25px;">INSERT COINS</h2>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); justify-items: center;">
                        @foreach ($coinStacks as $coinStack)
                        <x-button name="{{ $coinStack->id }}" id="insertCoin-{{ $coinStack->id }}" type="button" value="{{ $coinStack->type }}" onclick="insertCoin({{ $vendingMachine->id }}, '{{ $coinStack->type }}', '{{ $vendingMachine->currency->code }}')" class="insert-coin-button">{{ App\Services\VendingMachine\Helpers::formatPriceForCurrency($coinStack->type, $vendingMachine->currency) }}</x-button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-root>
