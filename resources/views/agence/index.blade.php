@extends('layouts.app')

@section('title', 'Index')

@section('content')
    <v-container>
        <agence-index :consultor_list="{{ json_encode($consultorList) }}" :available_month_list="{{ json_encode($availableMonthList) }}"/>
    </v-container>
@endsection