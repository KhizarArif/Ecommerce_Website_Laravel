<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // dd($request);
        // You can retrieve the event by verifying the webhook signature to confirm that the event is from Stripe
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('services.stripe.webhook_secret')
            );
            dd($event);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            dd("Signature verification failed: " . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;

            // Find the order using the session id
            $order = Order::where('stripe_session_id', $session->id)->first();

            if ($order) {
                // Update order status to completed
                $order->status = 'completed';
                $order->save();
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
