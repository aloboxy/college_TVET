<?php

namespace App\Http\Controllers\SupportTeam;

use Illuminate\Http\Request;
use App\Models\college;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use App\Http\Requests\College\CollegeCreate;
use App\Http\Requests\College\CollegeUpdate;
use App\Helpers\Qs;
use App\Http\Controllers\Controller;

class CollegeController extends Controller
{
    protected $my_class, $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->middleware('teamSA', ['except' => ['destroy',] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);
        
        $this->middleware('can:college.manage');

        $this->my_class = $my_class;
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['college'] = College::all();
        $d['teachers'] = $this->user->getUserByType('teacher');
        return view('pages.support_team.college.index',$d);
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
    public function store(CollegeCreate $req)
    {
        $data = $req->only(['name','dean']);

        try{
            College::create($data);
            return Qs::jsonStoreOk();
        }
                    catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while creating College.',
                    // 'error' => $e->getMessage() // Optional: for debugging
                ], 500);
            }


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
        $d['c'] = $c = College::find($id);
        $d['teachers'] = $this->user->getUserByType('teacher');
        return is_null($c) ? Qs::goWithDanger('college.index') : view('pages.support_team.college.edit', $d) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CollegeUpdate $req, $id)
    {
        //
        $data = $req->only(['name','dean']);
        try{
            College::find($id)->update($data);
            return Qs::jsonUpdateOk();
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating College.',
                // 'error' => $e->getMessage() // Optional: for debugging
            ], 500);
        }
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
        try{
            College::destroy($id);
            return back()->with('flash_success', __('msg.del_ok'));
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting College.',
                // 'error' => $e->getMessage() // Optional: for debugging
            ], 500);
        }
    }
}
