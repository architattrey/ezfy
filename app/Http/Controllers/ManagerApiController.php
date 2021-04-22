<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;
use App\models\Appusers;
use App\models\Institutes;
use App\models\Categories;
use App\models\SubCategory;
use App\models\Products;
use App\models\AppManagers;
use App\models\UserTransaction;
use DB,Image,Password,File,Validator;

class ManagerApiController extends Controller
{
    public function ManagerLogin(Request $request){
        try{
            $userData = AppManagers::all();
            if(count($userData) == 30){
                return response()->json([
                    'message'=>"Can not login or sign up more than 30 managers. After upload the app you can sign up more than 30.",
                    'status'=>'error'
                ]);
            }
            if(!empty($request->mail_id) && !empty($request->password)){
                $validator = Validator::make($request->all(), [
                    'email'    => 'Email',
                    'password' => 'required',
                ]);
                if($validator->fails()) {
                    return response()->json([
                        'message'=>$validator->messages(),
                        'status'=>'error'
                    ]);
                }
                $managerData = AppManagers::where('email',$request->mail_id)
                                            ->where('delete_status','1')
                                            ->first();
                if(!empty($managerData)){
                    if(decrypt($managerData->password) == $request->password){
                        AppManagers::where('email',$request->mail_id)->update([
                            'firebase_token' => $request->firebase_token
                        ]);
                        $managerInfo = AppManagers::with('getInstitute')->where('id',$managerData->id)->first();
                        return response()->json([
                            'message'=>'Manager has been successfully login.',
                            'status'=>'success',
                            'code'=>200,
                            'response'=>$managerInfo
                        ]);
                    }else{
                        return response()->json([
                            'message'=>'Password is incorrect',
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>'Manager is not Exist',
                        'status'=>'error'
                    ]);
                }    
            }else{
                return response()->json([
                    'message'=>'Provide email and password',
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something went wrong. Please contact administrator.'.$e->getMessage(),
                'status' =>'error'

            ]);
        }
    }
    #update firebase token
    public function updateFireBaseToken(Request $request){
        try{
            if($request->managerId  &&  $request->fireBaseToken){
                $appManager = AppManagers::where('id',$request->managerId)->first();
                if($appManager){
                    $updateToken = AppManagers::where('id',$request->managerId)->update([
                        'firebase_token'    => $request->fireBaseToken,
                    ]);
                    if($updateToken){
                        return response()->json([
                            'message'=>"token successfully updated",
                            'status' =>'success',
                            'code' =>200,
                        ]);
                    }else{
                        return  response()->json([
                            'message'=>'token is not updated yet. please try again',
                            'status' =>'error',
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>'user is not found in database',
                        'status' =>'error',
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>' userId or token not provided',
                    'status' =>'error',
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"something went wrong.Please contact administrator.".$e->getMessage(),
                'error' =>true,
            ]);
        }
    }
    #change password
    public function changePassword(Request $request){
        try{
            if(!empty($request->manager_id) && !empty($request->password)){
                $managerData = AppManagers::where('id',$request->manager_id)->first();
                if(!empty($managerData)){
                    $returnData = AppManagers::where('id',$request->manager_id)->update([
                        'password' => encrypt($request->password),
                    ]);
                    if($returnData){
                        return response()->json([
                            'message'=>'Password has been updated',
                            'status'=>'Success',
                            'code'=>200
                        ]);

                    }else{
                        return response()->json([
                            'message'=>'Password is not updated yet.Please contact to administrator.',
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>'Manager is not Exist',
                        'status'=>'error'
                    ]);   
                }
            }else{
                return response()->json([
                    'message'=>'Please provide manager id and password.',
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something went wrong. Please contact administrator.'.$e->getMessage(),
                'status' =>'error'

            ]);
        }
    }
    #get current  and past orders
    public function getAllOrders(Request $request){
        try{
            if(!empty($request->institute_id)){
                $userTransactions = UserTransaction::where('institute_id',$request->institute_id)
                                                    ->get();
                if(count($userTransactions) !=0){
                    return response()->json([
                        'message'=>'all orders',
                        'response'=>$userTransactions,
                        'status'=>'success',
                        'code'=>200
                    ]);
                }else{
                    return response()->json([
                        'message'=>'No orders found',
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>'Please provide manager id and password.',
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something went wrong. Please contact administrator.'.$e->getMessage(),
                'status' =>'error'

            ]);
        }
    }
    # get order details
    public function getOrderDetails(Request $request){
        try{
            if(!empty($request->manager_id) && !empty($request->order_id)){
                $userTransactions = UserTransaction::where('manager_id',$request->manager_id)
                                                    ->where('order_id',$request->order_id)
                                                    ->first();
                if(!empty($userTransactions)){
                    $orderDetails = Products::with(['getSubCat'])
                                             ->where('id',$userTransactions->product_id)
                                             ->where('delete_status','1')
                                             ->get();
                    if(count($orderDetails)!=0){
                        return response()->json([
                            'message'=>'order details',
                            'response'=>$orderDetails,
                            'status'=>'success',
                            'code'=>200
                        ]);
                    }else{
                        return response()->json([
                            'message'=>'No product found for the details',
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>'No any transactions found with this order id or manager id',
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>'Please provide manager id and order id.',
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something went wrong. Please contact administrator.'.$e->getMessage(),
                'status' =>'error'

            ]);
        }
    }
    # update order details
    public function updateOrderStatus(Request $request){
        try{
            if(!empty($request->manager_id) && !empty($request->order_id)){
                $userTransactions = UserTransaction::where('order_id',$request->order_id)->first();
                #if manager come when he already updated the status
                if(!empty($userTransactions) && !empty($userTransactions->manager_id)){
                    if(!empty($request->order_status) && $request->order_status==2){
                        
                        $returnData = UserTransaction::where('order_id',$request->order_id)->update(['dlvry_washed'=>date('d-m-Y | h:i')]);

                    }elseif(!empty($request->order_status) && $request->order_status==3){
                        $returnData = UserTransaction::where('order_id',$request->order_id)->update(['dlvry_delivered'=>date('d-m-Y | h:i')]); 
                    }else{
                        return response()->json([
                            'message'=>"Please provide atleast one status."
                        ]);
                    }
                }elseif(!empty($userTransactions) && empty($userTransactions->manager_id)){
                    $returnData = UserTransaction::where('order_id',$request->order_id)
                                                    ->update([
                                                        'no_of_clothes'=>$request->no_of_clothes,
                                                        'dlvry_started'=>date('d-m-Y | h:i'),
                                                        'manager_id'=>$request->manager_id,
                                                        'start_type'=>$request->start_type,
                                                    ]);
                }else{
                    return response()->json([
                        'message'=>'Sorry no order found with this id.',
                        'status'=>'error'
                    ]);
                }
                if($returnData){
                    $userData = Appusers::where('id',$userTransactions->user_id)->where('delete_status','1')->first();
                    $status = $this->notification($userData->firebase_token,$request->order_id,$request->order_status,$request->no_of_clothes);
                    if($status->success =="1"){
                        return response()->json([
                            'message'=>'delivery status updated',
                            'status'=>'success',
                            'code'=>200
                        ]);
                    }else{
                        return response()->json([
                            'message'=>'delivery status updated. But notification not sent yet.',
                            'status'=>'success',
                            'code'=>200
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>'Sorry delivery status is not updated yet',
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>'Please provide manager id, order id and, order status.',
                    'status'=>'error'
                ]);

            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something went wrong. Please contact administrator.'.$e->getMessage(),
                'status' =>'error'

            ]);
        }
    }
    #send fireBase notification
    public function notification($token,$orderId,$status,$no_of_clothes) {
        
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $fcmNotification = [
           
            'to'       => $token, //single token
            'data' => [
                'title' => "EZFY Order Status Change !!",
                'message' =>[
                    'order_id'  =>$orderId,
                    'order_type'=>'Laundry',
                    'image' =>'https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678134-sign-check-128.png',
                    'status'    =>($status == '1')?"Started":(($status == '2')?"Washed":(($status == '3')?"Delivered":"")),
                    'no_of_clothes'=>$no_of_clothes
                ], 
            ]
        ];
        $headers = [
            'Authorization : key=AAAAIK-COv0:APA91bGxSoc1lRVtsf9gJOP-2DfRC3u7145eXGflvRn-ddbXTVMmlKefhTNzrARHf-SM9kgFUH9BKSC37mAw5eFayw0f80h0jlixNvCKj1QYaxyBYaiTqdASZ7CZtQxiH0pVlo-MTCKy',
            'Content-Type: application/json'
        ];
        //print_r($fcmNotification);die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        
        curl_close($ch);
        $result = json_decode($result);
        return $result;
    }
    # fetch user details
    public function getUserDetails(Request $request){
        try{
            if(!empty($request->user_id)){
                $response['userData'] =  Appusers::where('id',$request->user_id)->where('delete_status','1')->first();
                $response['base_url'] = "http://www.projects.estateahead.com/ezfy/storage/app/public/";
                if(!empty($response['userData'])){
                    return response()->json([
                        'message'=>'User',
                        'status'=>'success',
                        'code'=>200,
                        'response'=>$response
                    ]);
                }else{
                    return response()->json([
                        'message'=>'User not found.',
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>'Please provide the user id.',
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something went wrong. Please contact administrator.'.$e->getMessage(),
                'status' =>'error'

            ]);
        }
    }
}
