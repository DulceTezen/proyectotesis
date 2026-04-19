@extends('admin.template')

@section('title', 'Productos - Crear nuevo')

@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
				@csrf
				<div class="row">
					<div class="col-md-3">
						<div class="mb-3">
							<label>Nombre</label>
							<input type="text" class="form-control" name="name" value="{{ old('name') }}">
							@error('name')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Código</label>
							<input type="text" class="form-control" name="code" value="{{ old('code') }}">
							@error('code')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Descripción</label>
							<input type="text" class="form-control" name="description" value="{{ old('description') }}">
							@error('description')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Categoría</label>
							<select class="form-select" name="category_id">
								<option value="">Seleccionar</option>
								@foreach($categories as $category)
								<option value="{{ $category->id }}" 
									@if($category->id == old('category_id')) selected @endif>
									{{ $category->name }}
								</option>
								@endforeach
							</select>
							@error('category_id')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Marca</label>
							<select class="form-select" name="brand_id">
								<option value="">Seleccionar</option>
								@foreach($brands as $brand)
								<option value="{{ $brand->id }}" 
									@if($brand->id == old('brand_id')) selected @endif>
									{{ $brand->name }}
								</option>
								@endforeach
							</select>
							@error('brand_id')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Precio</label>
							<input type="text" class="form-control" name="price" value="{{ old('price') }}">
							@error('price')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Stock</label>
							<input type="text" class="form-control" name="stock" value="{{ old('stock') }}">
							@error('stock')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="mb-3">
							<label>Imagen</label>
							<input type="file" class="form-control" name="image" value="{{ old('image') }}">
							@error('image')
							<div class="text-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-sm">Guardar</button>
			</form>
		</div>
	</div>
</div>
@endsection