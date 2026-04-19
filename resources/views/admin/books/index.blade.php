@extends('admin.template')

@section('title', 'Libros de reclamaciones')

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
						<th>Apellido</th>
						<th>Documento</th>
						<th>Tipo de insumo</th>
						<th>Monto</th>
						<th>Número de pedido</th>
						<th>Fecha</th>
					</tr>
				</thead>
				<tbody>
					@foreach($books as $book)
					<tr>
						<td>{{ $book->name }}</td>
						<td>{{ $book->last_name }}</td>
						<td>{{ $book->document }}</td>
						<td>{{ $book->product_type }}</td>
						<td>{{ $book->amount }}</td>
						<td>{{ $book->order_number }}</td>
						<td>{{ $book->date }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="card-footer">
			{{ $books->links() }}
		</div>
	</div>
</div>
@endsection