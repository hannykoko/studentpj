<?php

namespace App\Http\Controllers;

use Exception;
use App\Student;
use App\Student_skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StudentRequest;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @author Hanny Ko Ko
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        try {
            $option = request()->option;
            $input = request()->input;
        
            switch ($option) {
                case 0:
                    $students = Student::where ( "name", "LIKE", "%" . $input . "%" )->orWhere("email", "LIKE", "%" . $input . "%" )->orWhere("student_id", "LIKE", "%" . $input . "%" )->orWhere("career_path", "LIKE", "%" . $input . "%" )->paginate(10);
                    break;
                case 1:
                    $students = Student::where("name", "LIKE", "%" . $input . "%" )->paginate(10);
                    break;
                case 2:
                    $students = Student::where("student_id", "LIKE", "%" . $input . "%" )->paginate(10);
                    break;
                case 3:
                    $students = Student::where ( "name", "LIKE", "%" . $input . "%" )->paginate(10);
                    break;
                case 4:
                    $students = Student::where ( "email", "LIKE", "%" . $input . "%" )->paginate(10);
                    break;
            }

            // dd($students);
            return response()->json(['status' => 'OK' , 'data' => $students],200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json(['status' => 'NG' , 'message' => $e->getMessage()],200);
        }
        return response()->json(['status' => 'OK' , 'message' => 'Student search'],200);
    }

    

    /**
     * Store a newly created resource in storage.
     * @author Hanny Ko Ko
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function register(StudentRequest $request){  
    
        try {
            DB::beginTransaction();
            #student create
            if($request->hasFile('avatar')){#image upload
                $file = $request->file('avatar');
                $filenameWithExt = $request->file('avatar')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('avatar')->getClientOriginalExtension();
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                Storage::disk('local')->put( $fileNameToStore, file_get_contents($file));
            }
            $last_student = DB::table('students')->latest()->first();
           
            $new_student_id = $last_student ? ++$last_student->student_id:'10001';
            
            $new_student_data = $request->validated();
            $new_student_data['student_id'] = $new_student_id; 
            $new_student_data['avatar'] = $fileNameToStore?$fileNameToStore:null;
            
            // return $new_student_data;
            Student::insert($new_student_data);

            #student_skill create
            if($request->skills != null){
                $student_skills = [];
                // dd($student_skills);
                foreach($request->skills as $skill){
                    array_push($student_skills,[
                        'student_id' => $new_student_id,
                        'skill_id' =>  $skill,
                        'created_emp' => $request->created_emp,
                        'updated_emp' => $request->created_emp,
                    ]);
                }
                DB::table('student_skills')->insert($student_skills);
            }
            DB::commit();
            return response()->json(['status' => 'OK' , 'message' => 'Student created successfully.'],200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json(['status' => 'NG' , 'message' => $e->getMessage()],200);
        }
    }




    /**
     * Display the specified resource.
     * @author Hanny Ko Ko
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $idExists = Student::where('id',$id)->exists();
        if($idExists){
                try {
                    $student = DB::table('students')->where('id',$id)->first();
                    $student_skills_ids = DB::table('student_skills')->where('student_id',$student->student_id)->pluck('skill_id');
                    $skills = DB::table('skills')->whereIn('id',$student_skills_ids)->pluck('name');
                    $student->skills = $skills;
                    return response()->json(['status' => 'OK' , 'data' =>$student],200);
                    if($student){
                        return response()->json(['status' => 'OK' , 'data' => $student],200);
                    }else{
                        return response()->json(['status' => 'NG' , 'message' => 'No data found'],200);
                    }
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                    return response()->json(['status' => 'NG' , 'message' => $e->getMessage()],200);
                }
                
        }else{
            return response()->json(['status'=>'NG','message'=>"Id $id does not exist"],200);
        }
    }


    /**
     * Update the specified resource in storage.
     * @author Hanny Ko Ko
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, $id)
    {
        try {
            $student = DB::table('students')->whereNull('deleted_at')->find($id);
            if($student){
                DB::beginTransaction();
                if($request->hasFile('avatar')){#image upload
                    unlink(storage_path('app/'.$student->avatar)); #delete old avatar
                    $file = $request->file('avatar');
                    $filenameWithExt = $request->file('avatar')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('avatar')->getClientOriginalExtension();
                    $fileNameToStore = $filename.'_'.time().'.'.$extension;
                    Storage::disk('local')->put( $fileNameToStore, file_get_contents($file));
                }
                // $update_student_data = [
                //     'name' => $request->name,
                //     'father_name' => $request->father_name,
                //     'nrc_number' => $request->nrc_number,
                //     'phone_no' => $request->phone_no,
                //     'email' => $request->email,
                //     'gender' => $request->gender,
                //     'date_of_birth' => $request->date_of_birth,
                //     'avatar' => isset($fileNameToStore) ? $fileNameToStore : $student->avatar,
                //     'address' => $request->address,
                //     'career_path' => $request->career_path,
                //     'created_emp' => '11111',
                //     'updated_emp' => '11111'
                // ];

                $update_student_data = $request->validated();
                
                DB::table('students')->update($update_student_data);

                #student_skill create
                DB::table('student_skills')->where('student_id',$student->student_id)->delete(); #delete old skills
                if($request->skills != null){
                    $student_skills = [];
                    foreach($request->skills as $skill){
                        array_push($student_skills,[
                            'student_id' => $student->student_id,
                            'skill_id' =>  $skill,
                            'created_emp' => $request->created_emp,
                            'updated_emp' => $request->updated_emp,
                        ]);
                    }
                    DB::table('student_skills')->insert($student_skills);
                }
                DB::commit();
                return response()->json(['status' => 'OK' , 'message' => 'Updated student successfully.'],200);
            }else{
                return response()->json(['status' => 'NG' , 'message' => 'No data found'],200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json(['status' => 'NG' , 'message' => $e->getMessage()],200);
        }
    }




    /**
     * Remove the specified resource from storage.
     * @author Hanny Ko Ko
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function asdf($id)
    {

        // DB::table('students')->delete($id);
        // return response()->json(['status'=> 'OK', 'message'=>'deleted fguyfggcfsuccessfully'],200);
        $student = Student::find($id);
        if($student){
            // dd('found id');
        
            try{
               
                // unlink(storage_path('app/'.$student->avatar));
                Student_skill::where('student_id',$student->student_id)->delete();
                // DB::enableQueryLog();
                $student->delete();
                DB::raw("update `students` set `deleted_at` = ".date('Y-m-d H:i:s').", `students`.`updated_at` = ".date('Y-m-d H:i:s')." where `id` = `$id`");
                // dd(DB::getQueryLog());
                return response()->json(['status'=> 'OK', 'message'=>'deleted successfully'],200);
               
            }catch(Exception $e){
                Log::debug($e->getMessage());
                return response()->json(['status'=> 'NG', 'message'=>'Fail to delete'],200);
            }
        }else{
            return response()->json(['status'=>'NG', 'message'=>"Id $id does not exist"],200);
        }
    } 
}
