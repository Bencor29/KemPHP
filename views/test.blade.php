@extends('static.app')

@section('content')
  <div class="container">
    <p>
      Ca fonctionne {{ $name }}!!!
    </p>
    <form method="post">
      <input type="text" />
      <input type="submit" />
    </form>
  </div>
@endsection
