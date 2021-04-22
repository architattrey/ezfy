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

class UserApiController extends Controller
{
    #send otp for user login
    public function sendOtp(Request $request){
        try{
            if(!empty($request->phone_number)){
             
                // $otp = rand(1000,2000);
                
                // #send response
                // $authKey = "782a3998b8c705c6f6a650897f4f3403";
                // $mobileNumber = $request->phone_number;
                // $senderId = "EASYFY";
                // $message = "One time password for login to EZFY app is ".$otp.". DO NOT SHARE THIS OTP WITH ANYONE.";
                // $route = "4";
                // //Prepare you post parameters
                // $postData = array(
                //     'authkey' => $authKey,
                //     'mobiles' => $mobileNumber,
                //     'message' => $message,
                //     'sender'  => $senderId,
                //     'route'   => $route
                // );
                // //API URL
                // $url = "http://sms.bulksmsserviceproviders.com/api/send_http.php";
                // // init the resource
                // $ch = curl_init();
                // curl_setopt_array($ch, array(
                //     CURLOPT_URL => $url,
                //     CURLOPT_RETURNTRANSFER => true,
                //     CURLOPT_POST => true,
                //     CURLOPT_POSTFIELDS => $postData
                //     //,CURLOPT_FOLLOWLOCATION => true
                // ));
                // //Ignore SSL certificate verification
                // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                // //get response
                // $output = curl_exec($ch);
                // //Print error if any
                // if (curl_errno($ch)) {
                //     return response()->json([
                //         'message'=>curl_error($ch)."OTP did not send",
                //         'status' =>'error'
                //     ]);
                // }
                // curl_close($ch); 
                // return response()->json([
                //     'message'=>'OTP successfully Sent.',
                //     'code'=>200,
                //     'status'=>'success',
                //     'otp'=>$otp
                // ]);
                return response()->json([
                    'message'=>'ristriction error.Contact to administrator',
                    'status'=>'error'
                ]);
            }else{
                return response()->json([
                    'message'=>'Please provide the phone number.',
                    'status'=>'error'
                ]);

            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something went wrong with this request. Please contact to administrator'.$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #login with otp
    public function UserLogin(Request $request){
        try{
            $userData = Appusers::where('delete_status','1')->get();
            if(count($userData) == 30){
                return response()->json([
                    'message'=>"Can not login or sign up more than 30 users. After upload the app you can sign up more than 30",
                    'status'=>'error'
                ]);
            }
            if(!empty($request->phone_number) && !empty($request->firebase_token)){
                $appUser = Appusers::where('phone_number',$request->phone_number)->where('delete_status','1')->first();

                if(!empty($appUser)){
                    $response['user_data'] = $appUser;
                    Appusers::where('phone_number',$request->phone_number)->update([
                        'firebase_token'=>$request->firebase_token
                    ]);
                    return response()->json([
                        'message'=>"Exist user data of this phone number.",
                        'status'=>'success',
                        'code'=>200,
                        'response'=>$response['user_data']
                    ]);
                }else{
                    $model=new Appusers();
                    $model->phone_number = $request->phone_number;
                    $model->firebase_token = $request->firebase_token;
                    $model->delete_status = "1";
                    $model->created_at = date('Y-m-d | h:i');
                    $model->save();
                    if($model->id){
                        $appUser = Appusers::where('phone_number',$request->phone_number)->first();
                        if(!empty($appUser)){
                            return response()->json([
                                'message'=>"Sign up successfully",
                                'status'=>'success',
                                'code'=>200,
                                'response'=>$appUser
                            ]); 
                        }else{
                            return response()->json([
                                'message'=>"New user data has been submit but we are unable to find the data.",
                                'status'=>'error'
                            ]);  
                        }
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please contact to administrator.",
                            'status'=>'error'
                        ]);
                    }   
                }
            }else{
                return response()->json([
                    'message'=>"Please provide the number and firebase token.",
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something went wrong with this request. Please contact to administrator'.$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #update firebase token
    public function updateFireBaseToken(Request $request){
        try{
            if(!empty($request->userId)  &&  !empty($request->fireBaseToken)){
                $appUsers = Appusers::where('id',$request->userId)->first();
                if($appUsers){
                    $updateToken = Appusers::where('id',$request->userId)->update([
                        'firebase_token'    => $request->fireBaseToken,
                    ]);
                    if($updateToken){
                        return response()->json([
                            'user_token'=>"token successfully updated",
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
    #user image upload
    public function imageUpload(Request $request){
        try{
            $appUserId = $request->user_id;
            #check request have data or not
            if(!empty($appUserId) && isset($appUserId)){
                $appUser = Appusers::where('id',$appUserId)->first();
                #check user is in database
                if(!empty($appUser) && isset($appUser)) {
                    $validator = Validator::make($request->all(), ['image' => 'required']);
                    if ($validator->fails()) {
                        return response()->json([
                            'message'=>$validator->messages(),
                            'status'=>'error'
                        ]);
                    }
                    if($request->image){
                        $file_name = 'public/user_images/_user'.time().'.png';
                        $path = Storage::put($file_name, base64_decode($request->image),'public');
                        if($path==true){
                            #update image in App users table
                            $appUsers =   Appusers::where('id', $appUserId)->first();
                            $appUsers->update(['image' => $file_name]);
                            $finalPath = $file_name ? url('/').'/storage/app/'.$file_name : url('/')."/public/dist/img/user-dummy-pic.png";
                            return response()->json([
                                'message'=>'Image successfully uploaded',
                                'status'=>'success',
                                'response'=>$finalPath,
                                'code'=>200
                            ]);
                        }else{
                            return response()->json([
                                'message'=>'Something went wrong with request.Please try again later',
                                'status'=>'error'
                            ]);
                        }
                    }else{
                        return response()->json([
                            'message'=>'Please provide image for uploading',
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>'User not found',
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>'You are not able to performe this task',
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                "message" => "Something went wrong. Please contact administrator.".$e->getMessage(),
                "error" =>true,
            ]);
        }
    }
    #app user profile update
    public function appUserProfileUpdate(Request $request){
        try{
            $appUserId = $request['user_id'];
            #check request have data or not
            if(!empty($appUserId) && isset($appUserId)){
                $appUser = Appusers::where('id',$appUserId)->first();
                #check user is in database
                if(!empty($appUser) && isset($appUser)) {
                    Appusers::where('id',$appUserId)->update([
                       'name'         => $request->name,
                       'phone_number' => $request->phone_number,
                       'mail_id'      => $request->mail_id,
                       'address'      => $request->address,
                       'gender'       => $request->gender,
                       'institute_id'   => $request->institute_id,
                       'user_type'    => $request->user_type,
                       'cncted_with_google'=>$request->cncted_with_google,
                       'updated_at' => date("Y-m-d"),
                    ]);
                    $response = [];
                    $response['appUser'] =  Appusers::where('id', $appUserId)->first();
                    return response()->json([
                        'message'=>'Profile successfully updated',
                        'status'=>'success',
                        'code' =>200,
                        'data'=>$response
                    ]);
                }else{
                    return response()->json([
                        'message'=>'User not found',
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>'You are not able to performe this task',
                    'status'=>'error'
                ]);
            }        
        }catch(\Exception $e){
            return response()->json([
                "message" => "Something went wrong. Please contact administrator.".$e->getMessage(),
                "error" =>true,
            ]);
        }
    }
    #institute listing
    public function getAllInstitutes(Request $request){
        try{
            $allInstitutes = Institutes::where('delete_status','1')->get();
            if(count($allInstitutes)!=0){
                return response()->json([
                    'message'=>'all Institutes',
                    'status'=>'success',
                    'code' =>200,
                    'data'=>$allInstitutes
                ]);
            }else{
                return response()->json([
                    'message'=>'not found',
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                "message" => "Something went wrong. Please contact administrator.".$e->getMessage(),
                "status" =>"error",
            ]);
        }
    }
    #get all categories and old
    public function getAllCategories(Request $request){
        try{
            $response['allCategories'] = Categories::where('delete_status','1')->orwhere('delete_status','2')->get();
            if(count($response)!=0){
                $response['base_url'] = "http://www.projects.estateahead.com/ezfy/storage/app/public/";
                return response()->json([
                    'message'=>'All categories',
                    'status'=>'success',
                    'code' =>200,
                    'data'=>$response
                ]);
            }else{
                return response()->json([
                    'message'=>'no Data found',
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                "message" => "Something went wrong. Please contact administrator.".$e->getMessage(),
                "status" =>"error",
            ]);
        }
    }
    #get all subcats
    public function getAllSubCategories(Request $request){
        try{
            if(!empty($request->cat_id)){
                $allSubCatProducts = SubCategory::with(['getProduct'])
                                                ->where('cat_id',$request->cat_id)
                                                ->where('delete_status','1')
                                                ->get();
                if(count($allSubCatProducts)!=0){
                    return response()->json([
                        'message'=>'All Products with Subcategories',
                        'status'=>'success',
                        'code' =>200,
                        'data'=>$allSubCatProducts
                    ]);
                }else{
                    return response()->json([
                        'message'=>'no Data found',
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>'Provide  cat id',
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                "message" => "Something went wrong. Please contact administrator.".$e->getMessage(),
                "status" =>"error",
            ]);
        }
    }
    #save transactions of user
    public function submitTransaction(Request $request){
        try{
            $data = [];
            if(!empty($request->order_id) && !empty($request->user_id) && !empty($request->institute_id)){
                
                $model = new UserTransaction();
                $model->order_id   = $request->order_id;
                $model->user_id    = $request->user_id;
                $model->name       = $request->name;
                $model->institute_id= $request->institute_id;
                $model->product_id = $request->product_id;
                $model->invoice_id = rand(1000,70000);
                $model->amount     = $request->amount;
                $model->status     = $request->status;
                $model->user_type  = $request->user_type;
                $model->remaining_washes = $request->remaining_washes;
                $model->transaction_type = $request->transaction_type;
                $model->expire_date = date('Y-m-d', strtotime(date('Y-m-d'). ' + 30 days'));
                $model->created_at =  date('Y-m-d | h:i');
                $model->save();
             
                $userData = Appusers::where('institute_id',$request->institute_id)
                                     ->where('id',$request->user_id)
                                     ->first();
                
                if($model->id){
                    if($request->status =="Fail"){
                        #send response
                        $authKey = "782a3998b8c705c6f6a650897f4f3403";
                        $mobileNumber = $userData->phone_number;
                        $senderId = "EASYFY";
                        $message = "Transaction Failed."."\n"."Order id : ".$request->order_id."\n"."Amount : "."Rs.".$request->amount;
                        $route = "4";
                        //Prepare you post parameters
                        $postData = array(
                            'authkey' => $authKey,
                            'mobiles' => $mobileNumber,
                            'message' => $message,
                            'sender'  => $senderId,
                            'route'   => $route
                        );
                        //API URL
                        $url = "http://sms.bulksmsserviceproviders.com/api/send_http.php";
                        // init the resource
                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_POST => true,
                            CURLOPT_POSTFIELDS => $postData
                            //,CURLOPT_FOLLOWLOCATION => true
                        ));
                        //Ignore SSL certificate verification
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        //get response
                        $output = curl_exec($ch);
                        //Print error if any
                        if (curl_errno($ch)) {
                            return response()->json([
                                'message'=>curl_error($ch)."sms did not send but all operation has been successful",
                                'status' =>'error'
                            ]);
                        }
                        curl_close($ch); 
                        return response()->json([
                            'message'=>'Transaction added.',
                            'code'=>200,
                            'status'=>'success'
                        ]);
                    }else{
                        #send response
                        $authKey = "782a3998b8c705c6f6a650897f4f3403";
                        $mobileNumber = $userData->phone_number;
                        $senderId = "EASYFY";
                        $message = "Transaction successful."."\n"."Order id : ".$request->order_id."\n"."Amount : "."Rs.".$request->amount;
                        $route = "4";
                        //Prepare you post parameters
                        $postData = array(
                            'authkey' => $authKey,
                            'mobiles' => $mobileNumber,
                            'message' => $message,
                            'sender'  => $senderId,
                            'route'   => $route
                        );
                        //API URL
                        $url = "http://sms.bulksmsserviceproviders.com/api/send_http.php";
                        // init the resource
                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_POST => true,
                            CURLOPT_POSTFIELDS => $postData
                            //,CURLOPT_FOLLOWLOCATION => true
                        ));
                        //Ignore SSL certificate verification
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        //get response
                        $output = curl_exec($ch);
                        //Print error if any
                        if (curl_errno($ch)) {
                            return response()->json([
                                'message'=>curl_error($ch)."sms did not send but all operation has been successful",
                                'status' =>'error'
                            ]);
                        }
                        curl_close($ch); 
                        return response()->json([
                            'message'=>'Transaction successfully added.',
                            'code'=>200,
                            'status'=>'success'
                        ]);  
                    }
                }else{
                    return response()->json([
                        'message'=>'transaction not saved yet.',
                        'status' =>'error'
                    ]);
                } 
            }else{
                return response()->json([
                    'message'=>'Please provide data',
                    'status' =>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something went wrong. Please contact administrator.'.$e->getMessage(),
                'status' =>'error'
            ]);
        }
    }
    #old Transactions
    public function oldTransactions(Request $request){
        try{
            $product_ids = [];
            $response['deliveries']  = [];
            $response['subcategory'] = [];
            if(!empty($request->user_id)){
               $response['userTransections'] = $userTransections = UserTransaction::where('user_id',$request->user_id)->get();
                //print_r($response['userTransections']);die;
                for($i=0; $i<count($userTransections); $i++){
                    $product_id = $userTransections[$i]->product_id;
                    array_push($product_ids,$product_id);
                }
                for($i=0; $i<count($product_ids); $i++){
                    $products = Products::where('id', $product_ids[$i])->where('delete_status','1')->first();
                    array_push($response['deliveries'],$products);
                }
                for($i=0; $i<count($response['deliveries']); $i++){
                    $subCategory = SubCategory::where('id', $response['deliveries'][$i]['subcat_id'])->where('delete_status','1')->first();
                    array_push($response['subcategory'],$subCategory);
                }
                
                if($response){
                    #send response
                    return response()->json([
                        'message'=>'deliveries of this user.',
                        'code'=>200,
                        'data'=>$response,
                        'status'=>'success'
                    ]);
                }else{
                    return response()->json([
                        'message' => 'Transections not found of this user',
                        'status' => 'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message' => 'Please provide user id',
                    'status' => 'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something went wrong. Please contact administrator.'.$e->getMessage(),
                'status' =>'error'
            ]);
        }
    }
    #check order status
    public function checkOrderStatus(Request $request){
        try{
            if(!empty($request->user_id) && !empty($request->order_id)){
                $userTransection = UserTransaction::where('user_id',$request->user_id)
                                                     ->where('order_id',$request->order_id)
                                                     ->first();
                if($userTransection){
                    if(!empty($userTransection->dlvry_placed) && empty($userTransection->dlvry_started) && empty($userTransection->dlvry_washed) && empty($userTransection->dlvry_delivered)){
                        return response()->json([
                            'message'=>'Deliver status.',
                            'code'=>200,
                            'dlvryStatus'=>$userTransection->dlvry_placed,
                            'status'=>'success'
                        ]);
                    }elseif(!empty($userTransection->dlvry_placed) && !empty($userTransection->dlvry_started) && empty($userTransection->dlvry_washed) && empty($userTransection->dlvry_delivered)){
                        return response()->json([
                            'message'=>'Deliver status.',
                            'code'=>200,
                            'dlvryStatus'=>$userTransection->dlvry_started,
                            'status'=>'success'
                        ]);

                    }elseif(!empty($userTransection->dlvry_placed) && !empty($userTransection->dlvry_started) && !empty($userTransection->dlvry_washed) && empty($userTransection->dlvry_delivered)){
                        return response()->json([
                            'message'=>'Deliver status.',
                            'code'=>200,
                            'dlvryStatus'=>$userTransection->dlvry_washed,
                            'status'=>'success'
                        ]);

                    }elseif(!empty($userTransection->dlvry_placed) && !empty($userTransection->dlvry_started) && !empty($userTransection->dlvry_washed) && !empty($userTransection->dlvry_delivered)){
                        return response()->json([
                            'message'=>'Deliver status.',
                            'code'=>200,
                            'dlvryStatus'=>$userTransection->dlvry_delivered,
                            'status'=>'success'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message' => 'No data found with this user and order id.',
                        'status' => 'error'
                    ]);
                }                                     
            }else{
                return response()->json([
                    'message' => 'Please provide user id and order id.',
                    'status' => 'error'
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
            if(!empty($request->manager_id) && !empty($request->institute_id)){
                $userTransactions = UserTransaction::where('manager_id',$request->manager_id)
                                                    ->where('institute_id',$request->institute_id)
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
    #get perivious order
    public function getPreviousOrder(Request $request){
        try{
            $userTransections = UserTransaction::where('user_id',$request->user_id)->get();
            if(count($userTransections)!=0){
                return response()->json([
                    'message'=>'all orders',
                    'response'=>$userTransections,
                    'status'=>'success',
                    'code'=>200
                ]);
            }else{
                return response()->json([
                    'message'=>'No orders found',
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
    #sum of washes
    public function washesSum(Request $request){
        try{
            $creditWashes = [];
            $debitWashes  = [];
            $creditTransactions = UserTransaction::where('user_id',$request->user_id)->where('transaction_type','credit')->get();
            
            if(count($creditTransactions)!=0){
                for($i=0; $i<count($creditTransactions); $i++){
                    $washes = $creditTransactions[$i]['remaining_washes'];
                    array_push($creditWashes,$washes);
                } 
                $totalcreditWashes = array_sum($creditWashes);
                if($totalcreditWashes != 0){
                    return response()->json([
                        'message'=>'remaining washes.',
                        'status'=>'success',
						'code'=>200,
						'response'=>$totalcreditWashes
                    ]);
                }else{
					return response()->json([
                        'message'=>'No washes found in the database.',
                        'status'=>'error'
                    ]);
				}
            }else{
                return response()->json([
                    'message'=>'No credit transaction found',
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
    # reedemed washes
    public function remainingWashes(Request $request){
        try{
            //print_r($request->all());die;
            if(!empty($request->wash) && !empty($request->transaction_id) && !empty($request->user_id) ){
                $userTransections = UserTransaction::where('id',$request->transaction_id)->first();
                //print_r($userTransections);die;
                if($userTransections->remaining_washes ==0){
                    return response()->json([
                        'message'=>'You have not remaining washes.',
                        'status' =>'error'
                    ]); 
                }
                if(!empty($userTransections)){
                    $remainWash = $userTransections->remaining_washes - $request->wash;
                    $model = new UserTransaction();
                    $model->order_id   = "EZCP".rand(10000,90000);
                    $model->user_id    = $userTransections->user_id;
                    $model->name       = $userTransections->name;
                    $model->institute_id = $userTransections->institute_id;
                    $model->product_id = $userTransections->product_id;
                    $model->invoice_id = rand(1000,70000);
                    $model->amount     = $userTransections->amount;
                    $model->status     = $userTransections->status;
                    $model->user_type  = $userTransections->user_type;
                    $model->remaining_washes = $request->wash;
                    $model->transaction_type = "debit";
                    $model->expire_date  =  $userTransections->remaining_washes; 
                    $model->dlvry_placed = date('d-m-Y | h:i');
                    $model->created_at   = date('Y-m-d | h:i');
                    $model->save();
                    if($model->id){
                        $returnData = UserTransaction::where('id',$request->transaction_id)
                        ->update(['remaining_washes'=>$remainWash]);
                        if($returnData !=TRUE){
                            return response()->json([
                                'message'=>'new transaction is saved But old credit transaction is not updated yet.Please contact to administrator.',
                                'status' =>'error'
                            ]);   
                        }
                    }else{
                        return response()->json([
                            'message'=>'new transaction is not saved yet.Please contact to administrator.',
                            'status' =>'error'
                        ]);
                    }
                    $managerData = AppManagers::where('institute_id',$userTransections->institute_id)
                                                ->where('delete_status','1')
                                                ->get();
                    $userData = Appusers::where('institute_id',$userTransections->institute_id)
                                         ->where('id',$request->user_id)
                                        ->where('delete_status','1')
                                        ->first();
                    if(count($managerData)!=0){
                        for($i=0; $i<count($managerData); $i++){
                            $status = $this->notification($managerData[$i]['firebase_token'],$userData->phone_number,$userTransections->order_id);
                        }
                        // return response()->json([
                        //     'data'=>$status
                        // ]);
                        if($status->success =="1"){
                            #send response
                            $authKey = "782a3998b8c705c6f6a650897f4f3403";
                            $mobileNumber = $userData->phone_number;
                            $senderId = "EASYFY";
                            $message = "Congratulation! You have successfully redeemed your coupon \n Order Id : ".$userTransections->order_id."\n Validity : 48 hours.";
                            $route = "4";
                            //Prepare you post parameters
                            $postData = array(
                                'authkey' => $authKey,
                                'mobiles' => $mobileNumber,
                                'message' => $message,
                                'sender'  => $senderId,
                                'route'   => $route
                            );
                            //API URL
                            $url = "http://sms.bulksmsserviceproviders.com/api/send_http.php";
                            // init the resource
                            $ch = curl_init();
                            curl_setopt_array($ch, array(
                                CURLOPT_URL => $url,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_POST => true,
                                CURLOPT_POSTFIELDS => $postData
                                //,CURLOPT_FOLLOWLOCATION => true
                            ));
                            //Ignore SSL certificate verification
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            //get response
                            $output = curl_exec($ch);
                            //Print error if any
                            if (curl_errno($ch)) {
                                return response()->json([
                                    'message'=>curl_error($ch)."sms did not send but all operation has been successful",
                                    'status' =>'error'
                                ]);
                            }
                            curl_close($ch); 
                            return response()->json([
                                'message'=>'Transaction successfully added.',
                                'code'=>200,
                                'status'=>'success'
                            ]);
                        }else{
                            return response()->json([
                                'message'=>'notification not sent to managers.So we are not able to submit transaction of user',
                                'status' =>'error'
                            ]);
                        } 
                    }else{
                        return response()->json([
                            'message'=>'Manager not found  with provided manager_id',
                            'status' =>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>'You have not remaining washes',
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>'Please provie user_id and washes in integer type',
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
    public function notification($token,$userNumber,$orderId){

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $fcmNotification = [
           
            'to'       => $token, //single token
            'data' => [
                'title' => "New Order In Your Institute ",
                'message' =>[
                    'order_id'  =>$orderId,
                    'order_type'=>'Laundry',
                    'number'    =>$userNumber,
                    'status'    =>'New Orders', 
                    'image' =>'https://loading.io/s/asset/preview/256704.png',  
                ], 
            ]
        ];
        $headers = [
            'Authorization : key=AAAAIK-COv0:APA91bGxSoc1lRVtsf9gJOP-2DfRC3u7145eXGflvRn-ddbXTVMmlKefhTNzrARHf-SM9kgFUH9BKSC37mAw5eFayw0f80h0jlixNvCKj1QYaxyBYaiTqdASZ7CZtQxiH0pVlo-MTCKy',
            'Content-Type: application/json'
        ];
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
}
