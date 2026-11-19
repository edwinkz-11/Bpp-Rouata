@extends('layouts.public')

@section('title', 'Peta Wilayah')

@section('content')
@include('partials.page-header', ['title' => 'Peta Wilayah', 'subtitle' => 'Lokasi Kecamatan Routa di Google Maps dan rute perjalanan menuju Routa.'])
@include('partials.gmaps')
@include('partials.rute-akses')
@endsection
