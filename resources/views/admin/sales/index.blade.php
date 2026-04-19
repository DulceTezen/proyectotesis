@extends('admin.template')

@section('title', 'Ventas')

@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<form>
				<div class="row">
					<div class="col-md-3">
						<div class="mb-3">
							<label>Número</label>
							<input class="form-control" name="number" value="{{ request()->number }}">
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Fecha</label>
							<input type="date" class="form-control" name="date" value="{{ request()->date }}">
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
						<th>Comprobante</th>
						<th>Número</th>
						<th>Cliente</th>
						<th>Total</th>
						<th>Fecha</th>
						<th>Estado</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($sales as $sale)
					<tr>
						<td>{{ $sale->voucher }}</td>
						<td>{{ $sale->number }}</td>
						<td>{{ $sale->client->name }}</td>
						<td>{{ $sale->total }}</td>
						<td>{{ $sale->date }}</td>
						<td>{{ $sale->status }}</td>
						<td>
							<a class="btn btn-primary btn-sm" href="{{ route('sales.edit', $sale) }}">
								Editar
							</a>
							<form class="d-inline-block" method="POST" action="{{ route('sales.destroy', $sale) }}">
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
			{{ $sales->links() }}
		</div>
	</div>
</div>
@endsection