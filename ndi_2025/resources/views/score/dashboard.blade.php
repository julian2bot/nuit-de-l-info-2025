@extends('layouts.listing')

@section('titreContent')

    <h2>Score</h2>

@endsection

@section('sort')
    <option value="">Aucun tri</option>
    <option value="snake">Snake</option>
    <option value="easterEgg">Easter Egg</option>
    <option value="autres">Autres soon..</option>
@endsection

@section('scriptConst')
<script>
    const endPoint = "{{ route('score.load')}}";
</script>
@endsection

@section('script')
    <script src="{{ asset('js/scroll/scoreLoad.js') }}"></script>
@endsection


@section('styleCard')
    {{-- <link rel="stylesheet" href="{{ asset('style/membre/card.css') }}"> --}}
@endsection

