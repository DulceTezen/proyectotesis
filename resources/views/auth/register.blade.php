<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>LEXUS</title>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">

	<link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/web/images/favicon.png') }}" />
	<link rel="stylesheet" href="{{ asset('assets/web/plugins/themefisher-font/style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/web/plugins/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/web/plugins/animate/animate.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/web/plugins/slick/slick.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/web/plugins/slick/slick-theme.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/web/css/style.css') }}">

</head>

<body id="body">

	<section class="signin-page account">
	  <div class="container">
	    <div class="row">
	      <div class="col-md-6 col-md-offset-3">
	        <div class="block text-center">
	          <a class="logo" href="">
	            MI TIENDA
	          </a>
	          <h2 class="text-center">Registrarse</h2>
	          <form class="text-left clearfix" action="{{ route('auth.store') }}" method="POST">
	          	@csrf
	            <div class="form-group">
	              <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Nombre">
	              @error('name')<div class="text-danger">{{ $message }}</div>@enderror
	            </div>
	            <div class="form-group">
	              <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Apellidos">
	              @error('last_name')<div class="text-danger">{{ $message }}</div>@enderror
	            </div>
	            <div class="form-group">
	              <input type="text" class="form-control" name="document" value="{{ old('document') }}" placeholder="DNI">
	              @error('document')<div class="text-danger">{{ $message }}</div>@enderror
	            </div>
	            <div class="form-group">
	              <input type="text" class="form-control" name="address" value="{{ old('address') }}" placeholder="Dirección">
	              @error('address')<div class="text-danger">{{ $message }}</div>@enderror
	            </div>
	            <div class="form-group">
	              <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Teléfono">
	              @error('phone')<div class="text-danger">{{ $message }}</div>@enderror
	            </div>
	            <div class="form-group">
	              <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Correo electrónico">
	              @error('email')<div class="text-danger">{{ $message }}</div>@enderror
	            </div>
	            <div class="form-group">
	              <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Contraseña">
	              @error('password')<div class="text-danger">{{ $message }}</div>@enderror
	            </div>
	            <div class="text-center">
	              <button type="submit" class="btn btn-main text-center">Registrarse</button>
	            </div>
	          </form>
	          <p class="mt-20">¿Ya tienes una cuenta? <a href="{{ route('auth.login') }}"> Iniciar sesión</a></p>
	        </div>
	      </div>
	    </div>
	  </div>
	</section>


	<!-- Main jQuery -->
	<script src="{{ asset('assets/web/plugins/jquery/dist/jquery.min.js') }}"></script>
	<!-- Bootstrap 3.1 -->
	<script src="{{ asset('assets/web/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
	<!-- Bootstrap Touchpin -->
	<script src="{{ asset('assets/web/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') }}"></script>
	<!-- Instagram Feed Js -->
	<script src="{{ asset('assets/web/plugins/instafeed/instafeed.min.js') }}"></script>
	<!-- Video Lightbox Plugin -->
	<script src="{{ asset('assets/web/plugins/ekko-lightbox/dist/ekko-lightbox.min.js') }}"></script>
	<!-- Count Down Js -->
	<script src="{{ asset('assets/web/plugins/syo-timer/build/jquery.syotimer.min.js') }}"></script>

	<!-- slick Carousel -->
	<script src="{{ asset('assets/web/plugins/slick/slick.min.js') }}"></script>
	<script src="{{ asset('assets/web/plugins/slick/slick-animation.min.js') }}"></script>

	<!-- Google Mapl -->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCC72vZw-6tGqFyRhhg5CkF2fqfILn2Tsw"></script>
	<script type="text/javascript" src="{{ asset('assets/web/plugins/google-map/gmap.js') }}"></script>

	<!-- Main Js File -->
	<script src="{{ asset('assets/web/js/script.js') }}"></script>



</body>
</html>
