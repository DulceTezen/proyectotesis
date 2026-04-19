@extends('admin.template')

@section('title', 'Marcas - Editar')

@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<form method="POST" action="{{ route('brands.update', $brand) }}">
				@csrf
				@method('PATCH')
				<div class="row">
					<div class="col-md-3">
						<div class="mb-3">
							<label>Nombre</label>
							<input class="form-control" name="name" value="{{ old('name', $brand->name) }}">
							@error('name')
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