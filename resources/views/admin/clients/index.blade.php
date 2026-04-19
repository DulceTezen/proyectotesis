@extends('admin.template')

@section('title', 'Clientes')

@section('content')
<div class="col-md-12">
	<div class="card">
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
						<th>Documento</th>
						<th>Correo electrónico</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($clients as $client)
					<tr>
						<td>{{ $client->name }}</td>
						<td>{{ $client->last_name }}</td>
						<td>{{ $client->document }}</td>
						<td>{{ $client->email }}</td>
						<td>
							<a class="btn btn-primary btn-sm" href="{{ route('clients.edit', $client) }}">
								Editar
							</a>
							<form class="d-inline-block" method="POST" action="{{ route('clients.destroy', $client) }}">
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
			{{ $clients->links() }}
		</div>
	</div>
</div>
@endsection