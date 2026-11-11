@extends('cabang.layout.app')

@section('title', "Hafalan Hadist - " . ($cabang ? $cabang->nama_asrama ?? '' : ''))
@section('header_title', "Hafalan Hadist")

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold">Hafalan Hadist</h2>
    <p class="mt-4 text-gray-600">Halaman ini belum memiliki implementasi khusus. Silakan menambahkan konten sesuai kebutuhan.</p>
</div>
@endsection
