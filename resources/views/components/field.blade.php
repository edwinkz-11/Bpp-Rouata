@props([
    'name', 'label' => null, 'type' => 'text', 'value' => null, 'required' => false, 'help' => null,
    'options' => [], 'rows' => 4, 'placeholder' => null, 'accept' => null, 'col' => 12, 'step' => null,
])
@php
    $current = old($name, $value);
    $invalid = $errors->has($name) ? ' is-invalid' : '';
@endphp
<div class="col-md-{{ $col }}">
    @if ($type === 'checkbox')
        <input type="hidden" name="{{ $name }}" value="0">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" role="switch" id="f-{{ $name }}" name="{{ $name }}" value="1" @checked((bool) $current)>
            <label class="form-check-label fw-semibold" for="f-{{ $name }}">{{ $label }}</label>
        </div>
    @else
        @if ($label)
            <label for="f-{{ $name }}" class="form-label fw-semibold">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
        @endif

        @if ($type === 'textarea')
            <textarea id="f-{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" class="form-control{{ $invalid }}" placeholder="{{ $placeholder }}">{{ $current }}</textarea>
        @elseif ($type === 'select')
            <select id="f-{{ $name }}" name="{{ $name }}" class="form-select{{ $invalid }}">
                <option value="">— Pilih —</option>
                @foreach ($options as $key => $text)
                    <option value="{{ $key }}" @selected((string) $current === (string) $key)>{{ $text }}</option>
                @endforeach
            </select>
        @elseif ($type === 'file')
            <input type="file" id="f-{{ $name }}" name="{{ $name }}" class="form-control{{ $invalid }}" accept="{{ $accept ?? 'image/jpeg,image/png,image/webp' }}" data-preview="#pv-{{ $name }}">
            <img id="pv-{{ $name }}" class="img-preview d-none" alt="Pratinjau">
        @else
            <input type="{{ $type }}" id="f-{{ $name }}" name="{{ $name }}" value="{{ $type === 'password' ? '' : $current }}" class="form-control{{ $invalid }}" placeholder="{{ $placeholder }}" @if ($step) step="{{ $step }}" @endif @required($required)>
        @endif

        @error($name)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        @if ($help)
            <div class="form-text">{{ $help }}</div>
        @endif
    @endif
</div>
