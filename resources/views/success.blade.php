@extends('template')

@section('content')
<style>
  .full-center-viewport {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 80vh; 
    padding: 40px 15px;
  }

  .success-card {
    background: #ffffff;
    border-radius: 25px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    border: none;
    width: 100%;
    max-width: 650px; /* Aumentado ligeramente para acomodar texto grande */
    padding: 50px;
    text-align: center;
  }

  /* Botones más grandes y robustos */
  .btn-custom {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 65px; /* Altura aumentada */
    width: 100%; 
    border-radius: 15px;
    font-size: 1.25rem; /* Texto de botón más grande */
    font-weight: 700;
    transition: all 0.3s ease;
    text-decoration: none;
    gap: 12px;
  }

  @media (min-width: 576px) {
    .btn-custom { width: 260px; } /* Ancho de botón aumentado */
  }

  .btn-black {
    background-color: #000000;
    color: #ffffff !important;
  }

  .btn-whatsapp {
    background-color: #28a745;
    color: #ffffff !important;
  }

  /* Efecto hover */
  .btn-custom:hover {
    transform: scale(1.03);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  }
</style>

<div class="full-center-viewport">
  <div class="success-card">
    
    <div class="mb-4">
      <i class="tf-ion-android-checkmark-circle" style="font-size: 100px; color: #28a745;"></i>
    </div>

    <h1 style="font-weight: 900; color: #1a1a1a; margin-bottom: 20px; font-size: 2.8rem; letter-spacing: -1.5px;">
      ¡Pedido Confirmado!
    </h1>

    <p style="color: #444; font-size: 2rem; line-height: 1.6; margin-bottom: 35px; font-weight: 500;">
      Tu pedido ha sido registrado con éxito.<br>
      Para procesar el envío, por favor valida tu pago.
    </p>

    <div style="background: #fff4e5; border-radius: 15px; padding: 20px; margin-bottom: 40px; border-left: 8px solid #ffa500;">
      <p style="margin: 0; color: #856404; font-size: 1.7rem; font-weight: 600; line-height: 1.5;">
        Envía la captura de tu pago vía WhatsApp.<br>
        Cambiaremos el estado de 
        <span style="color: #d9534f; text-decoration: underline;">Pendiente</span> 
        <span style="color: #6f42c1; text-decoration: underline;">a</span> 
        <span style="color: #28a745; text-decoration: underline;">Completado</span>.
      </p>
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
    <a href="{{ route('orders') }}" target="_blank" class="btn-custom btn-black" style="font-size: 1.5rem; font-weight: 600;">
      <i class="tf-ion-ios-paper-outline" style="font-size: 2rem;"></i>
      Ver Pedidos
    </a>
    
      <a href="https://wa.me/51956664655?text=Hola,+te+env%C3%ADo+la+captura+de+mi+pago" 
        target="_blank" 
        class="btn-custom btn-whatsapp"
        style="font-size: 1.8rem; font-weight: 600;">
        <i class="tf-ion-social-whatsapp" style="font-size: 2rem;"></i>
        WhatsApp
    </a>
    </div>

    <div style="margin-top: 40px;">
    <a href="{{ route('index') }}" style="color: #777; text-decoration: none; font-size: 1.1rem; font-weight: 600;">
      <i class="tf-ion-ios-arrow-back"></i> Volver a la tienda
    </a>
    </div>

  </div>
</div>
@endsection