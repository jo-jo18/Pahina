@extends('layouts.admin')

@section('content')
    @include('admin.best-sellers')
    @include('admin.dashboard-section')
    @include('admin.inventory')
    @include('admin.orders')
    @include('admin.reports')
    @include('admin.users')
@endsection