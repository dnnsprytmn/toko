<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Show the contact form.
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Send contact email.
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Kirim email ke admin
            $adminEmail = config('mail.from.address', 'admin@example.com');
            
            Mail::raw(
                "Nama: " . $request->name . "\n" .
                "Email: " . $request->email . "\n\n" .
                "Pesan: \n" . $request->message,
                function ($message) use ($request, $adminEmail) {
                    $message->to($adminEmail)
                            ->subject('Pesan Kontak: ' . $request->subject)
                            ->replyTo($request->email, $request->name);
                }
            );

            return redirect()->route('contact')
                ->with('success', 'Pesan Anda berhasil dikirim! Kami akan merespon segera.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengirim pesan. Silakan coba lagi.')
                ->withInput();
        }
    }
}