@component('mail::message')
# {{ __('Thank you for your purchase!') }}

{{ __('Hi :customer_name, we\'re getting your order ready to be shipped. We will notify you when it has been sent.', ['customer_name' => $order->firstName . ' ' . $order->lastName]) }}

@component('mail::table')
    | {{ __('Order summary') }} |   |   |
    | :------------------------- | :------------: | -----------: |
    @foreach(json_decode($order->items, true) as $item)
        | **{{ $item['product']['title'] }} x {{ $item['quantity'] }}** |  | **${{ number_format($item['product']['price'] * $item['quantity'], 2) }}** |
    @endforeach
    |  | {{ __('Subtotal') }}  | **${{ number_format($order->subtotal, 2) }}** |
    |  | {{ __('Shipping') }}  | **${{ number_format($order->shipping_fee, 2) }}** |
    |  | {{ __('Taxes') }}     | **${{ number_format($order->taxes, 2) }}** |
    |  | {{ __('Total') }}     | **${{ number_format($order->subtotal + $order->shipping_fee + $order->taxes, 2) }}** |
@endcomponent

@component('mail::table')
    | {{ __('Customer information') }} |   |   |
    | :------------------------------- | :----------: | :---------- |
    | **{{ __('Shipping address') }}**<br>{{ $order->address }}<br>{{ $order->city }} {{ $order->state }} {{ $order->postal }}<br>{{ $order->country }} |  | **{{ __('Billing address') }}**<br>{{ $order->address }}<br>{{ $order->city }} {{ $order->state }} {{ $order->postal }}<br>{{ $order->country }} |
    | **{{ __('Contact') }}**<br>{{ $order->phone }} |  | **{{ __('Payment method') }}**<br>{{ __('Credit Card') }} |
@endcomponent

{{ __('Thanks,') }}<br>
{{ config('app.name') }}
@endcomponent