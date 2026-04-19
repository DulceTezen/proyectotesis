@extends('template')

@section('content')
<section class="single-product">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<ol class="breadcrumb">
					<li><a href="{{ route('index') }}">Inicio</a></li>
					<li><a href="{{ route('shop') }}">LEXUS</a></li>
					<li class="active">{{ $product->name }}</li>
				</ol>
			</div>
		</div>
		<div class="row mt-20">
			<div class="col-md-5">
				<div class="single-product-slider">
					<div id='carousel-custom' class='carousel slide' data-ride='carousel'>
						<div class='carousel-outer'>
							<!-- me art lab slider -->
							<div class='carousel-inner '>
								<div class='item active'>
									<img src='{{ asset('storage/'.$product->image) }}' alt='' data-zoom-image="{{ asset('storage/'.$product->image) }}" />
								</div>
							</div>
							
							<!-- sag sol -->
							<a class='left carousel-control' href='#carousel-custom' data-slide='prev'>
								<i class="tf-ion-ios-arrow-left"></i>
							</a>
							<a class='right carousel-control' href='#carousel-custom' data-slide='next'>
								<i class="tf-ion-ios-arrow-right"></i>
							</a>
						</div>
						
						<!-- thumb -->
						<ol class='carousel-indicators mCustomScrollbar meartlab'>
							<li data-target='#carousel-custom' data-slide-to='0' class='active'>
								<img src='{{ asset('assets/web/images/shop/products/product-1.jpg') }}' alt='' />
							</li>
						</ol>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="single-product-details">
					<h2>{{ $product->name }}</h2>
					<p class="product-price">S/{{ $product->price }}</p>
					
					<p class="product-description mt-20">
						{{ $product->description }}
					</p>

					<div class="product-category">
						<span>Código:</span>
						<ul>
							<li>{{ $product->code }}</li>
						</ul>
					</div>
					<div class="product-category">
						<span>Categoría:</span>
						<ul>
							<li>{{ $product->category->name }}</li>
						</ul>
					</div>
					<div class="product-category">
						<span>Marca:</span>
						<ul>
							<li>{{ $product->brand->name }}</li>
						</ul>
					</div>

					<form method="POST" action="{{ route('cart.add') }}">
						@csrf
						<input type="hidden" name="id" value="{{ $product->id }}">
						<div class="product-quantity">
							<span>Cantidad:</span>
							<div class="product-quantity-slider">
								<input class="form-control" type="text" value="1" name="quantity">
							</div>
						</div>
						<button type="submit" class="btn btn-main mt-20">Añadir al carrito</button>
					</form>

				</div>
			</div>
		</div>
	</div>
</section>
@endsection