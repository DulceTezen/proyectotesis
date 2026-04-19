@extends('template')

@section('title', 'Finalizar compra')

@section('content')
<section class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="content">
					<h1 class="page-name">Finalizar compra</h1>
					<ol class="breadcrumb">
						<li><a href="{{ route('index') }}">Inicio</a></li>
						<li class="active">Finalizar compra</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="page-wrapper">
	<div class="checkout shopping">
		<div class="container">
		<form class="checkout-form" method="POST" action="{{ route('finalize') }}">
			@csrf
			<input type="hidden" name="delivery_price" id="delivery_price">

			<div class="row">
				<div class="col-md-8">
					<div class="block billing-details">
						<h4 class="widget-title">Datos de facturación</h4>
						<div class="form-group">
							<label>Nombre</label>
							<input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" disabled>
						</div>
						<div class="form-group">
							<label>Apellidos</label>
							<input type="text" class="form-control" name="last_name" value="{{ auth()->user()->last_name }}" disabled>
						</div>
						<div class="form-group">
							<label>DNI</label>
							<input type="text" class="form-control" name="document" value="{{ auth()->user()->document }}" disabled>
						</div>
						<div class="form-group">
							<label>N° Contacto</label>
							<input type="text" class="form-control" name="phone" value="{{ auth()->user()->phone }}" >
						</div>
						<div class="form-group">
							<label>Dirección</label>
							<input type="text" class="form-control" name="direction"  placeholder="Chiclayo, La Victoria, Cuadra 12">
						</div>
						<div class="form-group">
                            <select class="form-control" name="voucher" id="voucherSelect">
                                <option value="">Tipo de comprobante</option>
                                <option value="Boleta" @if(old('voucher') == 'Boleta') selected @endif>Boleta</option>
                                <option value="Factura" @if(old('voucher') == 'Factura') selected @endif>Factura</option>
                            </select>
                            @error('voucher')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

						<div class="form-group" id="bussinessNameGroup">
                            <label>Razón social</label>
                            <input type="text" class="form-control" name="bussiness_name" id="bussiness_name" value="{{ old('bussiness_name') }}">
                        </div>
                        <div class="form-group" id="bussinessDocumentGroup">
                            <label>RUC</label>
                            <input type="text" class="form-control" name="bussiness_document" id="bussiness_document" value="{{ old('bussiness_document') }}" placeholder="Número DNI o C.E./ RUC">
                        </div>

						<script>
							function toggleBusinessFields() {
								const voucher = document.getElementById('voucherSelect').value;
								const bussinessNameGroup = document.getElementById('bussinessNameGroup');
								const bussinessDocumentGroup = document.getElementById('bussinessDocumentGroup');

								if (voucher === 'Factura') {
									bussinessNameGroup.style.display = 'block';
									bussinessDocumentGroup.style.display = 'block';
								} else {
									bussinessNameGroup.style.display = 'none';
									bussinessDocumentGroup.style.display = 'none';
								}
							}

							document.getElementById('voucherSelect').addEventListener('change', toggleBusinessFields);
							window.addEventListener('DOMContentLoaded', toggleBusinessFields);
						</script>
					</div> 

					<div class="block"> 
							<h4 class="widget-title">Tipo de envío</h4>
						<select class="form-control" name="delivery_id" id="deliverySelect">
							<option value="">Seleccionar</option>
							@foreach($deliveries as $delivery)
								<option value="{{ $delivery->id }}" 
										data-name="{{ $delivery->name }}" 
										data-price="{{ $delivery->price }}"
										{{ old('delivery_id') == $delivery->id ? 'selected' : '' }}>
									{{ $delivery->name }}
								</option>
							@endforeach
						</select>
					

    {{-- CAMPOS NORMALES DE DIRECCIÓN --}}
    <div id="addressFields" style="display: none; margin-top:15px;">
		<div class="form-group">
            <label>Departamento</label>
            <input type="text" class="form-control" name="departamento" value="Lambayeque" readonly>
        </div>
		<div class="form-group">
            <label>Ciudad</label>
            <input type="text" class="form-control" name="city" value="Chiclayo" readonly>
        </div>
		
        <div class="form-group">
            <select class="form-control" name="district" id="districtSelect">
				<option value="">Seleccione un distrito</option>
								<option value="Chiclayo">Chiclayo</option>
								<option value="José Leonardo Ortiz">José Leonardo Ortiz</option>
								<option value="La Victoria">La Victoria</option>
								<option value="Pimentel">Pimentel</option>
								<option value="Santa Victoria">Santa Victoria</option>
								<option value="Reque">Reque</option>
								<option value="Monsefú">Monsefú</option>
								<option value="Eten">Eten</option>
								<option value="Eten Puerto">Eten Puerto</option>
								<option value="Picsi">Picsi</option>
								<option value="Pomalca">Pomalca</option>
								<option value="Tuman">Tuman</option>
								<option value="Pucalá">Pucalá</option>
								<option value="Santa Rosa">Santa Rosa</option>
								<option value="Cayaltí">Cayaltí</option>
								<option value="Pátapo">Pátapo</option>
								<option value="Lagunas">Lagunas</option>
								<option value="Oyotún">Oyotún</option>
								<option value="Nueva Arica">Nueva Arica</option>
								<option value="Zaña">Zaña</option>
								<option value="Chongoyape">Chongoyape</option>
            </select>
			@error('district')
				<div class="text-danger small">{{ $message }}</div>
			@enderror
        </div>
        <div class="form-group">
            <label>Dirección</label>
			<input type="text" class="form-control" name="address" value="{{ old('address', auth()->user()->address) }}" placeholder="Cuadra, sector, etc." required>
				@error('address')
					<div class="text-danger small">
					 {{ $message }}
					</div>
				@enderror        
		</div>
        <div class="form-group">
            <label>Referencia</label>
			<input type="text" class="form-control" id="reference" name="reference" value="{{ old('reference', auth()->user()->reference) }}" placeholder="Ej: Frente al parque, cerca al hospital, etc.">
				@error('address')
					<div class="text-danger small">
						{{ $message }}
					</div>
				@enderror
        </div>
    </div>

	{{-- RECOJO EN TIENDA --}} 
	<div id="storePickup" style="display: none; margin-top:15px;">
		<div style="border: 2px solid #28a745; border-radius: 10px; padding: 15px; background: #f8fff8; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
			<h5 class="text-success">📍 RECOJO EN TIENDA</h5>
			<p><strong>Dirección:</strong> Av. Luis Gonzales 1420 - Chiclayo</p>
			<p><strong>Horario de atención:</strong> 🕒 Lunes a Sábado de 8:00 a.m. a 2:00 p.m.</p>

			{{-- Campos ocultos para guardar en BD --}}
			<input type="hidden" name="address" value="RECOJO EN TIENDA - Av. Luis Gonzales 1420 - Chiclayo">
			<input type="hidden" name="reference" value="Interior 7 - Galería El Ferretero">
			<input type="hidden" name="city" value="Chiclayo">
			<input type="hidden" name="district" value="Chiclayo">
		</div>
	</div>

