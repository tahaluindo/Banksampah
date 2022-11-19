<?php

namespace App\Controllers;

class BacaBerita extends BaseController
{
    public function BacaBerita()
    {
        $data = [
            'title' => 'Berita Acara'
        ];

        return view('HomePage/bacaBerita',$data);
    }
}
