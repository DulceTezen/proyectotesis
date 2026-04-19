@extends('admin.template')

@section('title', 'Ventas - Editar')

@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<form method="POST" action="{{ route('sales.update', $sale) }}" enctype="multipart/form-data">
				@csrf
				@method('PATCH')
				<div class="row">
					<div class="col-md-3">
						<div class="mb-3">
							<label>Comprobante</label>
							<input type="text" class="form-control" value="{{ $sale->voucher }}" disabled>
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Número</label>
							<input type="text" class="form-control" value="{{ $sale->number }}" disabled>
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Cliente</label>
							<input type="text" class="form-control" value="{{ $sale->client->name }}" disabled>
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Total</label>
							<input type="text" class="form-control" value="{{ $sale->total }}" disabled>
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Fecha</label>
							<input type="text" class="form-control" value="{{ $sale->date }}" disabled>
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Estado</label>
							<select class="form-select" name="status">
								<option value="">Seleccionar</option>
								<option value="Pendiente"
								@if($sale->status == 'Pendiente') selected @endif>Pendiente</option>
								<option value="En proceso"
								@if($sale->status == 'En proceso') selected @endif>En proceso</option>
								<option value="Completado"
								@if($sale->status == 'Completado') selected @endif>Completado</option>
								<option value="Rechazado" 
								@if($sale->status == 'Rechazado') selected @endif>Rechazado</option>
							</select>
							@error('status')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-sm">Guardar</button>
			</form>
		</div>
		<div class="table-responsive">
			<table class="table">
				<thead class="thead-light">
					<tr>
						<th>Producto</th>
						<th>Precio</th>
						<th>Cantidad</th>
					</tr>
				</thead>
				<tbody>
					@foreach($sale->details as $detail)
					<tr>
						<td>{{ $detail->product->name }}</td>
						<td>{{ $detail->price }}</td>
						<td>{{ $detail->quantity }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection