<?php

namespace App\Http\Controllers\Api;
 use App\Models\post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts'] = post::all();
        return response()->json([

          'status'=>true,
          'message'=>'all post data',
          'data'=>$data,

        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateUser =Validator::make(
        $request->all(),[
            'title'=>'required',
            'description'=>'required|unique:users,email',
            'image' =>'required|mimes:png,jpg,jpeg,gif',
        ]
      );

      if($validateUser->fails()){
        return response()->json([
             'status' => false,
             'message'  =>'valid users error',
             'errors'  =>$validateUser->errors()->all()
        ],401);
      }
          
         $img= $request->image;
         $text=$img->getClientOriginalExtension();
         $imageName = time().'.'.$text;
         $img->move(public_path().'/uploads',$imageName);

          $post =post::create([
             'title'  =>$request->title,
             'description'  =>$request->description,
             'image'  =>$imageName,

          ]);

          return response()->json([
             'status' => true,
             'message'  =>'user created successfully',
             'post'  => $post ,
        ],401);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['posts'] = post::select(
            'id',
            'title',
            'description',
            'image'
        )->where(['id'=>$id])->get();
       return response()->json([
             'status' => true,
             'message'  =>'your single  data',
             'post'  => $data ,
        ],401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    $validateUser =Validator::make(
        $request->all(),[
            'title'=>'required',
            'description'=>'required|unique:users,email',
            'image' =>'required|mimes:png,jpg,jpeg,gif',
        ]
      );

      if($validateUser->fails()){
        return response()->json([
             'status' => false,
             'message'  =>'valid users error',
             'errors'  =>$validateUser->errors()->all()
        ],401);
      }

         $postimage =post::select('id','image')->where(['id'=>$id])->get();
         if($request->image !=''){
            $path =public_path().'/uploads';
            if($postimage->image !='' && $postimage->image!=null){
                $old_file =$path. $postimage->image;
                if(file_exists($old_file)){
                    unlink($old_file);

                }
            }
         }
          
         $img= $request->image;
         $text=$img->getClientOriginalExtension();
         $imageName = time().'.'.$text;
         $img->move(public_path().'/uploads',$imageName);

          $post =post::where(['id'=>$id])->update([
             'title'  =>$request->title,
             'description'  =>$request->description,
             'image'  =>$imageName,

          ]);

          return response()->json([
             'status' => true,
             'message'  =>'post update successfully',
             'post'  => $post ,
        ],401);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagePath =post::select('image')->where('id',$id)->get();
        $filePath =public_path().'/uploads'.$imagePath[0]['iamge'];
        unlink($imagePath);
        $post = post::where('id',$id)->delete();

        return response()->json([
           'status' => true,
           'message'=>'your post has been remove',
           'post' =>$post,

        ],200);
    }
}
