<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ArtikelModel;

class HomePage extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Selamat Datang di Website Bank Sampah Budi Luhur',
            'baseUrl' => base_url(),
        ];

        return view('HomePage/index',$data);
    }

    public function listPenghargaan()
    {
        $data = [
            'title'    => 'Penghargaan Yang Diperoleh Banksampah Budiluhur',
        ];

        return view('HomePage/listPenghargaan', $data);
    }

    public function listArtikel(string $kategori)
    {
        $data = [
            'title'    => 'Artikel | ' . $kategori,
            'kategori' => $kategori,
        ];

        return view('HomePage/listArtikel', $data);
    }

    public function detilArtikel(string $slug)
    {
        $model = new ArtikelModel();
        $post  = $model->select("title,thumbnail")->where('slug',$slug)->first();

        $data = [
            'title' => $post['title'],
            'slug'  => $slug,
            'thumbnail' => base_url().'/assets/images/thumbnail-berita/'.$post['thumbnail']
        ];

        return view('HomePage/detilArtikel', $data);
    }

    /**
     *  Statistik
     */
    public function getStatistik()
    {
        try {
            $db = \Config\Database::connect();

            $result = $db->query("SELECT 
            (SELECT COUNT(id) from users WHERE privilege = 'nasabah') AS total_nasabah,
            (SELECT COUNT(mitra.id) from mitra) AS total_mitra,
            (SELECT COUNT(penghargaan.id) from penghargaan) AS total_penghargaan");
            $result = $result->getResultArray();
            
            $dbresponse = [
                'status' => 200,
                'error'  => false,
                'data'   => $result
            ];

            return $this->respond($dbresponse,$dbresponse['status']);
        } 
        catch (\Throwable $th) {
            $dbresponse = [
                'status'   => 500,
                'error'    => true,
                'messages' => $th->getMessage()
            ];

            return $this->respond($dbresponse,$dbresponse['status']);
        }
        
    }
}
