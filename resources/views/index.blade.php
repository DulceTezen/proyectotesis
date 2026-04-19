@extends('template')

@section('title', 'Inicio')

@section('content')
<div class="hero-slider">
  <div class="slider-item th-fullpage hero-area" style="background-image: url({{ asset('assets/web/images/slider/slider-1.jpg') }});">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 text-center">
          <p data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".1">PRODUCTOS ORIGINALES</p>
          <h1 data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".5">Las mejores marcas de zapatillas  <br> sólo en <br> LEXUS </h1>
          <a data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".8" class="btn" href="{{ route('shop') }}">Comprar ahora</a>
        </div>
      </div>
    </div>
  </div>
  <div class="slider-item th-fullpage hero-area" style="background-image: url({{ asset('assets/web/images/slider/slider-2.jpg') }});">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 text-left">
          <p data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".1">100 % originales, 100% TÚ</p>
          <h1 data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".5">Eleva tu estilo. <br> Sé tendencia desde tus pies.</h1>
          <a data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".8" class="btn" href="{{ route('shop') }}">Comprar ahora</a>
        </div>
      </div>
    </div>
  </div>
  <div class="slider-item th-fullpage hero-area" style="background-image: url({{ asset('assets/web/images/slider/slider-3.jpg') }});">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 text-right">
          <h1 data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".5">Para cada paso, <br> una zapatilla perfecta.</h1>
          <a data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".8" class="btn" href="{{ route('shop') }}">Comprar ahora</a>
        </div>
      </div>
    </div>
  </div>
</div>


<section class="products bg-gray">
	<div class="container">
		<div class="row">
			<div class="title text-center">
				<h2>Nuevos productos</h2>
			</div>
		</div>
		<div class="row">
			@if($products->count() > 0)
			@foreach($products as $product)
			<div class="col-md-3">
				<div class="product-item">
					<div class="product-thumb">
						<img class="img-responsive" src="{{ asset('storage/'.$product->image) }}" alt="product-img" />
						<div class="preview-meta">
							<ul>
								<li>
									<form method="POST" action="{{ route('cart.add') }}">
										@csrf
										<input type="hidden" name="id" value="{{ $product->id }}">
										<a href="#!" onclick="this.closest('form').submit()"><i class="tf-ion-android-cart"></i></a>
									</form>
								</li>
							</ul>
            </div>
					</div>
					<div class="product-content">
						<h4><a href="{{ route('product', $product) }}">{{ $product->name }}</a></h4>
						<p class="price">S/{{ $product->price }}</p>
					</div>
				</div>
			</div>
			@endforeach
			@else
			<div class="col-md-12">
				<div class="alert alert-info alert-common">
					No se han encontrado productos.
				</div>
			</div>
			@endif
		</div>
	</div>
</section>

<section class="products bg-gray">
	<div class="container">
		<div class="row">
			<div class="title text-center">
				<h2>Productos más vendidos</h2>
			</div>
		</div>
		<div class="row">
			@if($favorites->count() > 0)
			@foreach($favorites as $product)
			<div class="col-md-3">
				<div class="product-item">
					<div class="product-thumb">
						<img class="img-responsive" src="{{ asset('storage/'.$product->image) }}" alt="product-img" />
						<div class="preview-meta">
							<ul>
								<li>
									<form method="POST" action="{{ route('cart.add') }}">
										@csrf
										<input type="hidden" name="id" value="{{ $product->id }}">
										<a href="#!" onclick="this.closest('form').submit()"><i class="tf-ion-android-cart"></i></a>
									</form>
								</li>
							</ul>
            </div>
					</div>
					<div class="product-content">
						<h4><a href="{{ route('product', $product) }}">{{ $product->name }}</a></h4>
						<p class="price">S/{{ $product->price }}</p>
					</div>
				</div>
			</div>
			@endforeach
			@else
			<div class="col-md-12">
				<div class="alert alert-info alert-common">
					No se han encontrado productos.
				</div>
			</div>
			@endif
		</div>
	</div>
</section>
@endsection