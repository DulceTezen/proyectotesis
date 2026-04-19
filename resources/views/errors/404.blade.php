@extends('template')

@section('content')
<section class="page-404">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Error 404</h2>
				<h2>Página no encontrada</h2>
				<a class="btn btn-main" href="{{ route('index') }}">
					Ir a inicio
				</a>
			</div>
		</div>
	</div>
</section>
@endsection