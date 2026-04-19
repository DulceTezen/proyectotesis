@extends('template')

@section('title', 'Nosotros')

@section('content')
<section class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="content">
					<h1 class="page-name" style=" font-weight: bold;">Nosotros</h1>
					<ol class="breadcrumb">
						<li><a href="{{ route('index') }}">Inicio</a></li>
						<li class="active">Nosotros</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="about section">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<img class="img-responsive" src='{{ asset('assets/web/images/about/about.png') }}' style="width: 500px">
			</div>
			<div class="col-md-6">
				<h2 class="mt-50" style="text-align: center; font-weight: bold; font-family: Georgia, 'Times New Roman', serif;"> LEXUS</h2>
				<p style="text-align: justify">Es una empresa especializada en la comercialización de zapatillas originales e importadas de marcas reconocidas a nivel mundial. 
				Nuestro objetivo es ofrecer a nuestros clientes acceso exclusivo a los últimos modelos y ediciones limitadas de nuestros productos, 
				garantizando autenticidad, calidad y estilo. A través de nuestra tienda online, buscamos revolucionar la experiencia de compra en 
				el mercado de calzado urbano, conectando a los amantes de las zapatillas con los mejores productos del mundo.</p>
			</div>
		</div>

  </body>
  </html>
  <p></p>
  <br>
@endsection
