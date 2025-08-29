@props(['id' => '', 'value' => '', 'onclick' => '', 'class' => '', 'containerClass' => ''])

<div class="{{ $containerClass }}">
    <button name="{{ $id }}" id="{{ $id }}" type="button" value="{{ $value }}" onclick="{{ $onclick }}" class="{{ $class }}" {{ $attributes([]) }}>{{ $slot }}</button>

    <div id="error-{{ $id }}" style="display: none;"></div>
</div>