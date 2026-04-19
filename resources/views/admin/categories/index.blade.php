@extends('admin.template')

@section('title', 'Categorías')

@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
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
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($categories as $category)
					<tr>
						<td>{{ $category->name }}</td>
						<td>
							<a class="btn btn-primary btn-sm" href="{{ route('categories.edit', $category) }}">
								Editar
							</a>
							<form class="d-inline-block" method="POST" action="{{ route('categories.destroy', $category) }}">
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
			{{ $categories->links() }}
		</div>
	</div>
</div>
@endsection