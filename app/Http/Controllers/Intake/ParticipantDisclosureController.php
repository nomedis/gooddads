<?php

namespace App\Http\Controllers\Intake;

use App\Http\Controllers\Controller;
use App\Http\Requests\Intake\ParticipantDisclosureAuthorizationStoreRequest;
use App\Http\Requests\Intake\ParticipantDisclosureAuthorizationUpdateRequest;
use App\Http\Resources\ParticipantResource;
use App\Models\ParticipantDisclosureAuthorization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ParticipantDisclosureController extends Controller
{

    public function index(Request $request): Response
    {
        $participant = $request->user()->participant;

        return Inertia::render('Intake/Disclosure/DisclosureIndex',[
            'participant' => ParticipantResource::make($participant),
            'disclosureAuthorizations' => $participant?->disclosureAuthorizations?->toArray(),
        ]);
    }


    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Intake/Disclosure/DisclosureCreate',[
            'participant' => ParticipantResource::make($request->user()->participant),
        ]);
    }

    /**
     * Handle an incoming disclosure agreement request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(ParticipantDisclosureAuthorizationStoreRequest $request)
    {
        $validated = $request->validated();
        
        $participant = $request->user()->participant;

        $participant->disclosureAuthorizations()->create($validated);

        return redirect()->back(303)->with('message', 'Disclosure Authorization Created Successfully');
    }



    /**
     * Show the form for editing the disclosure agreement.
     */
    public function show(Request $request,  ParticipantDisclosureAuthorization $disclosureAuthorization): Response
    {
        $participant = $request->user()->participant;

        return Inertia::render('Intake/Disclosure/DisclosureShow', [
            'participant' => ParticipantResource::make($participant),
            'disclosureAuthorization' => $disclosureAuthorization,
        ]);
    }

    /**
     * Show the form for editing the disclosure agreement.
     */
    public function edit(Request $request, ParticipantDisclosureAuthorization $disclosureAuthorization): Response
    {
        $participant = $request->user()->participant;
        
        return Inertia::render('Intake/Disclosure', [
            'participant' => ParticipantResource::make($participant),
            'disclosureAuthorization' => $disclosureAuthorization,
        ]);
    }

    /**
     * Update the disclosure agreement.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(ParticipantDisclosureAuthorizationUpdateRequest $request, ParticipantDisclosureAuthorization $disclosureAuthorization): RedirectResponse
    {
        $validated = $request->validated();

        $disclosureAuthorization->update($validated);

        return redirect()->back(303)->with('message', 'Disclosure Authorization Updated Successfully');
    }


    public function destroy(Request $request, ParticipantDisclosureAuthorization $disclosureAuthorization): RedirectResponse
    {
        if($disclosureAuthorization->participant_id !== $request->user()->participant->id) {
            abort(403);
        }

        $disclosureAuthorization->delete();

        return redirect()->back(303)->with('message', 'Disclosure Authorization Deleted Successfully');
    }
}