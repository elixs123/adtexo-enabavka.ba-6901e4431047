<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Support\Controller\AuthHelper;
use Illuminate\Support\Facades\DB;

/**
 * Class Controller
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, AuthHelper, DispatchesJobs, ValidatesRequests;
    
    /**
     * @param array $input
     * @return array
     */
    protected function prepareFormInput(array $input)
    {
        // Process checkboxes
        foreach ($this->checkbox as $field) {
            $input[$field] = array_key_exists($field, $input) ? 1 : 0;
        }
        
        return $input;
    }
    
    /**
     * Get json response for store model action.
     *
     * @param $item
     * @param $view
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    protected function getStoreJsonResponse($item, $view = null, $message = null, array $data = [])
    {
        $message = is_null($message) ? trans('skeleton.notifications.created') : $message;

        return response()->json(array_merge([
            'action' => 'store',
            'data' => $item,
            'html' => is_null($view) ? '' : view($view, ['item' => $item])->render(),
            'notification' => [
                'type' => 'success',
                'message' => $message,
            ],
            'wrapper' => $item->getTable(),
            'close_modal' => true,
        ], $data), 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * @param $item
     * @param $view
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    protected function getUpdateJsonResponse($item, $view = null, $message = null, array $data = [])
    {
        $message = is_null($message) ? trans('skeleton.notifications.updated') : $message;

        return response()->json(array_merge([
            'action' => 'update',
            'data' => $item,
            'html' => is_null($view) ? '' : view($view, ['item' => $item])->render(),
            'notification' => [
                'type' => 'success',
                'message' => $message,
            ],
            'wrapper' => $item->getTable(),
            'close_modal' => true,
        ], $data), 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * @param $item
     * @param $view
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    protected function getDestroyJsonResponse($item, $view = null, $message = null, array $data = [])
    {
        $message = is_null($message) ? trans('skeleton.notifications.deleted') : $message;

        return response()->json(array_merge([
            'action' => 'destroy',
            'data' => $item,
            'html' => is_null($view) ? '' : view($view, ['item' => $item])->render(),
            'notification' => [
                'type' => 'success',
                'message' => $message,
            ],
            'wrapper' => $item->getTable(),
            'close_modal' => true,
        ], $data), 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuccessJsonResponse(array $data = [])
    {
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * @param string $message
     * @param int $status
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getErrorJsonResponse($message, $status, array $data = [])
    {
        return response()->json(array_merge([
            'message' => $message,
        ], $data), $status, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * DB Transaction.
     *
     * @param \Closure $closure
     * @return mixed
     */
    public function dbTransaction(\Closure $closure)
    {
        return DB::transaction($closure);
    }
}
