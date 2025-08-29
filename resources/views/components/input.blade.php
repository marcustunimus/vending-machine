@props(['id' => '', 'type' => '', 'value' => '', 'class' => '', 'containerClass' => ''])

<div class="{{ $containerClass }}">
    <input name="{{ $id }}" id="{{ $id }}" type="{{ $type }}" value="{{ $value }}" class="{{ $class }}" placeholder="{{ $slot }}" {{ $attributes([]) }}>

    <div id="{{ $id }}-error" style="display: none;"></div>
</div>