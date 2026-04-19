@extends('admin.template')

@section('title', 'Usuarios - Editar')

@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<form method="POST" action="{{ route('users.update', $user) }}">
				@csrf
				@method('PATCH')
				<div class="row">
					<div class="col-md-3">
						<div class="mb-3">
							<label>Nombre</label>
							<input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}">
							@error('name')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Apellidos</label>
							<input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->last_name) }}">
							@error('last_name')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Usuario</label>
							<input type="text" class="form-control" name="user" value="{{ old('user', $user->user) }}">
							@error('user')
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