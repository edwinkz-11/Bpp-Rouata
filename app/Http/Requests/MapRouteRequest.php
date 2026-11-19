<?php

namespace App\Http\Requests;

use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MapRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Koordinat dikirim sebagai JSON teks. Diterima: [[lat,lng],...] atau GeoJSON
     * (LineString / Feature / FeatureCollection, urutan [lng,lat] otomatis dibalik).
     */
    protected function prepareForValidation(): void
    {
        $raw = $this->input('coordinates');

        if (is_string($raw)) {
            $this->merge(['coordinates' => $this->normalize(json_decode($raw, true))]);
        }
    }

    private function normalize(mixed $data): mixed
    {
        if (! is_array($data)) {
            return null;
        }

        if (($data['type'] ?? null) === 'FeatureCollection') {
            $data = $data['features'][0] ?? null;
        }
        if (is_array($data) && ($data['type'] ?? null) === 'Feature') {
            $data = $data['geometry'] ?? null;
        }
        if (is_array($data) && ($data['type'] ?? null) === 'LineString') {
            return array_map(fn ($p) => [$p[1] ?? null, $p[0] ?? null], $data['coordinates'] ?? []);
        }

        return array_is_list($data) ? $data : null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:5000'],
            'start_location_id' => ['nullable', 'exists:locations,id'],
            'end_location_id' => ['nullable', 'exists:locations,id'],
            'verification_status' => ['required', Rule::in(array_keys(Location::STATUSES))],
            'is_published' => ['nullable', 'boolean'],
            'coordinates' => ['required', 'array', 'min:2', 'max:5000'],
            'coordinates.*' => ['array', 'size:2'],
            'coordinates.*.0' => ['required', 'numeric', 'between:-90,90'],
            'coordinates.*.1' => ['required', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'coordinates.required' => 'Jalur belum digambar. Klik peta untuk menambah titik (minimal 2 titik).',
            'coordinates.min' => 'Jalur minimal terdiri dari 2 titik.',
            'coordinates.array' => 'Format koordinat tidak dikenali.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama jalur', 'description' => 'deskripsi', 'start_location_id' => 'lokasi awal',
            'end_location_id' => 'lokasi akhir', 'verification_status' => 'status verifikasi',
        ];
    }
}
