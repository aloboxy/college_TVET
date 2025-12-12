<?php

namespace App\Http\Controllers\SupportTeam;
use App\Http\Controllers\Controller;

use App\Models\Major;
use Illuminate\Http\Request;
use App\Models\college;
use App\Helpers\Qs;
use App\Models\ClassType;

class MajorController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
        $this->middleware('can:major.manage');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['department'] = ClassType::all();
        $d['majors'] = Major::with('minor')->get();
        return view('pages.support_team.major.index', $d);
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
    public function store(Request $request)
    {
        //
    try{ $request->validate([
            'department_id' => 'required',
            'major' => 'required',

        ]);
        Major::create([
            'department_id' => $request->department_id,
            'major' => $request->major,
        ]);
        return Qs::jsonStoreOk();

    }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the major.',
                // 'error' => $e->getMessage() // Optional: for debugging
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function show(Major $major)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function edit(Major $major)
    {
        //
        $d['major'] = $major;
        $d['department'] = ClassType::all();
        return view('pages.support_team.major.edit', $d);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Major $major)
    {
        //
        // dd($request->all());
        try {
            $validated = $request->validate([
                'department_id' => 'required|integer|exists:class_types,id',
                'major' => 'required|string|max:255',

            ]);

            $major->update($validated);

            return Qs::jsonUpdateOk();


        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the major.',
                'error' => $e->getMessage(), // Uncomment for debugging
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function destroy(Major $major)
    {
        //
        try {
            $major->delete();
            return back()->with('flash_success', __('msg.del_ok'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the major.',
                // 'error' => $e->getMessage() // Optional: for debugging
            ], 500);
        }
    }
}
