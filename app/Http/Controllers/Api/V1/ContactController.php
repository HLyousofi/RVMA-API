<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StoreContactRequest;
use App\Http\Requests\V1\UpdateContactRequest;
use App\Http\Resources\V1\ContactResource;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the contacts with pagination and caching.
     */
    public function index(Request $request)
    {
        

        $customerId = $request->query('customerId');
     
        if (!$customerId) {
            return response()->json(['error' => 'customer_id is required'], 400);
        }
        $pageSize = $request->input('pageSize', 15); // Default to 15 if not provided
        $page = $request->input('page', 1); // Default to page 1 if not provided

        // Generate a unique cache key based on page and pageSize
        $cacheKey = 'contacts:' . md5(json_encode([
            'page' => $request->query('page', 1),
            'pageSize' => $pageSize,
            'customerId' => $customerId
        ]));

        $cacheTTL = now()->addMinutes(60);

        $paginatedContacts = Cache::tags(['contacts'])->remember($cacheKey, $cacheTTL, function () use ( $pageSize, $request, $customerId) {
            return  Contact::where('customer_id', $customerId)->paginate($pageSize);;
        });


        
        return ContactResource::collection($paginatedContacts);
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create($request->validated());

        return new ContactResource($contact);
    }

    /**
     * Display the specified contact.
     */
    public function show(Contact $contact)
    {
        return new ContactResource($contact);
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update($request->validated());

        // Clear cache for all pages to ensure consistency
        Cache::forget('contacts_page_*');

        return new ContactResource($contact);
    }

    /**
     * Remove the specified contact from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        // Clear cache for all pages to ensure consistency
        Cache::forget('contacts_page_*');

        return response()->json(null, 204);
    }
}