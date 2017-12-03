https://github.com/anam-hossain/phpcart


1.  composer require anam/phpcart

2.  config/app.php

   'Anam\Phpcart\CartServiceProvider'

    $aliases array

   'Cart' => 'Anam\Phpcart\Facades\Cart'

   
   
3. Make controller method & route 



use Anam\Phpcart\Cart;


// additem


$cart = new Cart();


public function add_to_cart($id){
	$cart = new Cart();
	$product = product::find($id);
	
	$cart->add([
		'id'       => $id,
		'name'     => $product->name,
		'quantity' => 1,
		'price'    => $product->price,
		'image'    => $product->picture
	]);
	
}





//get total item list



$cart = new Cart();

$items = $cart->items();   //get all item in cart (quantity)

$total_item = $carts->totalQuantity() //get total number of quantity

$total_price = $carts->getTotal();  // get total price


return view('index',compact('items'));
	
	
	
	
in view just :

{{ $items }}








4. checkout.blade.php



In controller :


$cart = new Cart();
$checkout_items = $cart->items();
return view('checkout',compact('checkout_items'));




In View:


@foreach($checkout_items as $checkout_item)

	<tr>
	
		<td><img src="images/{{ $checkout_item->image }}" height="50px" /></td>
		
		<td>{{  $checkout_item->name  }}</td>

		<td>{{  $checkout_item->price  }}</td>

		<td><input type="number" value="{{  $checkout_item->quantity  }}"/></td>
		
		<td>{{  $checkout_item->quantity * $checkout_item->price  }}</td>
		
		<td>Update</td>
		
		<td>Delete</td>
		
	</tr>

@endforeach




5. DELETE : 



<a href='{{ url("clear_cart/$checkout_item->id") }}'>Delete</a>

public function clear($id){
	
	$cart = new Cart();
	$product = product::find($id);
	$id = $product->id;
	$cart->remove($id);		
	return redirect()->back();

}




6. UPDATE :

  
in view 


@foreach($checkout_items as $checkout_item)

	<tr>
	<form method="post" action="{{ url('update') }}">
		<td><img src="images/{{ $checkout_item->image }}" height="50px" /></td>
		
		<td>{{  $checkout_item->name  }}</td>

		<td>{{  $checkout_item->price  }}</td>

		<td><input type="number" value="{{  $checkout_item->quantity  }}" name="quantity"/></td>
		
		<td>{{  $checkout_item->quantity * $checkout_item->price  }}</td>
		
		<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
		<input type="hidden" name="id" value="{{ $checkout_item->id }}" />
		
		<td><input type="submit" value='Update'></td>
		
		<td><a href='{{ url("clear_cart/$checkout_item->id") }}'>Delete</a></td>
	</form>	
	</tr>
	
@endforeach



in controller :


public function update(){
	
	$cart = new Cart();
	$id = $_POST['id'];
	$quantity = $_POST['quantity'];
	$cart->updateQty($id, $quantity);
	return redirect()->back();
}