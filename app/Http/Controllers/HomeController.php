<?php

namespace App\Http\Controllers;

use App\Present;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $present = Present::whereUserId(auth()->user()->id)->whereTanggal(date('Y-m-d'))->first();
        $url = 'https://kalenderindonesia.com/api/YZ35u6a7sFWN/libur/masehi/'.date('Y/m');
        
        // Mengambil data dari URL menggunakan file_get_contents()
        $kalender = file_get_contents($url);
        
        // Mendecode JSON menjadi array
        $kalender = json_decode($kalender, true);

        // Proses data kalender
        $libur = false;
        $holiday = null;
        if (isset($kalender['data']) && isset($kalender['data']['holiday']['data'])) {
            foreach ($kalender['data']['holiday']['data'] as $key => $value) {
                if ($value['date'] == date('Y-m-d')) {
                    $holiday = $value['name'];
                    $libur = true;
                    break;
                }
            }
        }

        return view('home', compact('present', 'libur', 'holiday'));
    }
}
