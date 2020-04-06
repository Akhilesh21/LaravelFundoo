<?php

namespace App\Http\Controllers;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header('Access-Control-Request-Method: POST');

use App\Model\Notes;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function createNote(Request $request)
    {
        $input = $request->all();
        // $token = $request->header('Authorization');
        // $array = preg_split("/\./",$token);
        // $decode = base64_decode($array[1]);
        // $user_id = $decode['sub'];
        // $input['$user_id']= $user_id;

        if ($input['title'] == null && $input['body'] == null) {
            return response()->json(['Message' => 'title and description must not be empty'], 400);
        } else {
            $input['userid']= 1;
            $notes = Notes::create($input);
            return response()->json(['Message' => 'Note Created Successfully'], 200);
        }
    }
/***
 *
 */
    public function editNote(Request $request)
    {
        $note = Notes::find($request->id);
        if ($note) {
            if ($request['title'] == null && $request['decription'] == null) {
                return response()->json(['Message' => 'title and description must not be empty'], 400);
            } else {
                $note->title = $request['title'];
                $note->decription = $request['decription'];
                $note->save();
                return response()->json(['message' => 'Note Updated Successfully'], 200);
            }
        } else {
            return response()->json(['message' => 'Unauthorized note ID'], 404);
        }

    }
    /**
     *
     */
    public function trashNote(Request $request)
    {
        $note = Notes::find($request->id);
        if ($note) {
            $note->istrash = 1;
            if ($note->save()) {
                return response()->json(['message' => 'Note Trashed successfully'], 200);
            } else {
                return response()->json(['message' => 'Erorr while trashing'], 400);
            }
        } else {
            return response()->json(['message' => 'Note Id Invalid'], 404);
        }

    }

    /**
     *
     */
    public function restoreNote(Request $request)
    {
        $note = Notes::find($request->id);
        if ($note) {
            $note->istrash = 0;
            if ($note->save()) {
                return response()->json(['message' => 'Note RESTORE succesfully'], 200);
            } else {
                return response()->json(['message' => 'Error While restoring'], 400);
            }
        } else {
            return response()->json(['message' => 'Note is Invalid'], 404);
        }
    }

    /**
     *
     */
    public function archiveNote(Request $request)
    {
        $note = Notes::find($request->id);
        if($note){
            $note->isarchive = 1;
            if($note->save()){
                return response()->json(['message' => 'Note archived successfully'],200);
            }else {
                return response()->json(['message' => 'Error While archive'], 400);
            }
        }else{
            return response()->json(['message' => 'Note is invalid'], 404);
        }
    }

    /**
     *
     */
    public function unarchiveNote(Request $request)
    {
        $note = Notes::find($request->id);
        if($note){
            $note->isarchive = 0;
            if($note->save()){
                return response()->json(['message' => 'Note unarchived successfully'],200);
            }else{
                return response()->json(['message' => 'Error while unarchive'], 400);
            }
        }else{
            return response()->json(['message' => 'Note is invalid'],404);
        }
    }

    public function getNotes(){

        /*
        $token=$request->header('Authorization');
        $tokenArray=preg_split("/\./", $token);
        $decodeToken=base64_decode($tokenArray[1]);
        $decodeToken=json_decode($decodeToken,true);
        $id=$decodeToken['sub'];
        */
        $find = Notes::where('userid', 1)->first();
        if ($find) {
            $notes = Notes::where('userid',1)->get(['id','title','decription']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function deleteNotes(Request $request)
    {
            $note= Notes::find($request['id']);
            if($note){
                $note = Notes::find($request['id'])->delete();
                return response()->json(['message' => 'Note deleted succefully'],200);
            }else
            {
                return response()->json(['message'=>'Note id is invalid'],400);
            }
    }
     
   
}
