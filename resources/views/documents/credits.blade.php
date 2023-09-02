@extends('layouts.app')

@section('title', 'Contributors')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header">{{ config('app.name') }} Credits</div>
        <div class="card-body">
        <h1>{{ config('app.name') }} Developers</h1>
            <ul>
                <li><b>Stan</b> - Project lead</li>
                <li><b>Anonymous</b> - Frontend, backend, design</li>
                <li><b>cirroskais</b> - Server manager, arbiter developer</li>
                <li><b>theodore</b> - Contributor, bug patcher</li>
            </ul>
            <h4>Special thanks</h4>
            <ul>
                <li><b>Anonymous</b> - Helped clean up code, client help</li>
                <li><b>You</b> - for using {{ config('app.name') }}!</li>
            </ul>

            <p>Without these people lending their help, {{ config('app.name') }} would not be as good as it is today. Thanks everyone.</p>
        </div>
    </div>
</div>
@endsection
