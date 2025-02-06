@extends('layouts.presensi')
@section('header')
    <!--App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButoon goBack">
                <io-icon name="chevron-back-outline"></io-icon>
            </a>
        </div>
        <div class="pageTitle">Data Izin / Sakit</div>
        <div class="right"></div>
    </div>
    <!--* App Header -->
@endsection
@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        @php
            $messagesuccess = Session::get('success');
            $messageerror = Session::get('error');
        @endphp
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ $messagesuccess }}
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger">
                {{ $messageerror }}
            </div>
        @endif
    </div>
</div>
    <div class="fab-button bottom-right">
        <a href="/presensi/buatizin" class="fab" style="margin-bottom: 70px">
            <ion-icon name="add-outline"></ion-icon>
        </a>
    </div>
@endsection