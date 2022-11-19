<?php

namespace App\Controllers;

class Notfound extends BaseController
{
    public function PageNotFound()
    {
        $data = [
            'title' => '404 page not found'
        ];

        return view('NotFound/index',$data);
    }
}