</div>
					<div class="block">
						<h4 class="widget-title">Método de pago</h4>
						<div class="checkout-product-details">
							<div class="payment">

								<div class="form-group">
									<select class="form-control" name="payment_method_id" id="paymentSelect">
										<option value="">Seleccionar</option>
										@foreach($payment_methods as $payment_method)
											<option value="{{ $payment_method->id }}" 
													data-name="{{ $payment_method->name }}"
													@if(old('payment_method_id') == $payment_method->id) selected @endif>
												{{ $payment_method->name }}
											</option>
										@endforeach
									</select>
									<!-- Modal  -->


										<!-- Modal YAPE-->
										<!-- Modal Yape Mejorado -->
											<div class="modal fade" id="yapeModal" tabindex="-1" role="dialog" aria-labelledby="yapeModalLabel">
											<div class="modal-dialog" role="document">
												<div class="modal-content text-center" style="border-radius:15px; overflow:hidden; box-shadow:0 5px 15px rgba(0,0,0,0.3);">

												<!-- Header -->
												<div class="modal-header bg-warning text-dark" style="border-bottom:none;">
													<h4 class="modal-title w-100 font-weight-bold" id="yapeModalLabel">Paga con Yape</h4>
													<button type="button" class="close text-dark" data-dismiss="modal" aria-label="Cerrar" style="opacity:1;">
													<span aria-hidden="true">&times;</span>
													</button>
												</div>

												<!-- Body -->
												<div class="modal-body" style="padding:30px; background:#fdf8f0;">

													<!-- QR -->
													<p style="font-weight:bold; color:#333;">Escanea este código QR con tu app de Yape:</p>
													<img src="assets/admin/img/yape-qr.jpg" alt="QR Yape" class="img-responsive img-rounded center-block" 
														style="max-width:250px; border-radius:15px; box-shadow:0 2px 10px rgba(0,0,0,0.2); margin-bottom:20px;">

													<!-- Información -->
													<table class="table no-border text-left" style="max-width:400px; margin:0 auto; background:#fff; border-radius:10px; padding:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
													<tr>
														<th style="width:120px;">Nombre</th>
														<td>Dulce Milagros Tezen Villanueva</td>
													</tr>
													<tr>
														<th>Número Yape</th>
														<td id="yapeNumber">956 664 655</td>
													</tr>
													<tr>
														<th>Monto a pagar</th>
														<td>S/ {{ number_format($total, 2) }}</td>
													</tr>
													</table>

													<!-- Botón copiar número -->
													<button class="btn btn-info btn-block btn-lg" onclick="copyToClipboard('956664655')" style="margin-top:15px; border-radius:10px;">
													Copiar número
													</button>

													<!-- Instrucciones -->
													<hr style="margin:25px 0; border-top:2px solid #ffe0b2;">
													<h5 style="font-weight:bold; color:#333;">Pasos para pagar:</h5>
													<ul class="text-left pasos-yape" style="margin-left:20px; color:#555; font-size:14px;">
													<li>1. Abre tu app Yape.</li>
													<li>2. Escanea el código QR o copia el número.</li>
													<li>3. Verifica el nombre antes de confirmar el pago.</li>
													<li>4. Envía tu comprobante por WhatsApp.</li>
													</ul>

													<!-- Botón enviar comprobante -->
													<a href="https://wa.me/51956664655?text=Hola,+te+env%C3%ADo+mi+comprobante+de+Yape+por+S/{{ number_format($total,2) }}" 
													target="_blank" class="btn btn-success btn-lg btn-block" style="margin-top:20px; border-radius:10px; font-weight:bold;">
													Enviar comprobante por WhatsApp
													</a>

													<!-- Botón Cerrar -->
													<button type="button" class="btn btn-danger btn-lg btn-block" data-dismiss="modal" style="margin-top:10px; border-radius:10px;">
													Cerrar
													</button>
													<div style="margin-top:10px; font-size:12px; color:#777;">⚠️ Asegúrate de enviar el comprobante después de realizar la transferencia.</div>


												</div>

												<style>
													.no-border th, .no-border td { border:none !important; padding:8px 10px; }
													.pasos-yape li { margin-bottom:10px; }
												</style>
												</div>
											</div>
											</div>

											<!-- Script para copiar número -->
											<script>
											function copyToClipboard(text) {
												var tempInput = document.createElement("input");
												tempInput.value = text;
												document.body.appendChild(tempInput);
												tempInput.select();
												document.execCommand("copy");
												document.body.removeChild(tempInput);
												alert("Número copiado: " + text);
											}
											</script>

										<!-- Modal Transferencia Bancaria -->
											<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel">
											<div class="modal-dialog" role="document">
												<div class="modal-content text-center" style="border-radius:15px; overflow:hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.3); position:relative;">

												<!-- Etiqueta BCP en la esquina -->
												<div style="position:absolute; top:10px; right:10px; background:#ffcc00; color:#000; font-weight:bold; padding:5px 10px; border-radius:5px; font-size:12px; z-index:10;">
													BCP
												</div>

												<!-- Header -->
												<div class="modal-header bg-primary text-white" style="border-bottom:none;">
													<h4 class="modal-title w-100" id="transferModalLabel" style="font-weight:bold;">Transferencia Bancaria</h4>
													<button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity:1;">
													<span aria-hidden="true">&times;</span>
													</button>
												</div>

												<!-- Body -->
												<div class="modal-body" style="padding:30px; background:#f7f9fc;">

													<!-- Instrucciones -->
													<h5 style="font-weight:bold; margin-bottom:15px; color:#333;">Pasos para pagar:</h5>
													<ul style="text-align:left; margin-left:20px; color:#555; font-size:14px;">
													<li>1. Realiza la transferencia a la cuenta indicada abajo.</li>
													<li>2. Verifica que el monto sea <strong>S/{{ number_format($total, 2) }}</strong></li>
													<li>3. Envía tu comprobante por WhatsApp.</li>
													</ul>

													<hr style="margin:20px 0; border-top:2px solid #ddd;">

													<!-- Información bancaria -->
													<div style="text-align:left; margin-bottom:20px;">
													<h5 style="font-weight:bold; color:#333;">Datos de la cuenta</h5>
													<p style="margin-bottom:8px;"><strong>Banco:</strong> Banco de Crédito del Perú (BCP)</p>
													<p style="margin-bottom:8px;"><strong>Número de cuenta:</strong> 123-4567890-1-23</p>
													<p style="margin-bottom:8px;"><strong>Código Interbancario:</strong> 00123456789012345678</p>
													</div>

													<!-- Botón enviar comprobante -->
													<a href="https://wa.me/51956664655?text=Hola,+te+env%C3%ADo+mi+comprobante+de+transferencia+por+S/{{ number_format($total,2) }}" 
													target="_blank" class="btn btn-success btn-lg btn-block" style="font-weight:bold; border-radius:10px; margin-bottom:10px;">
													Enviar comprobante por WhatsApp
													</a>

													<!-- Botón Cerrar largo -->
													<button type="button" class="btn btn-danger btn-lg btn-block" data-dismiss="modal" style="border-radius:10px;">
													Cerrar
													</button>

													<div style="margin-top:10px; font-size:12px; color:#777;">⚠️ Asegúrate de enviar el comprobante después de realizar la transferencia.</div>

												</div>

												</div>
											</div>
											</div>

										<!-- Script para copiar número -->
										<script>
										function copyToClipboard(text) {
											var tempInput = document.createElement("input");
											tempInput.value = text;
											document.body.appendChild(tempInput);
											tempInput.select();
											document.execCommand("copy");
											document.body.removeChild(tempInput);
											alert("Número copiado: " + text);
										}
										</script>

									@error('payment_method_id')
										<div class="form-group">
											<div class="text-danger small">
												{{ $message }}
											</div>
										</div>
									@enderror
								</div>

									
							</div>
						</div>
					</div>
				</div>
				<script>
					document.addEventListener('DOMContentLoaded', function() {
						const paymentSelect = document.getElementById('paymentSelect');

						function togglePaymentModal() {
							const selectedOption = paymentSelect.options[paymentSelect.selectedIndex];
							const methodName = selectedOption ? selectedOption.getAttribute('data-name') : null;

							// Ocultar ambos modales antes de mostrar uno
							$('#transferModal').modal('hide');
							$('#yapeModal').modal('hide');

							if (methodName === 'Transferencia bancaria') {
								$('#transferModal').modal('show');
							}

							if (methodName === 'Pago con Yape') {
								$('#yapeModal').modal('show');
							}
						}

						paymentSelect.addEventListener('change', togglePaymentModal);
					});
				</script>

							


				
			<div class="col-md-4">
			<div class="product-checkout-details">
				<div class="block">
					<h4 class="widget-title">Detalle de pedido</h4>
												
					@foreach($cart as $id => $item)
					<div class="media product-card">
						<a class="pull-left" href="{{ route('product', $id) }}">
							<img class="media-object" src="{{ asset('storage/'.$item['image']) }}" alt="Image" />
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="{{ route('product', $id) }}">{{ $item['name'] }}</a>
							</h4>
						</div>
					</div>
					@endforeach
					@php
						// Si no lo pasaste desde el controller, puedes asignarlo aquí rápido:
						$subtotal = $subtotal ?? ($total ?? 0);
					@endphp
					<style>
						

						/* Hace más grande el texto y lo centra */
						.product-card .media-body {
							text-align: center; /* Centra el contenido */
						}

						.product-card .media-heading a {
							font-size: 16px;     /* Tamaño más grande */
							font-weight: 500;    /* Más grueso */
							color: #000;
							text-decoration: none;
							display: block;
							line-height: 1.3;
							margin-top: 25px;
						}
						.product-card img {
							width: 130px !important; /* Ajusta el tamaño según lo grande que lo quieras */
							height: auto;
							object-fit: contain;
							margin-bottom: 8px;
							display: block;
						}
					</style>

					<!-- Resumen -->
					<div class="summary-total d-flex justify-content-between border-top pt-2">
						<span>Subtotal</span>
						<span id="subtotal">S/{{ number_format($subtotal, 2) }}</span>
					</div>

					<!-- Delivery (oculto inicialmente) -->
					<div class="summary-total d-flex justify-content-between border-top pt-2" id="delivery-row" style="display:none;">
						<span id="delivery-label">Total envío</span>
						<span id="delivery-cost">S/00.00</span>
					</div>
					<style>
						#delivery-label {
							width: 300px; /* lo separa más del precio */
							display: inline-block;
						}
					</style>

					<!-- Total -->
					<div class="summary-total d-flex justify-content-between border-top pt-2">
						<span><strong>Total</strong></span>
						<span><strong id="grand-total">S/{{ number_format($subtotal, 2) }}</strong></span>
					</div>
					<button type="submit" class="btn btn-main btn-block">Finalizar</button>
				</div> 
			</div></div>
			</form>
		</div>
	</div>



		</div>
	</div>
