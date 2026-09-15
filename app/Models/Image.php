<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'name',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function saveImg($product_id)
    {
        $images = $_FILES['img']['tmp_name'];
        foreach ($images as $key => $tmp_name) {
            $name = $_FILES['img']['name'][$key];
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            $new_img_name = md5(uniqid()).'.'.$extension;
            move_uploaded_file($tmp_name, storage_path("app/public/images/products/$new_img_name"));

            Image::create([
                'name' => $new_img_name,
                'product_id' => $product_id,
            ]);
        }
    }

    public static function deleteImg($product_id)
    {
        $images = Image::where('product_id', $product_id)->pluck('name');
        foreach ($images as $img_name) {
            unlink(storage_path('app/public/images/products/'.$img_name));
        }
        Image::where('product_id', $product_id)->delete();
    }
}
