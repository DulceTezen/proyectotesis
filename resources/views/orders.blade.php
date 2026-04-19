@extends('template')

@section('content')
<section class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="content">
					<h1 class="page-name">Pedidos</h1>
					<ol class="breadcrumb">
						<li><a href="{{ route('index') }}">Inicio</a></li>
						<li class="active">Pedidos</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="user-dashboard page-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="list-inline dashboard-menu text-center">
					<li><a href="{{ route('profile') }}">Perfil</a></li>
					<li><a class="active" href="{{ route('orders') }}">Pedidos</a></li>
				</ul>
				<div class="dashboard-wrapper user-dashboard">
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>Comprobante</th>
									<th>Número</th>
									<th>Total</th>
									<th>Fecha</th>
									<th>Estado</th>
									<th>Comprobante</th>
								</tr>
							</thead>
							<tbody>
								@foreach($sales as $sale)
								<tr>
									<td>{{ $sale->voucher }}</td>
									<td>{{ $sale->number }}</td>
									<td>S/. {{ $sale->total }}</td>
									<td>{{ $sale->date }}</td>
									<td>{{ $sale->status }}</td>
									<td>
										@if(strtolower($sale->status) === 'completado')
											<a href="{{ route('sales.pdf', $sale) }}" target="_blank" class="btn btn-main btn-small">
												PDF
											</a>
										@else
											<span class="text-muted">No disponible</span>
										@endif
									</td>

								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="d-flex justify-content-center">
						{{ $sales->links('pagination::bootstrap-5') }}
						<style> /* Fondo negro de los botones */
							.pagination .page-link {
								background-color: #000;
								color: #fff;
								border: 1px solid #000;
							}

							/* Hover */
							.pagination .page-link:hover {
								background-color: #333;
								color: #fff;
							}

							/* Página activa */
							.pagination .active .page-link {
								background-color: #000;
								border-color: #000;
								color: #fff;
							}

							/* Deshabilitados (Anterior/Siguiente) */
							.pagination .disabled .page-link {
								background-color: #111;
								color: #777;
								border-color: #111;
							}
							</style>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection