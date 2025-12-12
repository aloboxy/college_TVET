<?php

namespace App\Http\Controllers\SupportTeam;
use App\Http\Controllers\Controller;

use App\Models\Minor;
use App\Models\Major;
use Illuminate\Http\Request;
use App\Models\college;
use App\Helpers\Qs;

class MinorController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
        $this->middleware('can:minor.manage');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $d['major'] = Major::all();
        $d['minors'] = Minor::all();
        return view('pages.support_team.minor.index',$d);
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
        try {
            // Validate the request
            $request->validate([
                'major_id' => 'required|integer|exists:majors,id',
                'minor' => 'required|string|max:255',
            ]);

            // Create the minor
            Minor::create([
                'major_id' => $request->major_id,
                'minor' => $request->minor,
            ]);

            // Return success response (assuming Qs::jsonStoreOk is a helper method)
            return Qs::jsonStoreOk();
        } catch (\Exception $e) {
            // Error handling
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while storing the minor.',
                'error' => $e->getMessage() // Uncomment for debugging
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Minor  $minor
     * @return \Illuminate\Http\Response
     */
    public function show(Minor $major)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Minor  $minor
     * @return \Illuminate\Http\Response
     */
    public function edit(Minor $minor)
    {
        $d['minor'] = $minor;
        $d['major'] = Major::all();
        return view('pages.support_team.minor.edit', $d);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Minor  $minor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Minor $minor)
    {
        //
        // dd($request->all());
        try {
            $validated = $request->validate([
                'major_id' => 'required|integer|exists:majors,id',
                'minor' => 'required|string|max:255',
            ]);

            $minor->update($validated);

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
     * @param  \App\Models\Minor  $minor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Minor $minor)
    {
        //
        try {
            $minor->delete();
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
