@extends('admin.template')

@section('title', 'Clientes - Editar')

@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<form method="POST" action="{{ route('clients.update', $client) }}" enctype="multipart/form-data">
				@csrf
				@method('PATCH')
				<div class="row">
					<div class="col-md-3">
						<div class="mb-3">
							<label>Nombre</label>
							<input type="text" class="form-control" name="name" value="{{ old('name', $client->name) }}">
							@error('name')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Apellidos</label>
							<input type="text" class="form-control" name="last_name" value="{{ old('last_name', $client->last_name) }}">
							@error('last_name')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Documento</label>
							<input type="text" class="form-control" name="document" value="{{ old('document', $client->document) }}">
							@error('document')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Dirección</label>
							<input type="text" class="form-control" name="address" value="{{ old('address', $client->address) }}">
							@error('address')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Teléfono</label>
							<input type="text" class="form-control" name="phone" value="{{ old('phone', $client->phone) }}">
							@error('phone')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Correo electrónico</label>
							<input type="text" class="form-control" name="email" value="{{ old('email', $client->email) }}">
							@error('email')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-sm">Guardar</button>
			</form>
		</div>
	</div>
</div>
@endsection