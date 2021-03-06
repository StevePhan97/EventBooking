@extends('layouts.app')
@section('content')
<div class="container">
  <h2>{{$event->title}}</h2>
  <p>{{$time}}</p>
  <p>Location {{$event->location}}</p>
  <div class="card">
    <div class="card-header"><h5>Order Summary</h5></div>
    <div class="card-body">
    	<div class="row">
    		<div class="col"><strong>TICKET TYPE</strong></div>
    		<div class="col"><strong>PRICE</strong></div>
    		<div class="col"><strong>QUANTITY</strong></div>
    	</div>
       @if(!empty($code))
      <div class="row">
        <div class="col"><strong>DISCOUNTED!! {{$event->title}}</strong></div>
        <div class="col"><s>{{$event->price}} AUD</s> <large class="text-success">{{($event->price)*$type}} AUD</large></div>
        <div class="col">1</div>
      </div>
      @endif
      @if($quantity > 0)
    	<div class="row">
    		<div class="col">
          <!-- If the title is displayed as promoted event above, don't bother display title again -->
          @if(empty($code))
            {{$event->title}}
          @endif
        </div>
    		<div class="col"><strong>{{$event->price}}</strong></div>
    		<div class="col">{{$quantity}}</div>
    	</div>
      @endif
    	<div class="row">
    			<div class="col"></div>
    			<div class="col"><strong>Order total : </strong></div>
          @if(!empty($code))
            <div class = "col"><strong>{{$total = $event->price * $type + $event->price * $quantity}}</strong></div>
          @else
    			<div class="col"><strong>{{$total = $event->price * $quantity}}</strong></div>
          @endif
    	</div>
    </div>
  </div>
  <div class="card">
  	<div class="card-header"><h5>Registration Information</h5></div>
  	<div class="card-body">
  		<form class="PaymentForm" method="post" action="/events/{{$event->id}}/pay">
  			{{csrf_field()}}
  			<div class="form-horizontal">
  			<div class="form-group" style="margin-left:50px">
	  		         <label for="name">Name on Card</label>
	                <input id="name" class="form-control" type='text'>   
        	</div>
            <div class="form-group" style="margin-left:50px">
                  <label for="card">Card Number</label>
                <input autocomplete='off' class='form-control' id="card" type='text'>
            </div>
             <div class="form-group" style="margin-left:50px">
                <label>Billing Address</label>
                <input autocomplete='off' class='form-control' type='text'>
            </div>
            <div class="form-group row" style="margin-left:160px">
              <div class='form-group cvc required'>
                <label class='control-label'>CVC</label>
                <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'>
              </div>
              <div class='form-group expiration required'>
                <label class='control-label'>Expiration</label>
                <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text'>
              </div>
              <div class='form-group expiration required'>
                <label class='control-label'>Year</label>
                <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'>
              </div>
            </div>
			<div class="paymentWrap">
				<div class="btn-group paymentBtnGroup btn-group-justified" data-toggle="buttons">
		            <label class="btn paymentMethod active">
		            	<div class="method visa"></div>
		                <input type="radio" name="options" checked> 
		            </label>
		            <label class="btn paymentMethod">
		            	<div class="method master-card"></div>
		                <input type="radio" name="options"> 
		            </label>
		            <label class="btn paymentMethod">
	            		<div class="method amex"></div>
		                <input type="radio" name="options">
		            </label>
		             <label class="btn paymentMethod">
	             		<div class="method vishwa"></div>
		                <input type="radio" name="options"> 
		            </label>
		            <label class="btn paymentMethod">
	            		<div class="method ez-cash"></div>
		                <input type="radio" name="options"> 
		            </label>
		         
		        </div>        
			</div>
            <div class="form-group row" style="margin-left:150px">
              <div class='form-group'>
              <label class='control-label'></label>
               <input type="hidden" name="code" value="{{$code}}">
               <input type="hidden" name="total" value="{{$total}}"> 
              @if(!empty($code))
                <input type="hidden" name="quantity" value = "{{$quantity + 1}}">
              @else
                <input type = "hidden" name="quantity" value="{{$quantity}}">
              @endif
               <button class='form-control btn btn-primary' type='submit'> Pay Now →</button>
       
              </div>
            </div> 
        </div>
        </form>
  	</div>
</div>
@endsection

