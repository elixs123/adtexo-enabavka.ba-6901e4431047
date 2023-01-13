<?php

namespace App\Http\Controllers\Action;

use App\Action;
use App\Document;
use App\Http\Controllers\Controller;
use App\Person;
use App\Support\Controller\DatesHelper;
use App\User;

/**
 * Class StatsController
 *
 * @package App\Http\Controllers\Action
 */
class StatsController extends Controller
{
    use DatesHelper;
    
    /**
     * StatsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show()
    {
        $dates = $this->getDatesData();
        
        $documents = $this->getActionDocuments($dates);
        
        $salesmen = $this->getSalesmen($documents->pluck('created_by')->unique()->toArray());
        
        $actions = $this->getActionsFromDocuments($dates);
        
        if (request('export') == 'xls') {
            if ($salesmen_id = request('salesmen_id')) {
                $salesmen = [
                    $salesmen_id => $salesmen[$salesmen_id]
                ];
            }
            
            if ($action_id = request('action_id')) {
                $actions = collect([
                    $action_id => $actions[$action_id]
                ]);
            }
            
            $documents = $documents->groupBy('created_by')->map(function($documents) {
                return $documents->groupBy('action_id')->map(function($documents) {
                    return $documents->count();
                })->toArray();
            })->toArray();
            
            // return view('action.stats.export_xls')->with([
            //     'dates' => $dates,
            //     'salesmen' => $salesmen,
            //     'documents' => $documents,
            //     'actions' => $actions,
            // ]);
    
            return \Excel::create('Pregled prodaje', function($excel) use ($dates, $salesmen, $documents, $actions) {
                $excel->sheet('Pregled prodaje', function($sheet) use ($dates, $salesmen, $documents, $actions) {
                    $sheet->loadView('action.stats.export_xls')
                        ->with('dates', $dates)
                        ->with('salesmen', $salesmen)
                        ->with('documents', $documents)
                        ->with('actions', $actions);
                });
            })->download('xls');
        }
        
        $sales_chart_data = $this->getSalesChartData($documents);
        
        $salesmen_chart_data = $this->getSalesmenChartData($documents, $salesmen);
        
        return view('action.stats.show')->with([
            'dates' => $dates,
            'salesmen' => $salesmen,
            'documents' => $documents,
            'actions' => $actions,
            'sales_chart_data' => $sales_chart_data,
            'salesmen_chart_data' => $salesmen_chart_data,
        ]);
    }
    
    /**
     * @return array
     */
    private function getSalesmen($userIds = [])
    {
        $person = new Person();
        $person->limit = null;
        $person->userId = $userIds;
        $persons = $person->getAll()->reject(function($person) {
            return is_null($person->user_id);
        })->pluck('name', 'user_id')->toArray();
        
        $user = new User();
        $user->limit = null;
        $user->includeIds = $userIds;
        
        return $user->getAll()->keyBy('id')->map(function ($user) use ($persons) {
            return $persons[$user->id] ?? $user->email;
        })->prepend(trans('action.stats.labels.salesmen'), '')->toArray();
    }
    
    /**
     * @param array $dates
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getActionDocuments($dates)
    {
        $document = new Document();
        $document->limit = null;
        $document->typeId = 'order';
        $document->startDate = $dates['start_date']->toDateString();
        $document->endDate = $dates['end_date']->toDateString();
        $document->createdBy = request('salesmen_id');
        $document->statusId = ['for_invoicing', 'invoiced', 'express_post', 'shipped', 'express_post_in_process', 'delivered', 'retrieved'];
        $document->actionId = request('action_id');
        $document->onlyActionDocuments = true;
    
        return $document->relation(['rAction.rType', 'rStatus'], true)->getAll();
    }
    
    /**
     * @param array $dates
     * @return \Illuminate\Support\Collection
     */
    private function getActionsFromDocuments($dates)
    {
        $action = new Action();
        $action->limit = null;
        $action->startDate = $dates['start_date']->toDateString();
        $action->endDate = $dates['end_date']->toDateString();
        $action->statusId = ['active'];
        
        // $actions = collect([]);
        // foreach ($documents->groupBy('action_id') as $action_id => $_documents) {
        //     $actions->put($action_id, $_documents->first()->rAction);
        // }
        
        return $action->getAll()->keyBy('id');
    }
    
    /**
     * @param \Illuminate\Support\Collection $documents
     * @return \Illuminate\Support\Collection
     */
    private function getSalesChartData($documents)
    {
        return $documents->sortBy('date_of_order')->groupBy(function($document) {
            return $document->date_of_order->format('d.m.');
        })->map(function ($documents) {
            return $documents->count();
        });
    }
    
    /**
     * @param \Illuminate\Support\Collection $documents
     * @param array $salesmen
     * @return \Illuminate\Support\Collection
     */
    private function getSalesmenChartData($documents, $salesmen)
    {
        $total = $documents->count();
        
        return $documents->sortBy('date_of_order')->groupBy(function($document) use ($salesmen) {
            return $salesmen[$document->created_by] ?? 'Ostali';
        })->map(function ($documents) use ($total) {
            if ($total <= 0) {
                return 0;
            }
            
            return round(($documents->count() / $total) * 100, 2);
        });
    }
}
