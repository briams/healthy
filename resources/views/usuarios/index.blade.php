<h1>Usuarios</h1>
@foreach ($users as $user)
  <p>{{ $user->nombre }}</p>
  <p>{{ $user->usuario }}</p>
@endforeach
