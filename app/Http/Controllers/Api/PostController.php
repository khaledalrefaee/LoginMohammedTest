<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Posts;
use App\Models\Products;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;



class PostController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        // resource مشان شغل ال
        $products =PostResource::collection(Products::get());
        return $this->ApiResponse($products,'ok' , 200);
    }


        public function show($id)
        {
            $product = Products::find($id);
            if($product){
                // وانت راجع عميل عملية التحويل
                return $this->ApiResponse(new PostResource($product),'ok',200);
            }
            return $this->ApiResponse(null,'not found',404);
        }



    public  function store(Request $request){

        $validator=validator::make($request->all(),[
            'name'              =>  'required|max:200',
            'description'       =>  'required',
            'price'             =>  'required',
        ]);

        if( $validator->fails()){
            return $this->ApiResponse(null,$validator->errors(),400);
        }

        $filename ="";
            if($request->hasFile('image')) {
                $filename = $request->file('image')->store('Products', 'public');
                }
            else{
                $filename=Null;
            }
          $product= Products::create([
            'user_id' =>  Auth::id(),
            'name'          =>      $request->name,
            'description'   =>      $request->description,
            'price'         =>      $request->price,
            'image'         =>     $filename,
        ]);

        if($product){
            return $this->ApiResponse(new PostResource($product),'the post create',201);
        }
         return $this->ApiResponse(null,'the post not sasveing ',400);
   }





    public function updata(Request $request , $id){

        $validator=validator::make($request->all(),[
            'name'              =>  'required|max:200',
            'description'       =>  'required',
            'price'             =>  'required',
        ]);

        if( $validator->fails()){
            return $this->ApiResponse(null,$validator->errors(),400);
        }

        $product= Products::find($id);
        $destination=public_path("storage\\".$product->image);
        $filename ="";
        if($request->hasFile('image')){
            if(File::exists($destination)){
                File::delete($destination);
            }
            $filename = $request->file('image')->store('Products', 'public');
        }
        else{
            $filename = $request->image;
        }
        $product->name=$request->name;
        $product->description=$request->description;
        $product->price=$request->price;
        $product->image=$filename;
        $product->save();

        if(!$product){
            return $this->ApiResponse(null,' the post not found',404);
        }

        if($product){
            return $this->ApiResponse(new PostResource($product),'the post update',201);
        }
    }





    public function destroy($id){

        $post = Products::find($id);

        if(!$post){
            return $this->ApiResponse(null,' the post not found',404);
        }
        $post->delete($id);
        if($post){
            return $this->ApiResponse(null,'the post delete',200);
        }
    }


    public function search($name){
        return Products::where('name','like','%'.$name.'%')->get();
    }

}
