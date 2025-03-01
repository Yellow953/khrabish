<?php

namespace App\Http\Controllers;

use App\Models\Product;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = $this->get_notifications();

        return view('notifications.index', compact('notifications'));
    }

    public function fetch()
    {
        $notifications = $this->get_notifications();

        $notifications = collect($notifications)->map(function ($notification) {
            return [
                'message' => $notification,
                'timestamp' => now()->diffForHumans(),
            ];
        });

        return response()->json(['notifications' => $notifications]);
    }

    // Private
    private function get_notifications()
    {
        $products = Product::all();
        $notifications = [];

        $i = 0;
        foreach ($products as $product) {
            if ($product->quantity == 0) {
                $notifications[$i] = "Product " . $product->name . " quantity is 0. Please Import Urgently!";
                $i++;
            } else if ($product->quantity < 10) {
                $notifications[$i] = "Product " . $product->name . " quantity is below 10. Please Import Soon!";
                $i++;
            }
        }

        return $notifications;
    }
}
