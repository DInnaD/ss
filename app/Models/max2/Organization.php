<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

/**
* @property  int creator_id
*/

class Organization extends Model
{
    use SoftDeletes;
    
     /******* Properties *******/

    protected $hidden = [
        'api_token', 'deleted_at', 'pivot'
    ];
    protected $fillable = [ 
        'title', 
        'country',
        'city',
        'creator_id'
    ];
    
    public static function boot()
    {
        parent::boot();

        static::creating(function(self $model)
        {
            if(\Auth::id()){
                $model->creator_id = \Auth::id();
           }
        });
    }
    
     /******* Relations *******/

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id')->withDefault();
    }

    public function creator()
    {
        return $this->user();
    }

    public function vacancies()
    {
    	return $this->hasMany('App\Vacancy');
    }

    /******* CRUD Functions *******/

     /******* Static Functions *******/

    public static function getOrganizationList(Request $request)
    {
        $organizations = User::all();
        $all = $organizations->count();
        $active = count($organizations->where('deleted_at', '=', null)->all());        
        $softDelete = \App\Organization::onlyTrashed()->count();
        return $organization = collect(['active' =>  $active, 'softDelete' => $softDelete, 'all' => $all]);
    }

    public static function getShowList(Request $request)
    {
        // $organization = $organization->load(['creator', 'vacancies' => function ($query) {
        //     $query->withCount(['workers AS workers_booked'])->get();
        // }]);
        // $organization->vacancies->each(function ($value){
           
        //     $value->getStatusAttribute();
        // });
        //$organization->getVacanciesEndWorkers();

        

       //  $only_active = $request->input('only_active', null);
       //  $workers_amount = $request->get('workers_amount', 0);   
   
       // // MAX ASK AS w ???????
        // $builder = self::query();
        // $builder->select('vacancies.*', 'w.c', DB::raw("IF(
        //     vacancies.workers_amount > w.c, 
        //     'active',
        //     'closed'
        //     ) AS status"));
        // $subQuery = DB::query()
        //     ->select('vacancy_id', DB::raw('COUNT(vacancy_id) AS c'))
        //     ->from('user_vacancy')
        //     ->groupBy('vacancy_id');
        // $builder->leftJoinSub($subQuery,'w', function ($query) {
        //     $query->on('w.vacancy_id', 'vacancies.id');
        // });
       //  if($only_active == 'true'){     
       //      $builder->whereRaw("vacancies.workers_amount > w.c");
       //  }elseif($only_active == 'false'){
       //      $builder->whereRaw("vacancies.workers_amount <= w.c");
       //  }

       //  $results = $builder->get();


       //  return $results; 
        $vacancies = $request->input('vacancies', null);
        $workers = $request->input('workers', null);
        $only_active = $request->input('only_active', null);
        $workers_amount = $request->get('workers_amount', 0);    
         


        $builder=self::query();//ask Max AS w?????
        $builder->select('organizations.*', 'v', 'cr', 'w.c', DB::raw("IF(
            vacancies.workers_amount > w.c, 
            'active',
            'closed'
            ) AS status"));       
        $builder
            ->select('creator_id')
            ->from('users')
            ->groupBy('creator_id');
        
        $subQueryVacancies=DB::query()
            ->select('organization_id')
            ->from('vacancies')
            ->groupBy('organization_id');
        $builder->leftJoinSub($subQueryVacancies,'v', function ($query) {
            $query->on('v.organization_id', 'organizations.id');
        });
        $builder->select('vacancies.*', 'w.c', DB::raw("IF(
            vacancies.workers_amount > w.c, 
            'active',
            'closed'
            ) AS status"));//status not work ask Max
        $subQueryWorkers = DB::query()
            ->select('vacancy_id', DB::raw('COUNT(vacancy_id) AS c'))
            ->from('user_vacancy')
            ->groupBy('vacancy_id');
        $builder->leftJoinSub($subQueryWorkers,'w', function ($query) {
            $query->on('w.vacancy_id', 'vacancies.id');
        });
        if ($vacancies == '1' && $workers == '1') {
            $builder->whereRaw('vacancies.workers_amount <= w.c');
        }elseif($vacancies == '2'&& $workers == '1'){
            $builder->whereRaw('vacancies.workers_amount > w.c');            
        }elseif($vacancies == '3'&& $workers == '1'){
            $builder->whereRaw('vacancies.workers_amount <= w.c' and 'vacancies.workers_amount > w.c');
        }elseif($vacancies == '0' && $workers == '0'){
        
        }
        
        $results = $builder->get();

        return $results;  



// $builder=self::query();
//             $builder->select('vacancies.*', 'w.c', DB::raw("IF(
//                 vacancies.workers_amount > w.c, 
//                 'active',
//                 'closed'
//                 ) AS status"));
//             $subQuery=DB::query()
//                 ->select('vacancy_id', DB::raw('COUNT(vacancy_id) AS c'))
//                 ->from('user_vacancy')
//                 ->groupBy('vacancy_id');
//             $builder->leftJoinSub($subQuery,'w', function ($query) {
//                 $query->on('w.vacancy_id', 'vacancies.id');
//             });
        // $builder = self::query();

        // if ($vacancies || $workers) {
        //     $builder->where('1', $vacancies_closed);
        //     if ($workers) {
        //         $builder->where('1', $workers_booked);
        //     } else {
        //         $builder->where('0', $workers_nothing);
        //     }
        // } elseif ($vacancies || $workers) {
        //     $builder->where('2', $vacancies_active);
        //     if ($workers) {
        //         $builder->where('1', $workers_booked);
        //     } else {
        //         $builder->where('0', $workers_nothing);
        //     }
        // } elseif ($vacancies || $workers) {
        //     $builder->where('3', $vacancies_all);
        //     if ($workers) {
        //         $builder->where('1', $workers_booked);
        //     } else {
        //         $builder->where('0', $workers_nothing);
        //     }
        // } else {
        //         $builder->where('0', $vacancies_nothing);
        // }
    

        // Eager Loading relations for resource *************ask Max about BED PRACTICE use eager&with & Carbonefunctionality
       $builder->with(['organization.vacancies', 'vacancy.workers']);

        return $builder->get()->appends(\Request::query());
    }

    public static function getVacanciesEndWorkers(Request  $request)
    {    
        if (isset($_vacancies) and $_vacancies != 0 ) {
            $vacancies = $organization->vacancies;
            foreach ($vacancies as $key => $vacancy) {
                if ($_vacancies == 1) {
                    if($vacancy->shouldShowStatusClosed()){
                        unset($vacancies[$key]);
                    }
                } elseif ($_vacancies == 2) {
                    if($vacancy->shouldShowStatusActive()){
                        unset($vacancies[$key]);
                    }
                } elseif ($_vacancies == 3) {
                    if($vacancy->shouldShowStatusClosed() || $vacancy->shouldShowStatusActive()){
                        
                    }
                }
            }
            if ($_workers == 1) {
                    $organization->getWorkerList($request);
                }
        } else {
            unset($organization['vacancies']);
        }
    }

    public function getWorkerList(Request $request)
    {
        $workers = [];
        foreach ($this->vacancies as $vacancy) {
            array_push($workers, $vacancy->workers);
            unset($vacancy['workers']);
        }
        return $this->workers = collect($workers)->collapse()->all();
    }
}
