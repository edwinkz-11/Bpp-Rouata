@php
    $lead = $workers->filter(fn ($w) => str_contains(strtolower((string) $w->position), 'koordinator'));
    $rest = $workers->diff($lead);
    $staff = $rest->filter(fn ($w) => $w->position && ! str_contains(strtolower($w->position), 'anggota'));
    $members = $rest->diff($staff);
@endphp

<h2 class="section-title mt-5"><i class="bi bi-diagram-3-fill text-green me-2"></i>Struktur Organisasi BPP Routa</h2>

@if ($workers->isEmpty())
    <x-empty icon="bi-people" title="Data struktur belum diisi" text="Petugas menambahkan data penyuluh melalui dashboard admin." />
@else
<div class="org">
    @foreach ([$lead, $staff, $members] as $group)
        @if ($group->isNotEmpty())
            <div class="org-level">
                <div class="org-row">
                    @foreach ($group as $w)
                        <div class="org-item">
                            <div class="org-node">
                                @if ($w->photo_url)
                                    <img src="{{ $w->photo_url }}" alt="{{ $w->name }}" class="org-photo" loading="lazy">
                                @else
                                    <div class="org-photo worker-ph"><i class="bi bi-person-fill"></i></div>
                                @endif
                                <div class="org-name">{{ $w->name }}</div>
                                @if ($w->position)<div class="org-pos">{{ $w->position }}</div>@endif
                                @if ($w->working_area)<div class="org-area">{{ $w->working_area }}</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>
@endif
