@extends('template')

@section('title', 'Nosotros')

@section('content')
<section class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="content">
					<h1 class="page-name" style=" font-weight: bold;"> LEXUS </h1>
					<ol class="breadcrumb">
						<li><a href="{{ route('index') }}">Inicio</a></li>
						<li class="active">LEXUS</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>

<main class="about-page">
	<section class="about-story section">
		<div class="container">
			<div class="row about-story__row">
				<div class="col-md-6">
					<figure class="about-story__media">
						<img class="img-responsive" src="{{ asset('assets/web/images/about/about.png') }}" alt="Marcas originales disponibles en LEXUS">
						<figcaption>Selecci&oacute;n de marcas con respaldo y autenticidad.</figcaption>
					</figure>
				</div>
				<div class="col-md-6">
					<div class="about-story__content">
						<p class="about-section-label">Qui&eacute;nes somos</p>
						<h2>Una tienda pensada para comprar zapatillas con confianza.</h2>
						<p>
							LEXUS es una empresa especializada en la comercializaci&oacute;n de zapatillas
							originales e importadas de marcas reconocidas a nivel mundial. Nuestro
							prop&oacute;sito es acercar a cada cliente a modelos actuales, ediciones destacadas
							y productos con calidad comprobada.
						</p>
						<p>
							A trav&eacute;s de nuestra tienda online buscamos elevar la experiencia de compra
							en el mercado de calzado urbano, combinando autenticidad, estilo, asesor&iacute;a
							y un servicio pensado para que cada elecci&oacute;n se sienta segura desde el
							primer clic.
						</p>
						<div class="about-story__metrics" aria-label="Compromisos comerciales">
							<div>
								<strong>100%</strong>
								<span>original</span>
							</div>
							<div>
								<strong>Online</strong>
								<span>simple y seguro</span>
							</div>
							<div>
								<strong>Urbano</strong>
								<span>actual y vers&aacute;til</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="about-purpose section">
		<div class="container">
			<div class="row">
				<div class="col-md-5">
					<div class="about-purpose__intro">
						<p class="about-section-label">Nuestro rumbo</p>
						<h2>Una marca construida sobre confianza, selecci&oacute;n y estilo.</h2>
						<p>
							Cada producto, recomendaci&oacute;n y proceso de compra debe reflejar una promesa
							simple: acercarte zapatillas originales que acompa&ntilde;en tu forma de vestir,
							moverte y expresarte.
						</p>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row about-purpose__grid">
						<div class="col-sm-6">
							<article class="about-purpose-card">
								<span class="about-purpose-card__icon">01</span>
								<h3>Misi&oacute;n</h3>
								<p>
									Ofrecer zapatillas originales e importadas de marcas reconocidas,
									brindando una experiencia de compra confiable, cercana y orientada
									a las necesidades de cada cliente.
								</p>
							</article>
						</div>
						<div class="col-sm-6">
							<article class="about-purpose-card about-purpose-card--accent">
								<span class="about-purpose-card__icon">02</span>
								<h3>Visi&oacute;n</h3>
								<p>
									Consolidarnos como una tienda referente en calzado urbano, reconocida
									por su autenticidad, curadur&iacute;a de productos y capacidad de conectar
									tendencias globales con clientes locales.
								</p>
							</article>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="about-values section">
		<div class="container">
			<div class="about-values__header">
				<p class="about-section-label">Lo que nos diferencia</p>
				<h2>Detalles que hacen mejor cada compra.</h2>
			</div>
			<div class="row">
				<div class="col-sm-6 col-md-3">
					<div class="about-value">
						<span class="tf-ion-checkmark-circled"></span>
						<h3>Autenticidad</h3>
						<p>Trabajamos con productos originales y marcas reconocidas.</p>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="about-value">
						<span class="tf-ion-star"></span>
						<h3>Curadur&iacute;a</h3>
						<p>Seleccionamos modelos con presencia, calidad y valor de estilo.</p>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="about-value">
						<span class="tf-ion-bag"></span>
						<h3>Compra clara</h3>
						<p>Un recorrido online simple para elegir, confirmar y recibir soporte.</p>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="about-value">
						<span class="tf-ion-ios-location"></span>
						<h3>Cercan&iacute;a</h3>
						<p>Atenci&oacute;n enfocada en resolver dudas y acompa&ntilde;ar tu elecci&oacute;n.</p>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
@endsection
