<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

class TransactionController extends Controller
{



  public function upload(Request $request)
    {

        $file = $request->file('filebutton');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

// Check that is CSV

        if ($extension !== 'csv') {
          return "We can only upload CSV's";
        }

        //Move Uploaded File
     $destinationPath = public_path().'/uploads';
     $file->move($destinationPath,$file->getClientOriginalName());


     //Save Transaction List
     $a = new Transaction;
     $a->isDone = 0;
     $a->publicPath = url('/').'/uploads'.'/'.$file->getClientOriginalName();
     $a->save();

     return $a->id;




     return view('thankyou');




    }



}
