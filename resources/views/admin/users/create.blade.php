@extends('admin.template')

@section('title', 'Usuarios - Crear nuevo')

@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<form method="POST" action="{{ route('users.store') }}">
				@csrf
				<div class="row">
					<div class="col-md-3">
						<div class="mb-3">
							<label>Nombre</label>
							<input type="text" class="form-control" name="name" value="{{ old('name') }}">
							@error('name')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Apellidos</label>
							<input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
							@error('last_name')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Usuario</label>
							<input type="text" class="form-control" name="user" value="{{ old('user') }}">
							@error('user')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Contraseña</label>
							<input type="text" class="form-control" name="password" value="{{ old('password') }}">
							@error('password')
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