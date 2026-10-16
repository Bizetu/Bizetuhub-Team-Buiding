@extends('twill::layouts.main')

@section('appTypeClass', 'body--custom-page')

@push('extra_css')
    <style>
        a17-dialog {
            display: none;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('extra_js_head')

@endpush

@section('content')
    <div class="custom-page">
        <div class="container">
            @yield('customPageContent')
        </div>
    </div>
    <a17-modal class="modal--browser" ref="browser" mode="medium" :force-close="true">
        <a17-browser></a17-browser>
    </a17-modal>
@stop

@section('initialStore')
    window['{{ config('twill.js_namespace') }}'].STORE.medias.crops = {!! json_encode(config('twill.settings.crops') ?? []) !!}
    window['{{ config('twill.js_namespace') }}'].STORE.medias.selected = {}

    window['{{ config('twill.js_namespace') }}'].STORE.browser = {}
    window['{{ config('twill.js_namespace') }}'].STORE.browser.selected = {}
@stop

@push('extra_js')

@endpush
