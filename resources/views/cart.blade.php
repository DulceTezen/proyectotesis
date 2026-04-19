@extends('template')

@section('content')
<section class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="content">
					<h1 class="page-name">Carrito</h1>
					<ol class="breadcrumb">
						<li><a href="{{ route('index') }}">Inicio</a></li>
						<li class="active">Carrito</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="page-wrapper">
  <div class="cart shopping">
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="block">
            <div class="product-list">
            @if(session('error'))
              <div class="alert alert-danger">
                {{ session('error') }}
              </div>
            @endif

            @if(session('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>
            @endif
              @if(count($cart) > 0)
              <table class="table">
                <thead>
                  <tr>
                    <th class="">Producto</th>
                    <th class="">Precio</th>
                    <th class="">Cantidad</th>
                    <th class="">Subtotal</th>
                    <th class="">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($cart as $id => $item)
                  <tr class="">
                    <td class="">
                      <div class="product-info">
                        <img width="80" src="{{ asset('storage/'.$item['image']) }}" alt="" />
                        <a href="{{ route('product', $id) }}">{{ $item['name'] }}</a>
                      </div>
                    </td>
                    <td class="">S/{{ $item['price'] }}</td>
                    <td class="">
                      <form method="POST" action="{{ route('cart.update') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $id }}">
                        <input type="text" class="form-control" name="quantity" value="{{ $item['quantity'] }}" style="width: 100px">
                      </form>
                    </td>
                    <td class="">
                    S/{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                    <td class="">
                      <form method="POST" action="{{ route('cart.remove') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $id }}">
                        <a class="product-remove" href="#!" onclick="this.closest('form').submit()">Eliminar</a>
                      </form>
                    </td>
                  </tr>
                  @endforeach
                  <tr>
                    <td colspan="5" align="right">
                      <span class="h5">
                        TOTAL: S/{{ number_format($total, 2) }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>

              <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <a class="product-remove" href="#!" onclick="this.closest('form').submit()">Vaciar carrito</a>
              </form>

              <a href="{{ route('checkout') }}" class="btn btn-main pull-right">Finalizar compra</a>
              @else
              <div class="text-center">
                <p>No hay productos en el carrito</p>
                <a href="{{ route('shop') }}" class="btn btn-main">Ir a tienda</a>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection