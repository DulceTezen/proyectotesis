@extends('template')

@section('content')
<section class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="content">
					<h1 class="page-name">Libro de reclamaciones</h1>
					<ol class="breadcrumb">
						<li><a href="{{ route('index') }}">Inicio</a></li>
						<li class="active">Libro de reclamaciones</li>
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
				<div class="dashboard-wrapper user-dashboard">
					<form method="POST" action="{{ route('book_store') }}">
						@csrf
						<div class="row">
							<div class="col-md-12">
								<h3>Datos del consumidor</h3>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Nombre</label>
									<input type="text" class="form-control" name="name">
									@error('name')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Apellidos</label>
									<input type="text" class="form-control" name="last_name">
									@error('last_name')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>DNI</label>
									<input type="text" class="form-control" name="document">
									@error('document')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Ciudad</label>
									<input type="text" class="form-control" name="city">
									@error('city')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Dirección</label>
									<input type="text" class="form-control" name="address">
									@error('address')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Teléfono</label>
									<input type="text" class="form-control" name="phone">
									@error('phone')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Correo electrónico</label>
									<input type="text" class="form-control" name="email">
									@error('email')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-12">
								<h3>Bien contratado</h3>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Tipo de consumo</label>
									<select class="form-control" name="product_type">
										<option value="">Seleccionar</option>
										<option value="Producto">Producto</option>
										<option value="Servicio">Servicio</option>
									</select>
									@error('product_type')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Descripción</label>
									<input type="text" class="form-control" name="description">
									@error('description')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Monto reclamado</label>
									<input type="text" class="form-control" name="amount">
									@error('amount')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Número de pedido</label>
									<input type="text" class="form-control" name="order_number">
									@error('order_number')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-12">
								<h3>Detalle de la reclamación y pedido del consumidor</h3>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Reclamo</label>
									<input type="text" class="form-control" name="claim">
									@error('claim')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Pedido del consumidor</label>
									<input type="text" class="form-control" name="client_request">
									@error('client_request')
									<div class="text-danger">
										{{ $message }}
									</div>
									@enderror
								</div>
							</div>
						</div>
						<button type="submit" class="btn btn-main mt-20">Guardar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection