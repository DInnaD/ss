<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Organization;
use App\Vacancy;
use Illuminate\Http\Request;
use App\Http\Requests\OrganizationCreateRequest;
use App\Http\Requests\OrganizationUpdateRequest;
use App\Http\Resources\OrganizationResourceCollection;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Database\Query\Builder;

class OrganizationController extends Controller
{   
    // protected static $organizations;

    // public static function addOrganizations($array)
    // {
    //     return self::$organizations[] = $array;
    // }

    public static function getOrganizations()
    {//commented providers

//    $organizations = DB::table('organizations')
    //->value('title');
    //select('id')->get();
//    return response()->json($organizations);

    
    // $organizations = DB::table('organizations')->orderBy('id')->chunk(2, function($organizations){//return chunk true or false
    //     foreach ($organizations as $organization) {
    //         OrganizationController::addOrganizations($organization);
    //     }
    //     return false;
    // });// it is return true i what is a rison?

    return response()->json($organizations);
    //dump(self::$organizations);

    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $this->authorize('index', Organization::class);
        $organizations = \App\Http\Resources\OrganizationResourceCollection::make(Organization::all());
        foreach ($organizations as $organization) {
            $organization->load(['creator']);
        }

        return new OrganizationResourceCollection($organizations);
    }

    public function indexStats(Request $request)
    {
        $this->authorize('indexStats', Organization::class);
        $organization = Organization::getOrganizationList($request);        
    
        return response()->json(['success' => true, 'data' => $organization], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationCreateRequest $request)
    {
        $this->authorize('create', Organization::class);
        $data = $request->validated();
        $organization = Organization::create($request->all(), $data);
        
        return new OrganizationResource($organization);
        
    }

    
    /**
     * Display the specified resource.
     *
     * TODO: $id -> $organization
     *
     * @param  Organization  $organization
     * @param Vacancy $vacany
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Organization $organization)
    {
        $this->authorize('show', Organization::class);
        // $_vacancies = $request->get('vacancies');
        // $_workers = $request->get('workers');
        // $organization = $organization->load(['creator', 'vacancies' => function ($query) {
        //     $query->withCount(['workers AS workers_booked'])->get();
        // }]);
        // $organization->vacancies->each(function ($value){
           
        //     $value->getStatusAttribute();
        // });
        // $organization->_vacancies = $_vacancies;
        // //$organization = Organization::getVacanciesEndWorkers($request); 
        // if (isset($_vacancies) and $_vacancies != 0) {
        //     $vacancies = $organization->vacancies;
        //     foreach ($vacancies as $key => $vacancy) {
        //         if ($_vacancies == 1) {
        //             if($vacancy->shouldShowStatusClosed()){
        //                 unset($vacancies[$key]);
        //             }
        //         } elseif ($_vacancies == 2) {
        //             if($vacancy->shouldShowStatusActive()){
        //                 unset($vacancies[$key]);
        //             }
        //         } elseif ($_vacancies == 3) {
        //             if($vacancy->shouldShowStatusClosed() || $vacancy->shouldShowStatusActive()){
                        
        //             }
        //         }
        //     }
        //     if ($_workers == 1) {
        //             $organization->getWorkerList($request);
        //         }
        // } else {
        //     unset($organization['vacancies']);
        // }


        $organization = Organization::getShowList($request); 

        return new OrganizationResource($organization);
    
     }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(OrganizationUpdateRequest $request, Organization $organization)
    {
        $this->authorize('update', Organization::class);
        $data = $request->validated();
        $organization->update($request->all(), $data);
        
        return new OrganizationResource($organization);
        
    }

    /** 
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        $this->authorize('delete', Organization::class);
        $organization->delete();

        return response()->json(['success' => true], 200);
        
    }
}
