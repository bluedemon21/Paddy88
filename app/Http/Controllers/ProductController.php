<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
use Str;

session_start();
class   ProductController extends Controller
{
    public function index()
    {
        $this->AdminAuthCheck();
    	return view('admin.add_product');
    }

    public function indexx()
    {
        $this->SellerAuthCheck();
        return view('seller.seller_add_product');
    }

    public function all_product()
    {
        $this->AdminAuthCheck();
        $all_product_info=DB::table('tbl_products')
                        ->join('tbl_category','tbl_products.category_id','=','tbl_category.category_id')
                        ->join('tbl_manufacture','tbl_products.manufacture_id','=','tbl_manufacture.manufacture_id')
                        ->select('tbl_products.*','tbl_category.category_name','tbl_manufacture.manufacture_name')
                        ->get();

        // echo"<pre>";
        // print_r($all_product_info);
        // echo"</pre>";
        // exit();
        $manage_product=view('admin.all_product')
            ->with('all_product_info',$all_product_info);
        return view('admin_layout')
            ->with('admin.all_product',$manage_product);

    }

    public function seller_all_product()
    {
        $seller_id = Session::get('seller_id');
        $this->SellerAuthCheck();
        $all_product_info=DB::table('tbl_products')
                        ->join('tbl_category','tbl_products.category_id','=','tbl_category.category_id')
                        ->join('tbl_manufacture','tbl_products.manufacture_id','=','tbl_manufacture.manufacture_id')
                        ->select('tbl_products.*','tbl_category.category_name','tbl_manufacture.manufacture_name')
                        ->where('seller_id',$seller_id)
                        ->get();
        $manage_product=view('seller.seller_all_product')
            ->with('all_product_info',$all_product_info);
        return view('seller_layout')
            ->with('seller.seller_all_product',$manage_product);

    }


    public function save_product(Request $request)
    {
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['category_id'] = $request->category_id;
        $data['manufacture_id'] = $request->manufacture_id;
        $data['product_description'] = $request->product_description;
        $data['publication_status'] = $request->publication_status;
        $data['product_price'] = $request->product_price;
        $data['product_size'] = $request->product_size;
        $data['product_color'] = $request->product_color;
        $data['product_quantity'] = $request->product_quantity;


        // $image=$request->file('product_image');
        // if ($image)
        // {
        //     $image=$request->file('product_image');
        //     $image_name=str_random(20);


        $image=$request->file('product_image');
        if ($image)
        {
            $image=$request->file('product_image');
            $image_name=Str::random(20);

        $image = $request->file('product_image');

        if ($image)
        {
            $image = $request->file('product_image');
            $image_name=Str::random(20);
            $ext=strtolower($image->getClientOriginalExtension());
            $image_full_name=$image_name.'.'.$ext;
            $upload_path='image/';
            $image_url=$upload_path.$image_full_name;
            $success=$image->move($upload_path,$image_full_name);
            if($success)
                {
                    $data['product_image']=$image_url;
                    DB::table('tbl_products')->insert($data);
                    Session::put('message','Product added Sucessfully' . $image_url);
                    return Redirect::to('/add-product');
                }
        }

        	$data['product_image']='';
                DB::table('tbl_products')->insert($data);
            Session::put('message','Product added Sucessfully without image ');
            return Redirect::to('/add-product');
        }
    }

    public function seller_save_product(Request $request)
    {
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['category_id'] = $request->category_id;
        $data['manufacture_id'] = $request->manufacture_id;
        $data['product_description'] = $request->product_description;
        $data['publication_status'] = $request->publication_status;
        $data['product_price'] = $request->product_price;
        $data['product_size'] = $request->product_size;
        $data['product_color'] = $request->product_color;
        $data['product_quantity'] = $request->product_quantity;
        $data['seller_id'] = $request->seller_id;


        // $image=$request->file('product_image');
        // if ($image)
        // {
        //     $image=$request->file('product_image');
        //     $image_name=str_random(20);


        $image=$request->file('product_image');
        if ($image)
        {
            $image=$request->file('product_image');
            $image_name=Str::random(20);

        $image = $request->file('product_image');

        if ($image)
        {
            $image = $request->file('product_image');
            $image_name=Str::random(20);
            $ext=strtolower($image->getClientOriginalExtension());
            $image_full_name=$image_name.'.'.$ext;
            $upload_path='image/';
            $image_url=$upload_path.$image_full_name;
            $success=$image->move($upload_path,$image_full_name);
            if($success)
                {
                    $data['product_image']=$image_url;
                    DB::table('tbl_products')->insert($data);
                    Session::put('message','Product added Sucessfully' . $image_url);
                    return Redirect::to('/seller-add-product');
                }
        }

            $data['product_image']='';
                DB::table('tbl_products')->insert($data);
            Session::put('message','Product added Sucessfully without image ');
            return Redirect::to('/seller-add-product');
        }
    }


