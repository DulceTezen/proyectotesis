@extends('template')

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

<section class="products section">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<form method="get">
					<div class="widget">
						<h4 class="widget-title">Precio</h4>
						<div class="row">
							<div class="col-md-6">
								<input type="text" class="form-control" name="min_price" placeholder="Min" value="{{ request()->min_price }}">
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control" name="max_price" placeholder="Max" value="{{ request()->max_price }}">
							</div>
						</div>
					</div>
					<div class="widget">
						<h4 class="widget-title">Categorías</h4>
						<select class="form-control" name="category_id">
							<option value="">Todos</option>
							@foreach($categories as $category)
							<option value="{{ $category->id }}" 
								@if($category->id == request()->category_id) selected @endif>
								{{ $category->name }}
							</option>
							@endforeach
						</select>
					</div>
					<div class="widget">
						<h4 class="widget-title">Marcas</h4>
						<select class="form-control" name="brand_id">
							<option value="">Todos</option>
							@foreach($brands as $brand)
							<option value="{{ $brand->id }}" 
								@if($brand->id == request()->brand_id) selected @endif>
								{{ $brand->name }}
							</option>
							@endforeach
						</select>
					</div>
					<button type="submit" class="btn btn-main btn-small">Filtrar</button>
				</form>
			</div>
			<div class="col-md-9">
				<div class="row">
					@if($products->count() > 0)
					@foreach($products as $product)
					<div class="col-md-4">
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
								<h4 class="text-truncate"><a href="{{ route('product', $product) }}">{{ $product->name }}</a></h4>
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
				<div class="d-flex justify-content-center mt-3">{{ $products->withQueryString()->links() }} 
					
				<style>
					/* Fondo negro de los botones */
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

					/* Deshabilitados */
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
</section>
@endsection