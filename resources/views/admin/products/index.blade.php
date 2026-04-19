@extends('admin.template')

@section('title', 'Productos')

@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
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
						<th></th>
						<th>Nombre</th>
						<th>Código</th>
						<th>Categoría</th>
						<th>Marca</th>
						<th>Precio</th>
						<th>Stock</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($products as $product)
					<tr>
						<td>
							<img src="{{ asset('storage/'.$product->image) }}" width="32">
						</td>
						<td>{{ $product->name }}</td>
						<td>{{ $product->code }}</td>
						<td>{{ $product->category->name }}</td>
						<td>{{ $product->brand->name }}</td>
						<td>{{ $product->price }}</td>
						<td>{{ $product->stock }}</td>
						<td>
							<a class="btn btn-primary btn-sm" href="{{ route('products.edit', $product) }}">
								Editar
							</a>
							<form class="d-inline-block" method="POST" action="{{ route('products.destroy', $product) }}">
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
			{{ $products->links() }}
		</div>
	</div>
</div>
@endsection