</div>

<!-- Script para que aumento el delivery en el total-->
<script>
	document.addEventListener('DOMContentLoaded', function() {
	const subtotal = Number(@json($subtotal));
    const deliverySelect = document.getElementById('deliverySelect');
    const districtSelect = document.getElementById('districtSelect');

    const deliveryRow = document.getElementById('delivery-row');
    const deliveryLabel = document.getElementById('delivery-label');
    const deliveryCostEl = document.getElementById('delivery-cost');
    const grandTotalEl = document.getElementById('grand-total');

    const deliveryPriceInput = document.getElementById('delivery_price');

    function updateAll() {

        if (!deliverySelect || !districtSelect) return;

        const opt = deliverySelect.options[deliverySelect.selectedIndex];
        const name = opt?.getAttribute('data-name') || '';

        let price = 0;
        const district = districtSelect.value;

        // 🟢 ZONA 1
        if (["Chiclayo","La Victoria","José Leonardo Ortiz"].includes(district)) {
            price = 5;
        }
        // 🟡 ZONA 2
        else if (["Pimentel","Monsefú","Reque","Pomalca","Tuman"].includes(district)) {
            price = 10;
        }
        // 🔴 ZONA 3
        else if ([
            "Eten","Eten Puerto","Santa Rosa","Chongoyape","Lagunas",
            "Cayaltí","Pátapo","Picsi","Pucalá","Zaña","Tucume","Nueva Arica", "Oyotún"
        ].includes(district)) {
            price = 16;
        }

        // 👉 RECOJO
        if (name.toUpperCase().includes('RECOJO')) {
            price = 0;
        }

        const total = subtotal + price;

        // Mostrar fila
        deliveryRow.style.display = deliverySelect.value !== "" ? 'flex' : 'none';

        deliveryLabel.textContent = name.toUpperCase().includes('RECOJO') 
            ? "Recojo en tienda" 
            : "Total envío";

        deliveryCostEl.textContent = "S/" + price.toFixed(2);
        grandTotalEl.textContent = "S/" + total.toFixed(2);

        // 🔥 GUARDAR PARA BACKEND
        if (deliveryPriceInput) {
            deliveryPriceInput.value = price;
        }

        console.log("Distrito:", district, "Precio:", price);
    }

    deliverySelect.addEventListener('change', updateAll);
    districtSelect.addEventListener('change', updateAll);

    updateAll();
});

  
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deliverySelect = document.getElementById('deliverySelect');
    const addressFields = document.getElementById('addressFields');
    const storePickup = document.getElementById('storePickup');

    const addressInput = document.querySelector('input[name="address"]');
    const districtSelect = document.querySelector('select[name="district"]');
    function toggleDeliveryFields() {
        const selectedOption = deliverySelect.options[deliverySelect.selectedIndex];
        const deliveryName = selectedOption?.getAttribute('data-name');

        if (deliveryName === 'Recojo en tienda') {
            addressFields.style.display = 'none';
            storePickup.style.display = 'block';

            // ❌ quitar required
            addressInput.removeAttribute('required');
            districtSelect.removeAttribute('required');

        } else if (deliveryName) {
            addressFields.style.display = 'block';
            storePickup.style.display = 'none';

            // ✅ activar required
            addressInput.setAttribute('required', 'required');
            districtSelect.setAttribute('required', 'required');

        } else {
            addressFields.style.display = 'none';
            storePickup.style.display = 'none';

            addressInput.removeAttribute('required');
            districtSelect.removeAttribute('required');
        }
    }

    deliverySelect.addEventListener('change', toggleDeliveryFields);
    toggleDeliveryFields();
});
</script>

@endsection
