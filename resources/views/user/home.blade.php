@extends('layouts.user')

@section('content')
    @include('user.home-section')
    @include('user.shop-section')
    @include('user.wishlist-section')
    @include('user.cart-section')
    @include('user.orders-section')
    @include('user.profile-section')
@endsection