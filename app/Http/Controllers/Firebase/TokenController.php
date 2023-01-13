<?php

namespace App\Http\Controllers\Firebase;

use App\FirebaseToken;
use App\Http\Controllers\Controller;
use App\Libraries\Firebase;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\Messaging\InvalidArgument;

/**
 * Class TokenController
 *
 * @package App\Http\Controllers\Firebase
 */
class TokenController extends Controller
{
    use Firebase;
    
    /**
     * @var \App\FirebaseToken
     */
    private $firebaseToken;
    
    /**
     * TokenController constructor.
     *
     * @param \App\FirebaseToken $firebaseToken
     */
    public function __construct(FirebaseToken $firebaseToken)
    {
        $this->firebaseToken = $firebaseToken;
    }
    
    /**
     * Check.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function check()
    {
        $token = request('token');
    
        $appInstance = $this->getFirebase()->getMessaging()->getAppInstance($token);
        
        return response()->json($appInstance->rawData());
    }
    
    /**
     * Store.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $token = $request->get('token');
        
        try {
            $appInstance = $this->getFirebase()->getMessaging()->getAppInstance($token);
        } catch (InvalidArgument $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ]);
        }
    
        $firebaseToken = $this->firebaseToken->where('token', $token)->first();
    
        if (is_null($firebaseToken)) {
            $this->firebaseToken->add([
                'user_id' => auth()->id(),
                'token' => $token,
                'data' => $appInstance->rawData(),
            ]);
        } else {
            $firebaseToken->update([
                'data' => $appInstance->rawData(),
            ]);
        }
    
        $this->getFirebase()->getMessaging()->subscribeToTopic('global', $token);
    
        return $this->getStoreJsonResponse($firebaseToken, null, is_null($firebaseToken) ? 'Token je uspješno spašen.' : 'Token je uspješno ažuriran.');
    }
    
    /**
     * Delete.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $this->firebaseToken->where('token', $request->get('token', 'none'))->delete();
        
        return response()->json([
            'message' => 'Token je uspješno obrisan.',
        ]);
    }
}