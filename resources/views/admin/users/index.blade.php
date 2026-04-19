@extends('admin.template')

@section('title', 'Usuarios')

@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
				Crear nuevo
			</a>
		</div>
		<div class="card-body">
			<form>
				<div class="row">
					<div class="col-md-3">
						<div class="mb-3">
							<label>Nombre</label>
							<input class="form-control" name="name" value="{{ request()->name }}">
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
			</form>
		</div>
		<div class="table-responsive">
			<table class="table">
				<thead class="thead-light">
					<tr>
						<th>Nombre</th>
						<th>Apellidos</th>
						<th>Usuario</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
					<tr>
						<td>{{ $user->name }}</td>
						<td>{{ $user->last_name }}</td>
						<td>{{ $user->user }}</td>
						<td>
							<a class="btn btn-primary btn-sm" href="{{ route('users.edit', $user) }}">
								Editar
							</a>
							<form class="d-inline-block" method="POST" action="{{ route('users.destroy', $user) }}">
								@csrf
								@method('DELETE')
								<button type="submit" class="btn btn-danger btn-sm">
									Eliminar
								</button>
							</form>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="card-footer">
			{{ $users->links() }}
		</div>
	</div>
</div>
@endsection