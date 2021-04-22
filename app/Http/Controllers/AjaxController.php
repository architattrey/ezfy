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

class AjaxController extends Controller
{
    #get all category
    public function getCategories(Request $request){
        try{
            $response['categories'] = Categories::where('delete_status','1')->get();
            if(!empty($response['categories'])){
                #send response
                $base_url = url('/')."/storage/app/public/";
                return response()->json([
                    'message'=>'All Categories',
                    'code'=>200,
                    'base_url' => $base_url,
                    'data'=>$response,
                    'status'=>'success'
                ]);
            }else{
                return response()->json([
                    'message'=>"no Categories found",
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #Category image upload
    public function imageUpload(Request $request){
        try{
           
            if($request->category_image){
                
                $file_data = $request->category_image;
                $file_name = 'cat_images/' . time() . '.' . explode('/', explode(':', substr($file_data, 0, strpos($file_data, ';')))[1])[1];
                @list($type, $file_data) = explode(';', $file_data);
                @list(, $file_data) = explode(',', $file_data);
                if ($file_data != "") {
                    //dd($request->file_data);
                    \Storage::disk('public')->put($file_name, base64_decode($file_data));
                }
                // $finalPath = $file_name ? url('/')."/storage/app/public/".$file_name : url('/')."/public/dist/img/user-dummy-pic.png";
                $finalPath = $file_name;
                $base_url = url('/')."/storage/app/public/";
                if($finalPath){
                    return response()->json([
                        'message'=>' uploaded successfully.',
                        'image_url'=> $finalPath,
                        'base_url' => $base_url,
                        'code'=>200,
                        'status'=>'success'
                    ]);
                }else{
                    return response()->json([
                        'message'=>'Category not uploaded yet.',
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>"Please prvide the image.",
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    # update add category
    public function addOrUpdate(Request $request){
        try{
            #check if id
            if($request->id){
                $categoryData = Categories::where('id',$request->id)->first();
                if($categoryData){
                    $returnData = Categories::where('id',$request->id)->update([
                        'category'=> $request->categories,
                        'image'=> $request->image,
                        'updated_at'=> date('Y-m-d')
                    ]);
                    if($returnData){
                        return response()->json([
                            'message'=>'Updated successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);
                        
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please try again later",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"Sorry Category not found",
                        'status'=>'error'
                    ]);
                }
            }else{
                #add category
                $category = new Categories();
                $category->category       = $request->categories;
                $category->image          = $request->image;
                $category->delete_status  = "1";
                $category->created_at     = date("Y-m-d | h:i");
                $category->save();
                if($category->id){
                    return response()->json([
                        'message'=>'Category added successfully.',
                        'code'=>200,
                        'status'=>'success'
                    ]);
                }else{
                    return response()->json([
                        'message'=>"something went wrong contact with administrator.",
                        'status'=>'error'
                    ]);
                } 
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    # delete category
    public function deleteCategory(Request $request){
        try{
            if($request->id){
                $categoryData = Categories::where('id',$request->id)->first();
                if($categoryData){
                    $returnData = Categories::where('id',$request->id)->update([
                        'delete_status'=> "0",
                        'updated_at'=> date('Y-m-d')
                    ]);
                    if($returnData){
                        return response()->json([
                            'message'=>'Deleted successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);  
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please try again later",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"Sorry Category not found with our database",
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>"Something went wrong with this request.Please try again later",
                    'status'=>'error'
                ]); 
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    # get Sub category
    public function getSubcategory(Request $request){
        try{
            $response['subcategory'] = SubCategory::with(['getCat'])->where('delete_status','1')->get();
            if(!empty($response['subcategory'])){
                #send response
                return response()->json([
                    'message'=>'All Sub Category',
                    'code'=>200,
                    'data'=>$response,
                    'status'=>'success'
                ]);
            }else{
                return response()->json([
                    'message'=>"no sub category found",
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #add Update Subcategory
    public function addUpdateSubcategory(Request $request){
        try{
            #check if Subcategory id 
            if($request->id){
                $subCategoryData = SubCategory::where('id',$request->id)->first();
                if($subCategoryData){
                    $returnData = SubCategory::where('id',$request->id)->update([
                        'cat_id'      => $request->cat_id,
                        'sub_cat_name'=> $request->sub_cat_name,
                        'updated_at'  => date('Y-m-d')
                    ]);
                    if($returnData){
                        return response()->json([
                            'message'=>'Updated successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);
                        
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please try again later",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"Sorry Sub Category not found",
                        'status'=>'error'
                    ]);
                }
            }else{
                #add Sub category
                $model = new SubCategory();
                $model->cat_id       = $request->cat_id;
                $model->sub_cat_name = $request->sub_cat_name;
                $model->delete_status= "1";
                $model->created_at   = date("Y-m-d | h:i");
                $model->save();
                if($model->id){
                    return response()->json([
                        'message'=>'Sub Category added successfully.',
                        'code'=>200,
                        'status'=>'success'
                    ]);
                }else{
                    return response()->json([
                        'message'=>"something went wrong contact with administrator.",
                        'status'=>'error'
                    ]);
                } 
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    # delete Sub ctategory
    public function deleteSubcategory(Request $request){
        try{
            if($request->id){
                $subCategoryData = SubCategory::where('id',$request->id)->first();
                if($subCategoryData){
                    $returnData = SubCategory::where('id',$request->id)->update([
                        'delete_status'=> "0",
                        'updated_at'=> date('Y-m-d')
                    ]);
                    if($returnData){
                        return response()->json([
                            'message'=>'Sub Category Deleted successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);  
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please try again later",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"Sorry Sub Category not found with our data",
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>"Something went wrong with this request.Please try again later",
                    'status'=>'error'
                ]); 
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #get all products
    public function getProducts(Request $request){
        try{
            $response['products'] = Products::with(['getSubCat','getInstitute'])->where('delete_status','1')->get();
            if(!empty($response['products'])){
                #send response
                return response()->json([
                    'message'=>'All Products',
                    'code'=>200,
                    'data'=>$response,
                    'status'=>'success'
                ]);
            }else{
                return response()->json([
                    'message'=>"no Products found",
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #add Update Product
    public function addUpdateProduct(Request $request){
        try{
            #check if id 
            if($request->id){
                $productData = Products::where('id',$request->id)->first();
                if($productData){
                    $returnData = Products::where('id',$request->id)->update([
                       
                        'subcat_id'  => $request->subcat_id,
                        'institute_id'  => $request->institute_id,
                        'plan'=> $request->plan,
                        'price'=>$request->price,
                        'washes'=>$request->washes,
                        'updated_at'=> date('Y-m-d')
                    ]);
                    if($returnData){
                        return response()->json([
                            'message'=>'Updated successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);
                        
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please try again later",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"Sorry Data not found",
                        'status'=>'error'
                    ]);
                }
            }else{
                #add products
                $model = new Products();
                $model->subcat_id = $request->subcat_id;
                $model->institute_id = $request->institute_id;
                $model->plan    = $request->plan;
                $model->price   = $request->price;
                $model->washes  = $request->washes;
                $model->delete_status = '1';
                $model->created_at     = date("Y-m-d | h:i");
                $model->save();
                if($model->id){
                    return response()->json([
                        'message'=>'Product added successfully.',
                        'code'=>200,
                        'status'=>'success'
                    ]);
                }else{
                    return response()->json([
                        'message'=>"something went wrong contact with administrator.",
                        'status'=>'error'
                    ]);
                } 
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    # delete Product
    public function deleteProduct(Request $request){
        try{
            if($request->id){
                $brandData = Products::where('id',$request->id)->first();
                if($brandData){
                    $returnData = Products::where('id',$request->id)->update([
                       
                        'delete_status'  => "0",
                    ]);
                    if($returnData){
                        return response()->json([
                            'message'=>'Product Deleted successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);  
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please try again later",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"Sorry Product  not found with our data",
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>"Something went wrong with this request.Please try again later",
                    'status'=>'error'
                ]); 
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #get all institute
    public function getInstitutes(Request $request){
        try{
            $response['institutes'] = Institutes::where('delete_status','1')->get();
            if(!empty($response['institutes'])){
                #send response
                return response()->json([
                    'message'=>'All Institutes',
                    'code'=>200,
                    'data'=>$response,
                    'status'=>'success'
                ]);
            }else{
                return response()->json([
                    'message'=>"no Institutes found",
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #add Update institute
    public function addUpdateInstitute(Request $request){
        try{
            #check if id 
            if($request->id){
                $productData = Institutes::where('id',$request->id)->first();
                if($productData){
                    $returnData = Institutes::where('id',$request->id)->update([
                       
                        'institute' => $request->institute,
                        'updated_at'=> date('Y-m-d')
                    ]);
                    if($returnData){
                        return response()->json([
                            'message'=>'Updated successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);
                        
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please try again later",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"Sorry Data not found",
                        'status'=>'error'
                    ]);
                }
            }else{
                #add Institutes
                $model = new Institutes();
                $model->institute = $request->institute;
                $model->delete_status = '1';
                $model->created_at    = date("Y-m-d | h:i");
                $model->save();
                if($model->id){
                    return response()->json([
                        'message'=>'Institute added successfully.',
                        'code'=>200,
                        'status'=>'success'
                    ]);
                }else{
                    return response()->json([
                        'message'=>"something went wrong contact with administrator.",
                        'status'=>'error'
                    ]);
                } 
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    # delete Product
    public function deleteInstitute(Request $request){
        try{
            if($request->id){
                $brandData = Institutes::where('id',$request->id)->first();
                if($brandData){
                    $returnData = Institutes::where('id',$request->id)->update([
                        'delete_status'  => "0",
                    ]);
                    if($returnData){
                        return response()->json([
                            'message'=>'Institute Deleted successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);  
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please try again later",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"Sorry Product  not found with our data",
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>"Something went wrong with this request.Please try again later",
                    'status'=>'error'
                ]); 
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #get app manager
    public function getManagers(Request $request){
        try{
            $response['managers'] = AppManagers::with(['getInstitute'])->get();
            if(!empty($response['managers'])){
                #send response
                return response()->json([
                    'message'=>'All Managers',
                    'code'=>200,
                    'data'=>$response,
                    'status'=>'success'
                ]);
            }else{
                return response()->json([
                    'message'=>"no managers found",
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #add Update institute
    public function addUpdateManager(Request $request){
        try{
            #check if id 
            if($request->id){
                $data = AppManagers::where('id',$request->id)->first();
                if($data){
                    $returnData = AppManagers::where('id',$request->id)->update([
                       
                        'institute_id' => $request->institute_id,
                        'name' => $request->name,
                        'email' => $request->email,
                        'updated_at'=> date('Y-m-d')
                    ]);
                    if($returnData){
                        return response()->json([
                            'message'=>'Updated successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);
                        
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please try again later",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"Sorry Data not found",
                        'status'=>'error'
                    ]);
                }
            }else{
                #add Manager
                if(!empty($request->email) && !empty($request->password)){
                    $model = new AppManagers();
                    $model->institute_id = $request->institute_id;
                    $model->name = $request->name;
                    $model->email = $request->email;
                    $model->password = encrypt($request->password);
                    $model->delete_status = '1';
                    $model->created_at    = date("Y-m-d | h:i");
                    $model->save();
                    if($model->id){
                        return response()->json([
                            'message'=>'Manager added successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);
                    }else{
                        return response()->json([
                            'message'=>"something went wrong contact with administrator.",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>'Email and password must be required',
                        'status'=>'required'
                    ]);
                }
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    # delete manager
    public function deleteManager(Request $request){
        try{
            if(!empty($request->id)){
                $data = AppManagers::where('id',$request->id)->first();
                if(!empty($data)){
                    $returnData = AppManagers::where('id',$request->id)->update([
                        'delete_status'  => $request->delete_status,
                    ]);
                    if($returnData){
                        
                        return response()->json([
                            'message'=>'Manager Status changed successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);  
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please try again later",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"Sorry Product  not found with our data",
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>"Something went wrong with this request.Please try again later",
                    'status'=>'error'
                ]); 
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #show email and password
    public function  showManagerCredentials(Request $request){
        try{
            if(!empty($request->id)){
                $data = AppManagers::where('id',$request->id)->first();
                if(!empty($data)){
                    return response()->json([
                        'message'=>' Manager Credentials.',
                        'code'=>200,
                        'status'=>'success',
                        'email'=>$data->email,
                        'password'=>decrypt($data->password)
                    ]);
                }else{
                    return response()->json([
                        'message'=>'Something went wrong! Please contact to administrator',
                        'status'=>'required'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>'Something went wrong!',
                    'status'=>'required'
                ]);
            }

        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }

    }
    #get app user
    public function getUsers(Request $request){
        try{
            $response['users'] = Appusers::with(['getInstitute'])->get();
            if(!empty($response['users'])){
                $response['base_url'] = url('/')."/storage/app/";
                #send response
                return response()->json([
                    'message'=>'All Users',
                    'code'=>200,
                    'data'=>$response,
                    'status'=>'success'
                ]);
            }else{
                return response()->json([
                    'message'=>"no users found",
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    # delete user
    public function deleteUser(Request $request){
        try{
            if(!empty($request->id)){
                $data = Appusers::where('id',$request->id)->first();
                 
                if(!empty($data)){
                    $returnData = Appusers::where('id',$request->id)->update([
                        'delete_status'  => $request->delete_status,
                    ]);
                    if($returnData){
                        #send response
                        $createMsg = ($request->delete_status=="0") ? "You have been currently Disabled from EZFY mobile App. Kindly contact the administrator. ": "Now you have been successfully enabled by EZFY administrator now you can login on EZFY app.";
                        $authKey = "782a3998b8c705c6f6a650897f4f3403";
                        $mobileNumber = $data->phone_number;
                        $senderId = "EASYFY";
                        $message = $createMsg;
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
                            'message'=>'User Status changed successfully.',
                            'code'=>200,
                            'status'=>'success'
                        ]);  
                    }else{
                        return response()->json([
                            'message'=>"Something went wrong with this request.Please try again later",
                            'status'=>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"Sorry Product  not found with our data",
                        'status'=>'error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>"Something went wrong with this request.Please try again later",
                    'status'=>'error'
                ]); 
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    # show user transactions
    public function showTransactions(Request $request){
        try{
            if(!empty($request->user_id)){
                $userTreansactions = UserTransaction::with(['getManager'])->where('user_id',$request->user_id)->get();
                if(count($userTreansactions) !=0){
                    return response()->json([
                        'message'=>'User Status changed successfully.',
                        'code'=>200,
                        'status'=>'success',
                        'response'=>$userTreansactions
                    ]);
                }else{
                    return response()->json([
                        'message'=>"Data not found",
                        'status'=>'data error'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>"Something went wrong with this request.Please try again later",
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    #add institute wise coupens
    public function addInstituteWiseCoupens(Request $request){
        try{
            if(!empty($request->institute_id) && !empty($request->product_id) && !empty($request->user_type)){
                $usersData = Appusers::where('institute_id',$request->institute_id)->get();
                $productData = Products::where('id',$request->product_id)->first();
               // dd($productData->price);
                //dd($productData->price * $productData->washes);
                if(count($usersData) !=0 && !empty($productData)){
                    
                    for($i=0; $i<count($usersData); $i++){
                        $model = new UserTransaction();
                        $model->order_id = "EZCP".rand(10000,90000);
                        $model->user_id  = $usersData[$i]['id'];
                        $model->institute_id  = $request->institute_id;
                        $model->name      = $usersData[$i]['name'];
                        $model->product_id= $productData->id;
                        $model->invoice_id= rand(1000,70000);
                        $model->amount = $productData->price * $productData->washes;
                        $model->status = "Success";
                        $model->user_type = $request->user_type;
                        $model->remaining_washes = $productData->washes;
                        $model->transaction_type = 'credit';
                        $model->expire_date = date('Y-m-d', strtotime(date('Y-m-d'). ' + 30 days'));
                        $model->created_at = date('Y-m-d | h:i');
                        $model->save();

                        #send response
                        $authKey = "782a3998b8c705c6f6a650897f4f3403";
                        $mobileNumber = $usersData[$i]['phone_number'];
                        $senderId = "EASYFY";
                        $message = "You have recieved free washes. Your order id : ".$model->order_id." and Your final amount : ".$model->amount;
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
                    }
                    return response()->json([
                        'message'=>"Successfully submit the data",
                        'status'=>'success',
                    ]);
                }else{
                    return response()->json([
                        'message'=>"something went wrong.",
                        'status'=>'not Found'
                    ]);
                }
            }elseif(!empty($request->user_id) && !empty($request->product_id)){
                $usersData   = Appusers::where('id',$request->user_id)->first();
                $productData = Products::where('id',$request->product_id)->first();
                if(!empty($usersData) && !empty($productData)){
                    
                    $model = new UserTransaction();
                    $model->order_id = "EZCP".rand(10000,90000);
                    $model->user_id  = $usersData->id;
                    $model->institute_id  = $usersData->institute_id;
                    $model->name          = $usersData->name;
                    $model->product_id    = $productData->id;
                    $model->invoice_id    = rand(1000,70000);
                    $model->amount        = $productData->price * $productData->washes;
                    $model->status        = "Success";
                    $model->user_type     = $usersData->user_type;
                    $model->remaining_washes = $productData->washes;
                    $model->transaction_type = 'credit';
                    $model->expire_date      = date('Y-m-d', strtotime(date('Y-m-d'). ' + 30 days'));
                    $model->created_at       = date('Y-m-d | h:i');
                    $model->save();
                    if($model->id){
                        #send response
                        $authKey = "782a3998b8c705c6f6a650897f4f3403";
                        $mobileNumber = $userData->phone_number;
                        $senderId = "EASYFY";
                        $message = "You have recieved free washes. Your order id : ".$request->order_id." and Your final amount : ".$request->amount;
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
                            'message'=>'transaction not saved yet.',
                            'status' =>'error'
                        ]);
                    }
                }else{
                    return response()->json([
                        'message'=>"something went wrong.",
                        'status'=>'not Found'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>"something went wrong contact with administrator.",
                    'status'=>'error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
    # all transactions
    public function showAllTransactions(Request $request){
        try{
            $transactions = UserTransaction::with(['getManager','getUser','getInstitute'])->get()->all();
            if(count($transactions) !=0){
                return response()->json([
                    'message'=>'all transactions.',
                    'code'=>200,
                    'status'=>'success',
                    'response'=>$transactions
                ]);
            }else{
                return response()->json([
                    'message'=>"Data not found",
                    'status'=>'data error'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=>"Something went wrong. Please contact administrator.".$e->getMessage(),
                'status'=>'error'
            ]);
        }
    }
}
