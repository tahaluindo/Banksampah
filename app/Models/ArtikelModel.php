<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class ArtikelModel extends Model
{
    protected $table         = 'artikel';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['id','title','slug','thumbnail','content','id_kategori','created_by','created_at'];

    public function getLastArtikel(): array
    {
        try {
            $lastArtikel = $this->db->table($this->table)->select('id')->limit(1)->orderBy('id','DESC')->get()->getResultArray();

            if (!empty($lastArtikel)) { 
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $lastArtikel[0],
                ];
            }
            else {   
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => 'not found',
                ];
            } 
        } 
        catch (Exception $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function addArtikel(array $data): array
    {
        try {
            $query = $this->db->table($this->table)->insert($data);

            if ($query == true) {
                return [
                    'status'   => 201,
                    'error'    => false,
                    'messages' => 'add artikel is success',
                ];
            } 
        } 
        catch (Exception $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function getOldThumbnail(string $id): string
    {
        $oldThumbnail = $this->db->table($this->table)->select('thumbnail')->where('id',$id)->get()->getResultArray()[0]['thumbnail'];
        
        if (!empty($oldThumbnail)) {    
            return $oldThumbnail;
        } 
    }

    public function editArtikel(array $data): array
    {
        try {
            $this->db->table($this->table)->where('id',$data['id'])->update($data);
            
            return [
                'status'   => 201,
                'error'    => false,
                'messages' => ($this->db->affectedRows()>0) ? 'edit artikel is success' : 'nothing updated'
            ];  
        } 
        catch (Exception $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function deleteArtikel(string $id): array
    {
        try {
            $this->db->table($this->table)->where('id', $id)->delete();
            $affectedRows = $this->db->affectedRows();

            return [
                'status'   => ($affectedRows>0) ? 201   : 404,
                'error'    => ($affectedRows>0) ? false : true,
                'messages' => ($affectedRows>0) ? "delete artikel with id $id is success" : "artikel with id $id is not found"
            ];  
        } 
        catch (Exception $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function getArtikel(array $get,bool $isAdmin): array
    {
        try {
            $orderby = (isset($get['orderby']) && $get['orderby']=='terbaru')? 'DESC': 'ASC';

            if (isset($get['id']) && !isset($get['kategori'])) {
                $berita = $this->db->table($this->table)
                ->select("artikel.id,artikel.title,artikel.slug,artikel.id_kategori,kategori_artikel.name AS kategori,artikel.published_at,artikel.created_at,artikel.thumbnail,artikel.content")
                ->join('kategori_artikel', 'kategori_artikel.id = artikel.id_kategori')
                ->where("artikel.id",$get['id'])->get()->getFirstRow();
                $berita = $this->modifImgPath($berita);
            } 
            else if (isset($get['slug']) && !isset($get['kategori'])) {
                $berita = $this->db->table($this->table)
                ->select("artikel.id,artikel.title,artikel.slug,artikel.id_kategori,kategori_artikel.name AS kategori,artikel.published_at,artikel.created_at,artikel.thumbnail,artikel.content")
                ->join('kategori_artikel', 'kategori_artikel.id = artikel.id_kategori')
                ->where("artikel.slug",$get['slug'])->get()->getFirstRow();
                $berita = $this->modifImgPath($berita);
            } 
            else if (isset($get['kategori']) && !isset($get['id'])) {
                $berita = $this->db->table($this->table)->select('artikel.id,artikel.title,artikel.slug,kategori_artikel.name AS kategori,artikel.published_at,artikel.created_at,artikel.thumbnail')
                ->join('kategori_artikel', 'kategori_artikel.id = artikel.id_kategori')
                ->where("kategori_artikel.name",$get['kategori']);

                if (!$isAdmin) {
                    $berita = $berita->where("artikel.published_at <=",(int)time());
                }

                $berita = $berita->orderBy('artikel.created_at',$orderby)->get()->getResultArray();
                $berita = $this->modifImgPath($berita);
            } 
            else {
                $berita = $this->db->table($this->table)->select('artikel.id,artikel.title,artikel.slug,kategori_artikel.name AS kategori,artikel.published_at,artikel.created_at,artikel.thumbnail')
                ->join('kategori_artikel', 'kategori_artikel.id = artikel.id_kategori');

                if (!$isAdmin) {
                    $berita = $berita->where("artikel.published_at <=",(int)time());
                }

                $berita = $berita->orderBy('artikel.created_at',$orderby);

                if (isset($geet['limit'])) {
                    $berita = $berita->limit($geet['limit']);
                }

                $berita = $berita->get()->getResultArray();
                $berita = $this->modifImgPath($berita);
            }

            if (empty($berita)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "artikel notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $berita
                ];
            }
        } 
        catch (Exception $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function getRelatedArtikel(string $slug): array
    {
        try {
            $allBerita  = [];
            $target     = $this->db->table($this->table)->select("id,id_kategori")->where("slug",$slug)->get()->getResultArray();
            $id         = $target[0]['id'];
            $idKategori = $target[0]['id_kategori'];

            $firstId   = $this->db->table($this->table)->select('id')->where("id_kategori",$idKategori)->limit(1)->orderBy('id','ASC')->get()->getResultArray()[0]['id'];
            $lastId    = $this->db->table($this->table)->select('id')->where("id_kategori",$idKategori)->limit(1)->orderBy('id','DESC')->get()->getResultArray()[0]['id'];

            // var_dump($idKategori);
            // var_dump($id);
            // var_dump($firstId);
            // var_dump($lastId);die;
            
            $limitPrev  = 2;
            $prevBerita = $this->db->query("SELECT artikel.id,artikel.title,artikel.slug,kategori_artikel.name AS kategori,artikel.created_at,artikel.thumbnail 
            FROM artikel 
            JOIN kategori_artikel ON(artikel.id_kategori = kategori_artikel.id) 
            WHERE artikel.id_kategori = '$idKategori' 
            AND artikel.id BETWEEN '$firstId' AND '$id' 
            ORDER BY artikel.id DESC LIMIT $limitPrev OFFSET 1")->getResultArray();
            
            $limitNext  = 2 + ($limitPrev-count($prevBerita));
            $nextBerita = $this->db->query("SELECT artikel.id,artikel.title,artikel.slug,kategori_artikel.name AS kategori,artikel.created_at,artikel.thumbnail 
            FROM artikel 
            JOIN kategori_artikel ON(artikel.id_kategori = kategori_artikel.id) 
            WHERE artikel.id_kategori = '$idKategori' 
            AND artikel.id BETWEEN '$id' AND '$lastId '
            ORDER BY artikel.id ASC LIMIT $limitNext OFFSET 1")->getResultArray();

            if (count($nextBerita) < 2 && count($prevBerita) == 2) {
                $limitNewNext  = 2-count($nextBerita);
                $newNextBerita = $this->db->query("SELECT artikel.id,artikel.title,artikel.slug,kategori_artikel.name AS kategori,artikel.created_at,artikel.thumbnail 
                FROM artikel 
                JOIN kategori_artikel ON(artikel.id_kategori = kategori_artikel.name)
                WHERE artikel.id_kategori = '$idKategori' 
                AND artikel.id BETWEEN '$firstId' AND '".$prevBerita[1]['id']."' ORDER BY artikel.id DESC LIMIT $limitNewNext OFFSET 1")->getResultArray();
                
                foreach ($newNextBerita as $key) {
                    $nextBerita[] = $key;
                }
            }

            foreach ($prevBerita as $key) {
                $allBerita[] = $key;
            }
            foreach ($nextBerita as $key) {
                $allBerita[] = $key;
            }

            if (count($prevBerita) + count($nextBerita) < 4) {
                $limitOtherKat = 4-(count($prevBerita) + count($nextBerita));
                $otherKat = $this->db->query("SELECT artikel.id,artikel.title,artikel.slug,kategori_artikel.name AS kategori,artikel.created_at,artikel.thumbnail 
                FROM artikel 
                JOIN kategori_artikel ON(artikel.id_kategori = kategori_artikel.id)
                WHERE artikel.id_kategori != '$idKategori' 
                ORDER BY artikel.id DESC LIMIT $limitOtherKat")->getResultArray();

                foreach ($otherKat as $key) {
                    $allBerita[] = $key;
                }
            }

            $allBerita = $this->modifImgPath($allBerita);
            
            if (empty($allBerita)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "related artikel notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $allBerita
                ];
            }
        } 
        catch (Exception $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function modifImgPath($data): array
    {
        $newData = [];

        if (is_array($data)==false) {
            foreach ($data as $key => $value) {
                if($key == "thumbnail"){
                    $newData[$key] = base_url()."/assets/images/thumbnail-berita/".$value;
                }
                else{
                    $newData[$key] = $value;
                }
            }
        } 
        else {

            foreach ($data as $array) {
                foreach ($array as $key => $value) {
                    if($key === 'thumbnail'){
                        $array[$key] = base_url()."/assets/images/thumbnail-berita/".$array['thumbnail'];
                        $newData[]   = $array; 
                    }
                    
                }
            }
        }

        return $newData;
    }
}
