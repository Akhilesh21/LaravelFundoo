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
    // {
    //     $note = Notes::find($request['id']);
    //     if ($note) {
    //         $note->istrash =$request['istrash'];
    //         $note->save();
    //         return response()->json(['message' => 'trashed successfully']);
    //     } else {
    //         return response()->json(['message' => 'unauthorized user']);
    //     }
    // }
    

    public function displayTrash(Request $request)
    {
            $find = Notes::where('userid', 1)->first();
            if ($find) {
                $notes = Notes::where(['userid' => 1 ,'istrash'=>true])->get(['id','title','decription','istrash']);
                return response()->json(['data' => $notes],200);
            }
            else 
            {
                return response()->json(['message' => 'unauthorized error']);
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
        
        $find = Notes::where('userid', 1)->first();
        if ($find) {

        $notes = Notes::where('userid',1)->get(['id','title','decription','color','istrash','isarchive','ispinned','reminder']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

   
     
     public function deleteNotes(Request $request) {
        $find = Notes::find($request['id']);
        if ($find) {
            $find = Notes::find($request['id'])->delete();
            return response()->json(['message' => 'Note Deleted Successfully'],200);
        } else {
            return response()->json(['message' => 'Note Id Invalid'],404);
        }
    }


    public function noteColor(Request $request){
        $note = Notes::find($request['id']);
        if($note){
            $note->color=$request->color;
            if($note->save()){
                return response()->json(['message' => 'Note  color changed'],200);
            }else{
                return response()->json(['message' => 'Error while changing color'],404);
            }
        }
        else
        {
            return response()->json(['message' => 'note id not found'],404);
        }

    }
   

    public function getPinnedNote(){
        $find = Notes::where('userid',1)->first();
        if($find){
            $notes = Notes::where(['userid' => 1 ,'ispinned'=>true])->get(
                ['id','title','description','color','ispinned','isarchived','istrash']);
                return response()->json(['data' => $notes],200);
        }else{
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function getUnPinNotes()
    {
        $find = Notes::where('userid', 1)->first();
        if ($find) {
            $notes = Notes::where(['userid' => 1 ,'ispinned'=>false])->get(['id','title','decription']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }
    
    // public function updatePin(Request $request){
    //     $find = Notes::find($request['id']);
    //     if($find){
    //         $find->ispinned = $request['ispinned'];
    //         $find->save();
    //         return response()->json(['message' => 'pin changed successfully']);
    //     }else{
    //         return response()->json(['message' => 'unauthorized error']);
    //     }
    // }
             
    public function updatePin(Request $request)
    {
        $note = Notes::find($request->id);
        if($note){
            if($note->ispinned == 1) {
             $note->ispinned = 0;
            }else
            $note->ispinned = 1;
            // $note->ispinned = 1 || $note->ispinned = 0;
            if($note->save()){
                return response()->json(['message' => 'Note unarchived successfully'],200);
            }else{
                return response()->json(['message' => 'Error while unarchive'], 400);
            }
        }else{
            return response()->json(['message' => 'Note is invalid'],404);
        }
    }

    public function addReminder(Request $request){
        $note = Notes::find($request->id);
        if($note){
            $note->reminder = $request['reminder'];
            // $note->ispinned = 1 || $note->ispinned = 0;
            if($note->save()){
                return response()->json(['message' => 'added reminder  successfully'],200);
            }else{
                return response()->json(['message' => 'Error while unarchive'], 400);
            }
        }else{
            return response()->json(['message' => 'Note is invalid'],404);
        }
    }
    public function removeReminder(Request $request){
        $note = Notes::find($request->id);
        if($note){
            $note->null;
            // $note->ispinned = 1 || $note->ispinned = 0;
            if($note->save()){
                return response()->json(['message' => 'added reminder  successfully'],200);
            }else{
                return response()->json(['message' => 'Error while unarchive'], 400);
            }
        }else{
            return response()->json(['message' => 'Note is invalid'],404);
        }
    }

}
