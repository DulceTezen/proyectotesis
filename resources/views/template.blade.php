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

<header class="site-header">
<!-- Start Top Header Bar -->
<section class="top-header">
	
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-xs-12 col-sm-4">
				<div class="contact-number">
					<i class="tf-ion-ios-telephone"></i>
					<span>956 664 655</span>
				</div>
			</div>
			<div class="col-md-4 col-xs-12 col-sm-4">
				<div class="logo text-center">
					<a href="{{ route('index') }}">
						<!-- replace logo here -->
						<svg width="200px" height="40px" viewBox="0 0 200 40" version="1.1" xmlns="http://www.w3.org/2000/svg"
							xmlns:xlink="http://www.w3.org/1999/xlink">
							<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" font-size="40"
								font-family="AustinBold, Austin" font-weight="bold">
								<g id="Group" transform="translate(-108.000000, -297.000000)" fill="#000000">
									<text id="LEXUS">
										<tspan x="108.94" y="325">LEXUS</tspan>
									</text>
								</g>
							</g>
						</svg>
					</a>
				</div>
			</div>
			<!--<div class="col-md-4 col-xs-12 col-sm-4">-->
				<!-- Site Logo -->
				<!--<div class="logo text-center">
					<a href="{{ route('index') }}">
						RUN RUN
					</a>
				</div>
			
			-->
			<div class="col-md-4 col-xs-12 col-sm-4">
				<!-- Cart -->
				<ul class="top-menu text-right list-inline">
					<li class="dropdown cart-nav dropdown-slide {{ request()->routeIs('cart') ? 'active' : '' }}">
						<a href="{{ route('cart') }}">
							<i class="tf-ion-android-cart"></i> Carrito
						</a>
					</li><!-- / Cart -->

					<!-- Search -->
					<li class="dropdown search dropdown-slide">
						<a href="#!" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"><i class="tf-ion-ios-search-strong"></i> Buscar</a>
						<ul class="dropdown-menu search-dropdown">
							<li>
								<form method="GET" action="{{ route('shop') }}">
									<input type="search" class="form-control" name="search" placeholder="Buscar...">
								</form>
							</li>
						</ul>
					</li><!-- / Search -->

				</ul><!-- / .nav .navbar-nav .navbar-right -->
			</div>
		</div>
	</div>
</section><!-- End Top Header Bar -->
<!------------------->

  <!--------------------------------------------------->
<!-- Main Menu Section -->
<section class="menu">
	<nav class="navbar navigation">
		<div class="container">
			<div class="navbar-header">
				<h2 class="menu-title">Menú Principal</h2>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
					aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

			</div><!-- / .navbar-header -->

			<!-- Navbar Links -->
			<div id="navbar" class="navbar-collapse collapse text-center">
				<ul class="nav navbar-nav">


					<li class="dropdown {{ request()->routeIs('index') ? 'active' : '' }}">
						<a href="{{ route('index') }}">Inicio</a>
					</li>
					<li class="dropdown {{ request()->routeIs('about') ? 'active' : '' }}">
						<a href="{{ route('about') }}">Nosotros</a>
					</li>
					<li class="dropdown {{ request()->routeIs('shop') || request()->routeIs('product') ? 'active' : '' }}">
						<a href="{{ route('shop') }}">Tienda</a>
					</li>

					

					@if(auth()->guard('web')->check())
					<li class="dropdown dropdown-slide {{ request()->routeIs('profile') || request()->routeIs('orders') ? 'active' : '' }}">
						<a href="#!" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="350"
							role="button" aria-haspopup="true" aria-expanded="false">Hola {{ auth()->guard('web')->user()->name }} <span
								class="tf-ion-ios-arrow-down"></span></a>
						<ul class="dropdown-menu">
							<li><a href="{{ route('profile') }}">Perfil</a></li>
							<li><a href="{{ route('orders') }}">Pedidos</a></li>
							<li><a href="{{ route('auth.logout') }}">Cerrar sesión</a></li>
						</ul>
					</li>
					@else
					<li class="dropdown {{ request()->routeIs('auth.login') ? 'active' : '' }}">
						<a href="{{ route('auth.login') }}">Ingresar</a>
					</li>
					@endif

				</ul><!-- / .nav .navbar-nav -->

			</div>
			<!--/.navbar-collapse -->
		</div><!-- / .container -->
	</nav>
</section>
</header>

@yield('content')

<footer class="footer section text-center">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="social-media">
					<li>
						<a href="https://www.facebook.com/profile.php?id=61579920332036">
							<i class="tf-ion-social-facebook"></i>
						</a>
						
					</li>
					<li>
						<a href="https://www.instagram.com/d759289236/">
							<i class="tf-ion-social-instagram"></i>
						</a>
					</li>
				</ul>
				<ul class="footer-menu text-uppercase">

					<li>
						<a href="{{ route('index') }}">INICIO</a>
					</li>
					<li>
						<a href="{{ route('about') }}">NOSOTROS</a>
					</li>
					<li>
						<a href="{{ route('shop') }}">TIENDA</a>
					</li>
					<li>
						<a href="{{ route('cart') }}">CARRITO</a>
					</li>
					<li>
						<a href="{{ route('book') }}">LIBRO DE RECLAMACIONES</a>
					</li>
				</ul>
				<p class="copyright-text">Copyright &copy;2025.LEXUS</a></p>
			</div>
		</div>
	</div>
</footer>

    <!-- 
    Essential Scripts
    =====================================-->
    
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

    <!-- Main Js File -->
    <script src="{{ asset('assets/web/js/script.js') }}"></script>


  </body>
  </html>
