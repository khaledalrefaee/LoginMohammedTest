<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Posts;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'image'         =>      $request->image,
        ]);

        if($product){
            return $this->ApiResponse(new PostResource($product),'the post create',201);
        }
         return $this->ApiResponse(null,'the post not sasveing ',400);
   }





    public function updata(Request $request , $id){

        $validator=validator::make($request->all(),[

            'title' =>'required|max:200',
            'body' => 'required',
        ]);

        if( $validator->fails()){
            return $this->ApiResponse(null,$validator->errors(),400);
        }

        $post = Posts::find($id);
        if(!$post){
            return $this->ApiResponse(null,' the post not found',404);
        }

        $post->update($request->all());

        if($post){
            return $this->ApiResponse(new PostResource($post),'the post update',201);
        }
    }



    public function destroy($id){

        $post = Posts::find($id);

        if(!$post){
            return $this->ApiResponse(null,' the post not found',404);
        }
        $post->delete($id);
        if($post){
            return $this->ApiResponse(null,'the post delete',200);
        }
    }


    public function search($title){
        return Posts::where('title','like','%'.$title.'%')->get();
    }

}
