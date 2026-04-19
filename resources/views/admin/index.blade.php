@extends('admin.template')

@section('title', 'Inicio')

@section('content')
<div class="col-md-4">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Clientes</h5>
			<h1 class="text-center">{{ $clients }}</h1>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Productos</h5>
			<h1 class="text-center">{{ $products }}</h1>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Ventas</h5>
			<h1 class="text-center">S/{{ $sales }}</h1>
		</div>
	</div>
</div>
@endsection
