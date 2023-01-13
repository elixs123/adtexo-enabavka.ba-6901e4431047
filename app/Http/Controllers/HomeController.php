<?php

namespace App\Http\Controllers;

use App\Document;
use App\Route;
use App\Support\Controller\ClientHelper;
use App\Support\Controller\Dashboard\DashboardAdminHelper;
use App\Support\Controller\Dashboard\DashboardClientHelper;
use App\Support\Controller\Dashboard\DashboardEditorHelper;
use App\Support\Controller\Dashboard\DashboardExpressPostHelper;
use App\Support\Controller\Dashboard\DashboardSalesmanHelper;
use App\Support\Controller\Dashboard\DashboardSupervisorHelper;
use App\Support\Controller\Dashboard\DashboardWarehouseHelper;
use App\Support\Controller\DashboardHelper;
use App\Support\Controller\DatesHelper;
use App\Support\Controller\DocumentHelper;
use App\Support\Controller\RouteHelper;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    use ClientHelper, DashboardHelper, DocumentHelper, RouteHelper, DatesHelper;
    use DashboardAdminHelper, DashboardExpressPostHelper, DashboardSupervisorHelper, DashboardEditorHelper, DashboardWarehouseHelper, DashboardSalesmanHelper, DashboardClientHelper;
    
    /**
     * @var \App\Route
     */
    private $route;
    
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * HomeController constructor.
     *
     * @param \App\Route $route
     * @param \App\Document $document
     */
    public function __construct(Route $route, Document $document)
    {
        $this->route = $route;
        $this->document = $document;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('homepage.index')->with($this->getViewData());
    }
    
    /**
     * @return array
     */
    private function getViewData()
    {
        $data = [];
        
        $this->getSalesmanData($data);
        
        $this->getSupervisorData($data);
        
        $this->getEditorData($data);
        
        $this->getWarehouseData($data);
        
        $this->getClientData($data);
        
        $this->getAdminData($data);
        
        return $data;
    }
}
