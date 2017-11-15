<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

class TransactionController extends Controller
{



public function processFileName($filename)
{
  // Remove ()
    $filename = str_replace("(","",$filename);
    $filename = str_replace(")","",$filename);
   #Remove whitespace
   $filename = str_replace(" ","_",$filename);
   return $filename;
}


public function processFile($exportFileid,$pathToInputFile,$pathToExportFolder,$outputFileName)
{

  // Add Export URL

  $url = url('/');

  $appPath = app_path();
  $ch = "python3"." " .app_path().'/python/process.py ' . $exportFileid . ' ' . $pathToInputFile . ' ' . $pathToExportFolder . ' ' . $outputFileName. ' ' . $url;

  $a = shell_exec($ch);
  
  return $a;
}

public function upload(Request $request)
{
        $file = $request->file('filebutton');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
// Process file name
        $filename = TransactionController::processFileName($filename);
// Check that is CSV
        if ($extension !== 'csv') {
          return "We can only upload CSV's";
        }
        //Move Uploaded File
     $destinationPath = public_path().'/uploads';
     $file->move($destinationPath,$filename);
     //Save Transaction List
     $a = new Transaction;
     $a->isDone = 0;
     //THis is storing the wrong file
     //$a->publicPath = ;
     $a->save();

     $exportFileid = $a->id;
     // $pathToInputFile = url('/').'/uploads'.'/'.$filename;
     $pathToInputFile = public_path().'/uploads'.'/'.$filename;
     $pathToExportFolder = public_path().'/exports';
     $outputFileName = $filename;

     TransactionController::processFile($exportFileid,$pathToInputFile,$pathToExportFolder,$outputFileName);

    return redirect('thank-you');

    }



public function show()
{
// return "cats";
$lists = Transaction::all();

return $lists;

}


}
