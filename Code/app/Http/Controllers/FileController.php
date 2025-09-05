<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\Bakery;
use Illuminate\Support\Facades\File as FileBase;

class FileController extends Controller
{
    //
    public function index(){
        $file=File::paginate(7);
        $Date = [];
        foreach ($file as $files) {
            $createDate = $files->created_at;
            $formatDate = date('d-m-Y', strtotime($createDate));
            $yearInBE = date('Y', strtotime($createDate)) + 543;
            $Date[] = date('d/m/', strtotime($createDate)).$yearInBE;
        }
        return view('file', compact('file','Date'));
    }

    public function create(){
        $bakeries = Bakery::all(); 
        return view('UploadAIB', compact('bakeries'));
    }

    public function createFile(){
        return view('UploadFile');
    }

    public function uploadAIB(Request $request) {
        $folderName = $request->input('bname') ?: $request->input('selected_bakery');
        
        if($request->hasFile('TImg')) {
            if ($folderName) {
                // Create folder if it doesn't exist
                if (!Storage::exists($folderName)) {
                    Storage::makeDirectory($folderName);
                }
    
                foreach($request->file('TImg') as $image) {
                    $filename = time(). '_' .$image->getClientOriginalName();
                    Storage::putFileAs($folderName, $image, $filename);
                }
                return redirect('/UploadAIB')->with('status','Bakery Image Uploaded Successfully');
            } else {
                return redirect('/UploadAIB')->with('status','Please select or enter a bakery name');
            }
        }
    }
    // public function uploadAIB(Request $request) {
    //     if($request->hasFile('TImg')) {
    //         $folderName = $request->input('bname');
    //         Storage::makeDirectory($folderName);
    //         foreach($request->file('TImg') as $image) {
    //             $filename = time(). '_' .$image->getClientOriginalName() ;
    //             // $image->storeAs('upload', $filename);
    //             Storage::putFileAs($folderName, $image, $filename);
    //             // $image->move('storage/app/'.$folderName, $filename);
    //         }
    //         return redirect('/UploadAIB')->with('status','Bakery Image Uploaded Successfully');
    //     }
    // }

    public function uploadFile(Request $request) {
        $file = new File;
        $file->File_name = $request->input('fname');
        if($request->hasfile('file')){
            $uploadFile = $request->file('file');
            $originalName = $uploadFile->getClientOriginalName();
            $filename = $originalName;
            $uploadFile->move('uploads/files/', $filename);
            $file->File = $filename;
        }
        $file->File_Type = $request->input('fileT');
        $file->save();
        return redirect('/File')->with('status','เพิ่มไฟล์เอกสารสำเร็จ');
    }

    public function edit($file_id){
        $file = File::find($file_id);
        $fileTypes = ['รายการขาย', 'รายการซื้อ', 'ข้อมูลอื่น ๆ'];
        return view('EditFile', compact('file','fileTypes'));
    }

    public function update(Request $request, $file_id){
        $files = File::find($file_id);
        $files->File_name = $request->input('fname');
        if($request->hasfile('file')){
            $destination = 'uploads/files/'.$files->File;
            if(FileBase::exists($destination)){
                FileBase::delete($destination);
            }
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $filename = $originalName;
            $file->move('uploads/files/', $filename);
            $files->File = $filename;
        }
        $files->File_Type = $request->input('ftype');
        $files->update();
        return redirect('/File')->with('status','อัปเดตข้อมูลไฟล์สำเร็จ');
    }

    public function delete(Request $request) {
        $files = File::find($request->delete_file_id);
        $files->delete();
        return redirect('/File')->with('status','ลบข้อมูลไฟล์เอกสารสำเร็จ');
    }
}