    public function unactive_product($product_id)
    {
        DB::table('tbl_products')
            ->where('product_id',$product_id)
            ->update(['publication_status' =>0]);
            Session::put('message','Product Unactive Sucessfully ');
            return Redirect::to('/all-product');
    }

     public function seller_unactive_product($product_id)
    {
        DB::table('tbl_products')
            ->where('product_id',$product_id)
            ->update(['publication_status' =>0]);
            Session::put('message','Product Unactive Sucessfully ');
            return Redirect::to('/seller-all-product');
    }

    public function active_product($product_id)
    {
        DB::table('tbl_products')
            ->where('product_id',$product_id)
            ->update(['publication_status' =>1]);
            Session::put('message','Product Active Sucessfully ');
            return Redirect::to('/all-product');
    }

    public function seller_active_product($product_id)
    {
        DB::table('tbl_products')
            ->where('product_id',$product_id)
            ->update(['publication_status' =>1]);
            Session::put('message','Product Active Sucessfully ');
            return Redirect::to('/seller-all-product');
    }

    public function delete_product($product_id)
    {

        DB::table('tbl_products')
            ->where('product_id',$product_id)
            ->delete();
            Session::get('message','Product Delete Successfully !');
            return Redirect::to('/all-product');
    }

    public function seller_delete_product($product_id)
    {

        DB::table('tbl_products')
            ->where('product_id',$product_id)
            ->delete();
            Session::get('message','Product Delete Successfully !');
            return Redirect::to('/seller-all-product');
    }

    public function edit_product($product_id)
    {
        $product_info=DB::table('tbl_products')
                        ->where('product_id',$product_id)
                        ->first();
            $product_info=view('admin.edit_product')
            ->with('product_info',$product_info);
        return view('admin_layout')
            ->with('admin.edit_product',$product_info);
        // return view('admin.edit_product');
    }

    public function seller_edit_product($product_id)
    {
        $product_info=DB::table('tbl_products')
                        ->where('product_id',$product_id)
                        ->first();

        $product_info=view('seller.seller_edit_product')->with('product_info',$product_info);

        return view('seller_layout')->with('seller.seller_edit_product',$product_info);
    }

     public function update_product(Request $request,$product_id)
    {
        $data=array();
        $data['product_name'] = $request->product_name;
        $data['category_id'] = $request->category_id;
        $data['manufacture_id'] = $request->manufacture_id;
        $data['product_description'] = $request->product_description;
        $data['publication_status'] = $request->publication_status;
        $data['product_price'] = $request->product_price;
        $data['product_size'] = $request->product_size;
        $data['product_color'] = $request->product_color;

        DB::table('tbl_products')
            ->where('product_id',$product_id)
            ->update($data);

            Session::get('message','product Update Successfully !');
            return Redirect::to('/all-product');
    }


    public function seller_update_product(Request $request,$product_id)
    {
        $data=array();
        $data['product_name'] = $request->product_name;
        $data['category_id'] = $request->category_id;
        $data['manufacture_id'] = $request->manufacture_id;
        $data['product_description'] = $request->product_description;
        $data['publication_status'] = $request->publication_status;
        $data['product_price'] = $request->product_price;
        $data['product_size'] = $request->product_size;
        $data['product_color'] = $request->product_color;

        DB::table('tbl_products')
            ->where('product_id',$product_id)
            ->update($data);

            Session::get('message','product Update Successfully !');
            return Redirect::to('/seller-all-product');
    }

    public function AdminAuthCheck()
    {
        $admin_id=Session::get('admin_id');
        if ($admin_id)
        {
            return;
        }
        else
        {
            return Redirect::to('/admin')->send();
        }
    }

    public function SellerAuthCheck()
    {
        $seller_id=Session::get('seller_id');
        if ($seller_id)
        {
            return;
        }
        else
        {
            return Redirect::to('/seller')->send();
        }
    }
}
