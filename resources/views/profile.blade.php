@extends('template')

@section('content')
<section class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="content">
					<h1 class="page-name">Perfil</h1>
					<ol class="breadcrumb">
						<li><a href="{{ route('index') }}">Inicio</a></li>
						<li class="active">Perfil</li>
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
					<li><a class="active" href="{{ route('profile') }}">Perfil</a></li>
					<li><a  href="{{ route('orders') }}">Pedidos</a></li>
				</ul>
				<div class="dashboard-wrapper user-dashboard">
					<form method="POST" action="{{ route('update') }}">
						@csrf
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Nombre</label>
									<input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Apellidos</label>
									<input type="text" class="form-control" value="{{ auth()->user()->last_name }}" disabled>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>DNI</label>
									<input type="text" class="form-control" value="{{ auth()->user()->document }}" disabled>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Dirección</label>
									<input type="text" class="form-control" name="address" value="{{ auth()->user()->address }}">
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
									<input type="text" class="form-control" name="phone" value="{{ auth()->user()->phone }}">
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
									<input type="text" class="form-control" name="email" value="{{ auth()->user()->email }}">
									@error('email')
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