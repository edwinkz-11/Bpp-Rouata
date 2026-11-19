@props(['icon' => 'bi-inbox', 'title' => 'Data belum tersedia', 'text' => null])
<div class="empty-state">
    <i class="bi {{ $icon }}"></i>
    <h3>{{ $title }}</h3>
    @if ($text)<p>{{ $text }}</p>@endif
    {{ $slot }}
</div>
