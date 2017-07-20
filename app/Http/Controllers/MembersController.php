<?php

namespace App\Http\Controllers;
use App\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use DB;
use App\Http\Requests\MemberRequest;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {    
        if ($id == null) {
            return Member::orderBy('id')->get();
        } else {
            return $this->show($id);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemberRequest $request)
    {
        $input_data = $request->all();

        $member = new Member;
        $member->name = $input_data["name"];
        $member->address = $input_data["address"];
        $member->age = $input_data["age"];

        if($file = $request->hasFile('image')) {
            $file = $request->file('image') ; 
            $fileName = $file->getClientOriginalName() ;
            $destinationPath = public_path().'/images/' ;
            $file->move($destinationPath,$fileName);
            $member->image = $fileName ;
        } else {
            $member->image = "";
        }
        $member->save();
        
        return response()->json([
            'error' => true,
            'messages' => 'Create member successfully'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return Member::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $this->validate($request, [
            'name' => 'required|max:100',
            'address' => 'required|max:300',
            'age' => 'required|digits_between:1,2|regex:/^\d{0,9}(\.\d{1,9})?$/',
        ]);

        //
        $input_data = $request->all();

        $member = Member::find($id);
        $member->name = $input_data["name"];
        $member->address = $input_data["address"];
        $member->age = $input_data["age"];
        if($file = $request->hasFile('image')) {
            
            $file = $request->file('image') ;
            
            $fileName = $file->getClientOriginalName() ;
            $destinationPath = public_path().'/images/' ;
            $file->move($destinationPath,$fileName);
            $member->image = $fileName ;
        }
        //$member->image = "";
        
        $member->save();
        $request->session()->flash('alert-success', 'Member was successful edited!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //dd($request);
        // $input_data = $request->all();
        $member = Member::findOrFail($id);
        $member->delete();

        return "Member record successfully deleted #" .$id;
    }
}
