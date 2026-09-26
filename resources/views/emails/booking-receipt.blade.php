<x-mail::message>
# Booking payment receipt

Your payment has been confirmed. Present this receipt at the park or to your transport provider.

**Booking reference:** {{ $payment->transaction_id }}
**Passenger count:** {{ $payment->booking->number_of_people }}
**Travel date:** {{ $payment->booking->booking_date->format('d M Y') }}
**Amount paid:** TZS {{ number_format($payment->amount, 2) }}
**Payment status:** Confirmed

@if($payment->booking->booking_type === 'transport')
**Transport:** {{ ucfirst(str_replace('_', ' ', $payment->booking->transport_type)) }}
**Pick-up:** {{ $payment->booking->origin }}
**Destination:** {{ $payment->booking->destination }}
@else
**Booking:** {{ $payment->booking->service?->name ?? 'Tourism service' }}
@endif

Keep the booking reference available for inspection.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
