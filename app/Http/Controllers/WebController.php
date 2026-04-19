<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Client;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\PaymentMethod;
use App\Models\Delivery;
use App\Models\Book;
use Illuminate\Support\Str;


class WebController extends Controller
{
    private const PICKUP_CITY = 'Chiclayo';
    private const PICKUP_DISTRICT = 'Chiclayo';
    private const PICKUP_ADDRESS = 'RECOJO EN TIENDA - Av. Luis Gonzales 1420 - Chiclayo';
    private const PICKUP_REFERENCE = 'Interior 7 - Galeria El Ferretero';

    public function index(){
        $products = Product::active()->orderBy('id','desc')->limit(4)->get();

        $favorites = Product::active()->withCount('details')->orderBy('details_count', 'desc')->having('details_count', '>', 0)->limit(4)->get();

        return view('index', compact('products', 'favorites'));
    }

    public function shop(Request $request){
        $categories = Category::active()->orderBy('name', 'asc')->get();
        $brands = Brand::active()->orderBy('name', 'asc')->get();
        $products = Product::active()->when($request->search, function($query, $search){
            return $query->where('name', 'LIKE', '%'.$search.'%');
        })->when($request->category_id, function($query, $category_id){
            return $query->where('category_id', $category_id);
        })->when($request->brand_id, function($query, $brand_id){
            return $query->where('brand_id', $brand_id);
        })->when($request->min_price, function($query, $min_price){
            return $query->where('price', '>=', $min_price);
        })->when($request->max_price, function($query, $max_price){
            return $query->where('price', '<=', $max_price);
        })->orderBy('name', 'asc')->paginate(12);

        return view('shop', compact('categories', 'products', 'brands'));
    }

    public function product(Product $product){
        return view('product', compact('product'));
    }

    public function cart(Request $request){
        $cart = session('cart', []);
        $total = array_reduce($cart, function($sum, $item){
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);
        $delivery = Delivery::find($request->delivery_id);
        return view('cart', compact('cart', 'total'));

        
    }

    public function about(){
        return view('about');
    }

    public function contact(){
        return view('contact');
    }

    public function dashboard(){
        $clients = Client::count();
        $products = Product::active()->count();
        $sales = Sale::active()->sum('total');
        return view('admin.index', compact('clients', 'products', 'sales'));
    }

    public function checkout(){
        $cart = session('cart', []);

        if(count($cart) == 0){
            return redirect()->route('index');
        }

        $total = array_reduce($cart, function($sum, $item){
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);
        $payment_methods = PaymentMethod::all();
        $deliveries = Delivery::all();
        return view('checkout', compact('cart', 'total', 'payment_methods', 'deliveries'));
    }

    public function finalize(Request $request){
        // Validar los campos principales
     
        $request->validate([
            'voucher' => 'required',
            'payment_method_id' => 'required',
            'delivery_id' => 'required|exists:deliveries,id'
        ]);

        $delivery = Delivery::findOrFail($request->delivery_id);
        $isStorePickup = $this->isStorePickup($delivery);

        if ($isStorePickup) {
            $deliveryData = [
                'city' => self::PICKUP_CITY,
                'district' => self::PICKUP_DISTRICT,
                'address' => self::PICKUP_ADDRESS,
                'reference' => self::PICKUP_REFERENCE,
                'delivery_price' => 0,
            ];
        } else {
            $request->validate([
                'city' => 'required',
                'district' => 'required',
                'address' => 'required',
                'reference' => 'nullable|string|max:255',
            ]);

            $deliveryData = [
                'city' => $request->city,
                'district' => $request->district,
                'address' => $request->address,
                'reference' => $this->optionalCheckoutText($request->reference),
                'delivery_price' => $this->resolveDeliveryPrice($delivery, $request->district),
            ];
        }
    
        // Validar datos adicionales si es una factura
        if ($request->voucher == 'Factura') {
            $request->validate([
                'bussiness_name' => 'required',
                'bussiness_document' => 'required'
            ]);
        }
    
        // Verificar que el carrito no esté vacío
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío.');
        }
        //cart.index
    
        $client_id = auth()->user()->id;
        $total = array_reduce($cart, function($sum, $item){
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);
    
        // Obtener el número de venta
        $number = DB::table('numbers')->where('voucher', $request->voucher)->first();
    
        if (!$number) {
            return redirect()->route('cart')->with('error', 'No se encontró el número de venta.');
        }
    //cart.index
        $sale_number = $number->serie.'-'.str_pad($number->number, 8, "0", STR_PAD_LEFT);

        // Crear la venta
        $sale = Sale::create([
            'bussiness_name' => $request->bussiness_name,
            'bussiness_document' => $request->bussiness_document,
            'phone' => $request->phone,
            'direction' => $request->direction,
            'voucher' => $request->voucher,
            'city' => $deliveryData['city'],
            'district' => $deliveryData['district'],
            'address' => $deliveryData['address'],
            'reference' => $deliveryData['reference'],
            'number' => $sale_number,
            'client_id' => $client_id,
            'delivery_price' => $deliveryData['delivery_price'],
            'total' => $total + $deliveryData['delivery_price'],
            'payment_method_id' => $request->payment_method_id,
            'delivery_id' => $request->delivery_id,
            'date' => now(),
            'status' => 'Pendiente'
        ]);
    
        // Comprobar stock de productos
        foreach($cart as $id => $item){
            $product = Product::find($id);
            
            if ($product->stock < $item['quantity']) {
                return redirect()->route('cart')->with('error', 'No hay suficiente stock para el producto: ' . $product->name);
            }
            //cart.index
    
            // Crear el detalle de la venta
            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $id,
                'price' => $item['price'],
                'quantity' => $item['quantity']
            ]);
    
            // Actualizar el stock del producto
            $product->update([
                'stock' => $product->stock - $item['quantity']
            ]);
        }
    
        // Actualizar el número de venta
        DB::table('numbers')->where('voucher', $request->voucher)->update([
            'number' => $number->number + 1
        ]);
    
        // Vaciar el carrito
        session()->forget('cart');
    
        // Redirigir al éxito con la URL del PDF
        return redirect()->route('success')->with('url', route('sales.pdf', $sale)); 
    }

