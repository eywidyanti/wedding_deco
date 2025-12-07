<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cart;
use App\Models\Dekor;
use App\Models\Galeri;
use App\Models\Paket;
use App\Models\PaketDekor;
use App\Models\Position;
use PDF;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function welcome()
    {
        $pakets = Paket::all();
        $dekors = Dekor::all();
        $cartItems = Cart::all();
        $galeris = Galeri::all();

        $paketDekors = PaketDekor::with('dekor')->get();
        $dekorsFromPaketDekor = $paketDekors->pluck('dekor');

        return view('welcome', compact('dekors', 'pakets', 'cartItems', 'galeris', 'dekorsFromPaketDekor'));
    }

    // public function DragnDrop()
    // {
    //     $user = Auth::user();
    //     $user_id = $user->id;

    //     $position = Position::where('user_id', $user_id)->get();

    //     if ($position->isEmpty()) {
    //         $paketDekors = PaketDekor::all();

    //         $dekorIds = $paketDekors->pluck('dekor_id')->unique();

    //         $dekors = Dekor::whereIn('id', $dekorIds)->get();
    //         return view('klien.DragnDrop', compact('dekors', 'position'));
    //     } else {
    //         $maxId = $position->sortByDesc('id')->first();
    //         $pecah = explode('},{', $maxId->x);

    //         $paketDekors = PaketDekor::all();

    //         $dekorIds = $paketDekors->pluck('dekor_id')->unique();

    //         $dekors = Dekor::whereIn('id', $dekorIds)->get();

    //         return view('klien.DragnDrop', compact('dekors', 'pecah', 'position'));
    //     }
    // }
    
    public function DragnDrop()
    {
        $user = Auth::user();
        $user_id = $user->id;

        $dekors = Dekor::whereDoesntHave('paketDekors')->get();

        $position = Position::where('user_id', $user_id)->get();

        if ($position->isEmpty()) {
            return view('klien.DragnDrop', compact('dekors', 'position'));
        } else {
            $maxId = $position->sortByDesc('id')->first();
            $pecah = explode('},{', $maxId->x);

            return view('klien.DragnDrop', compact('dekors', 'pecah', 'position'));
        }
    }


    public function simpan(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $position = new Position;
        $position->x = $request->x;
        $position->user_id = $user_id;
        $position->save();

        // return response()->json(['status' => 'success']);
        return redirect()->route('DragnDrop');
    }

    public function cart_drag_n_drop(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;


        $PaketDiCart = Cart::where('user_id', $user_id)->whereNotNull('paket_id')->exists();

        if ($PaketDiCart) {
            session()->flash('error', 'Maaf, dekor tidak bisa ditambahkan karena ada paket di keranjang.');
            return redirect()->route('home');
        } else {
            $data  = explode(',', $request->x);
            foreach ($data as $val) {
                $posisi = explode('#', $val);
                if (floatval($posisi[2]) != 0) {
                    $ids = explode('-', $posisi[0]);
                    $id = $ids[1];

                    $dekor = Dekor::where('id', $id)->first();
                    $cartItem = Cart::where('dekor_id', $dekor->id)->where('user_id', $user_id)->first();

                    if (!$cartItem) {
                        $cart = new Cart;
                        $cart->user_id = $user_id;
                        $cart->dekor_id = $dekor->id;
                        $cart->slug = Str::random(20);
                        $cart->quantity = 1;

                        $cart->save();

                        $position = new Position;
                        $position->x = $request->x;
                        $position->user_id = $user_id;
                        $position->save();

                        session()->flash('success', 'Visualisasi dekor impian anda berhasil dimasukkan ke keranjang');
                    } else {
                        session()->flash('error', 'Maaf, item sudah ada di keranjang.');
                    }
                }
            }


            return redirect()->route('home');
        }
    }
    public function detailwc($slug)
    {
        $paket = Paket::where('slug', $slug)->firstOrFail();
        $dekors = $paket->dekors;
        $galeris = Galeri::all();
        $cartItems = Cart::all();
        return view('detailwc', compact('dekors', 'cartItems', 'galeris'));
    }

    public function cari(Request $request)
    {
        $tanggal = $request->input('date');

        $paketDekors = PaketDekor::whereHas('dekor', function ($query) use ($tanggal) {
            $query->whereDoesntHave('bookingDetail', function ($query) use ($tanggal) {
                $query->whereHas('booking', function ($query) use ($tanggal) {
                    $query->where('tanggal_mulai_penggunaan', $tanggal)
                        ->where(function ($query) {
                            $query->where('status', 'Pending')
                                ->orWhere('status', 'Kirim');
                        });
                });
            });
        })->whereHas('paket', function ($query) use ($tanggal) {
            $query->whereDoesntHave('bookingDetail', function ($query) use ($tanggal) {
                $query->whereHas('booking', function ($query) use ($tanggal) {
                    $query->where('tanggal_mulai_penggunaan', $tanggal)
                        ->where(function ($query) {
                            $query->where('status', 'Pending')
                                ->orWhere('status', 'Kirim');
                        });
                });
            });
        })->with('paket', 'dekor')->get();

        $paketIds = $paketDekors->pluck('paket_id')->unique();

        $pakets = Paket::whereIn('id', $paketIds)
            ->whereDoesntHave('paketDekors', function ($query) use ($tanggal) {
                $query->whereHas('dekor.bookingDetail.booking', function ($query) use ($tanggal) {
                    $query->where('tanggal_mulai_penggunaan', $tanggal)
                        ->where(function ($query) {
                            $query->where('status', 'Pending')
                                ->orWhere('status', 'Kirim');
                        });
                });
            })
            ->get();

        $dekorsFromPaketDekor = $paketDekors->pluck('dekor')->filter()->unique('id');

        $user = Auth::user();
        $galeris = Galeri::all();
        $cartItems = Cart::where('user_id', $user->id)->get();

        session()->flash('success', 'Berikut data dekor dan paket yang tersedia pada tanggal ' . $tanggal);
        return view('klien.tampilan', compact('dekorsFromPaketDekor', 'pakets', 'galeris', 'cartItems', 'tanggal'));
    }

    public function kwitansi(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $booking = Booking::find($bookingId);

        return view('klien.payment.kwitansi', compact('booking'));
    }

    public function cetak(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $booking = Booking::find($bookingId);

        $pdf = PDF::loadView('klien.payment.cetak', compact('booking'));

        return $pdf->download('kwitansi.pdf');
    }
}
