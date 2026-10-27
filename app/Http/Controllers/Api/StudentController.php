<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
   public function index()
    {
        $students = Student::all();

        return response()->json([
            'status' => true,
            'data' => $students
        ]);
    }

  public function store(Request $request){
      $student = Student::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone
    ]);

     return response()->json([
            'status'=> true,
             'message' => 'Student created successfully',
              'data' => $student
     ],201);
  }



  public function update(Request $request, $id)
{
    
    $student = Student::findOrFail($id);

    $student->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Student updated successfully',
        'data' => $student
    ]);
}



public function destroy($id)
{
    $student = Student::findOrFail($id);

    $student->delete();

    return response()->json([
        'status' => true,
        'message' => 'Student deleted successfully'
    ]);
}




public function show($id)
{
    $student = Student::find($id);

    if (!$student) {
        return response()->json([
            'status' => false,
            'message' => 'Student not found'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data' => $student
    ]);
}

}