    private function isStorePickup(Delivery $delivery): bool
    {
        return Str::contains(Str::lower($delivery->name), 'recojo');
    }

    private function resolveDeliveryPrice(Delivery $delivery, ?string $district): float
    {
        if ($this->isStorePickup($delivery)) {
            return 0;
        }

        $district = Str::lower(Str::ascii(trim((string) $district)));

        if (in_array($district, ['chiclayo', 'la victoria', 'jose leonardo ortiz'], true)) {
            return 5;
        }

        if (in_array($district, ['pimentel', 'monsefu', 'reque', 'pomalca', 'tuman'], true)) {
            return 10;
        }

        if (in_array($district, [
            'eten',
            'eten puerto',
            'santa rosa',
            'chongoyape',
            'lagunas',
            'cayalti',
            'patapo',
            'picsi',
            'pucala',
            'zana',
            'tucume',
            'nueva arica',
            'oyotun',
        ], true)) {
            return 16;
        }

        return 0;
    }

    private function optionalCheckoutText($value): string
    {
        $value = trim((string) $value);

        return $value === '' ? '-' : $value;
    }
    


    

    public function success(){
        if(!session()->has('url')){
            return redirect()->route('index');
        }
        return view('success');
    }

    public function orders(){
        $client_id = auth()->user()->id;
        $sales = Sale::active()->where('client_id', $client_id)->orderBy('date', 'desc')->paginate(10);

        return view('orders', compact('sales'));
    }

    public function profile(){
        return view('profile');
    }

    public function update(Request $request){
        $user = auth()->user();
        
        $request->validate([
            'address' => 'required',
            'reference' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:clients,email,'.$user->id
        ]);

        auth()->user()->update($request->all());

        return redirect()->route('profile');
    }

    public function book(){
        return view('book');
    }

    public function book_store(Request $request){

        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'document' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'direction' => 'required',
            'district' => 'required',
            'address' => 'required',
            'reference' => 'required',
            'email' => 'required|email',
            'product_type' => 'required',
            'description' => 'required',
            'amount' => 'required|numeric',
            'order_number' => 'required',
            'claim' => 'required',
            'client_request' => 'required',
        ]);

        Book::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'document' => $request->document,
            'direction' => $request->direction,
            'city' => $request->city,
            'address' => $request->address,
           // 'reference' => $request->reference,
            'phone' => $request->phone,
            'email' => $request->email,
            'product_type' => $request->product_type,
            'description' => $request->description,
            'amount' => $request->amount,
            'order_number' => $request->order_number,
            'claim' => $request->claim,
            'client_request' => $request->client_request,
            'date' => now()
        ]);

        return redirect()->route('book');
    }
    
}
