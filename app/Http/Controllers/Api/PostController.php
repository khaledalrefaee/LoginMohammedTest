<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class PostController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        // resource مشان شغل ال 
        $posts =PostResource::collection(Posts::get());
        return $this->ApiResponse($posts,'ok' , 200);
    }


        public function show($id)
        {
            $post = Posts::find($id);
            if($post){
                // وانت راجع عميل عملية التحويل 
                return $this->ApiResponse(new PostResource($post),'ok',200);
            }
            return $this->ApiResponse(null,'not found',404);
        }



    public  function store(Request $request){

        $validator=validator::make($request->all(),[

            'title' =>'required|max:200',
            'body' => 'required',
        ]);

        if( $validator->fails()){
            return $this->ApiResponse(null,$validator->errors(),400);
        }

            $post= Posts::create([
            'title' => $request->title,
            'body'  =>$request->body
        ]);
        if($post){
            return $this->ApiResponse(new PostResource($post),'the post create',201);
        }
        // return $this->ApiResponse(null,'the post not sasveing ',400);
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
