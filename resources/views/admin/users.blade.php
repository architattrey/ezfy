@extends('admin.layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" ng-app="userApp" ng-controller="userController">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">User Section</li>
        </ol>
        </section>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="col-sm-6" id="search_div">
                    <button type="button" class="btn btn-success" id="search_button"><i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp; Search</button><input type="text" id="search" placeholder="&nbsp; Seach By Any.." ng-model="search">
                </div>
                <div class="back-bg" style="background-color:#fff; height: 64px; margin-top: 20px;"></div>
            </div>
        </div>
        <div class="row">
        </div>
        <!-- view list of user -->
        <!-- Main content -->
        <section class="content" >
            <table id="categories" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th width="2%">#</th>
                        <th width="10%">Institute</th>
                        <th width="10%">Name</th>
                        <th width="10%">Phone Number</th>
                        <th width="12%">Email</th>
                        <th width="10%">User Type</th>
                        <th width="10%">Created At</th>
                        <th width="10%">Updated At</th>
                        <th width="9%">Status</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody ng-repeat ="user in userData | filter:search">
                    <tr>
                        <td>@{{$index+1}}</td>
                        <td>@{{user.get_institute.institute}}</td> 
                        <td>@{{user.name}}</td>
                        <td>@{{user.phone_number}}</td>
                        <td>@{{user.mail_id}}</td>
                        <td>@{{user.user_type}}</td>
                        <td>@{{user.created_at|limitTo:10}}</td>
                        <td>@{{user.updated_at|limitTo:10}}</td>
                        <td ng-show ="@{{user.delete_status==1}}"><span class="label label-success">Enabled</span></td>
                        <td ng-show ="@{{user.delete_status==0}}"><span class="label label-danger">Disabled</span></td> 
                        <td>
                           
                            <button type="button" class="btn btn-warning"><a href="" ng-click="deleteModel(user)"><i class="fa fa-reply-all" style="font-size:16px;color:white" aria-hidden="true"></i></a></button> 

                            <button type="button" class="btn btn-primary"><a href="" ng-click="showdetails(user)"><i class="fa fa-eye" style="font-size:16px;color:white" aria-hidden="true"></i></a></button>

                            <button type="button" class="btn btn-primary"><a href="" ng-click="showtransactions(user)"><i class="fa fa-book" style="font-size:16px;color:white" aria-hidden="true"></i></a></button> 

                        </td>
                    </tr>
                </tbody> 
            </table>
        </section>    
        <!-- /.content -->
        <!-- delete model -->
        <div class="modal fade" id="deteteuser" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Do you want to Change Status</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" ng-click="deleteUser()">Change Status</button>
                        {{ Form::button('Cancel',['class'=>'btn btn-default','data-dismiss'=>'modal']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal close -->
        <!-- details model -->
        <div class="modal fade" id="showDetails" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">More Details</h4><hr>
                        <div class ="row">
                           
							<div class="clearfix"></div>
                           <div class="user_details">
                             <div class="col-md-4 col-sm-4 col-xs-4">
                                <div class="user_details_name"> 
                                    <h4>Name</h4>
                                </div>
                                <div class="user_details_name"> 
                                     <h4>Email</h4>
                                </div>
                                <div class="user_details_name"> 
                                     <h4>Contact Number</h4>
                                </div>
                                <div class="user_details_name"> 
                                     <h4>Gender</h4>
                                </div>
                                <div class="user_details_name"> 
                                     <h4>Institute</h4>
                                </div>
                                <div class="user_details_name"> 
                                     <h4>User type</h4>
                                </div>
                                <div class="user_details_name"> 
                                     <h4>Concted with Google</h4>
                                </div>
                                
                                <div class="user_details_name my_name"> 
                                     <h4>Address</h4>
                                </div>
                                <div class="user_details_name"> 
                                     <h4>Created-at</h4>
                                </div>
                                <div class="user_details_name"> 
                                     <h4>Updated-at</h4>
                                </div>
                             </div>
                             <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="user_details_name" > 
									<h4><span>-</span> @{{userDetail.name}}</h4>
                                </div>
                                <div class="user_details_name" > 
                                     <h4><span>-</span> @{{userDetail.mail_id}}</h4>
                                </div>
                                <div class="user_details_name" > 
                                     <h4><span>-</span> @{{userDetail.phone_number}}</h4>
                                </div>
                                <div class="user_details_name" > 
                                    <h4><span>-</span> @{{userDetail.gender}}</h4>
                                </div>
                                <div class="user_details_name"> 
                                    <h4><span>-</span> @{{userDetail.get_institute.institute}}</h4>
                                </div>
                                <div class="user_details_name"> 
                                    <h4><span>-</span> @{{userDetail.user_type}}</h4>
                                </div>
                                <div class="user_details_name"> 
                                    <h4><span>-</span> @{{userDetail.cncted_with_google}}</h4>
                                </div>
                                <div class="user_details_name "> 
                                    <textarea class="form-control" rows="4" disabled>@{{userDetail.address}}</textarea>
                                </div>
                                <div class="user_details_name"> 
                                    <h4><span>-</span> @{{userDetail.created_at|limitTo:10}}</h4>
                                </div>
                                <div class="user_details_name"> 
                                    <h4><span>-</span> @{{userDetail.updated_at|limitTo:10}}</h4>
                                </div>
                             </div>
                            <div class="col-md-2 col-sm-2 col-xs ">
                               <img src="@{{baseUrl}}@{{userDetail.image}}" alt="EZFY" width="100%">
                           </div>
                           </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ Form::button('Cancel',['class'=>'btn btn-default','data-dismiss'=>'modal']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal close -->
        <!-- transactions -->
		<div class="modal fade" id="showTransactions" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content" id="transections_content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="text-align:center;">All transactions of this user</h4>
                    </div>
                    <!-- search bar -->
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="col-sm-6" id="search_div">
                                <button type="button" class="btn btn-success" id="search_button"><i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp; Search</button><input type="text" id="search" placeholder="&nbsp; Seach By Any.." ng-model="search_transaction">
                            </div>
                        </div>
                    </div>
                    <!--/ search bar -->

                    <!-- all transaction show -->
                    <div class="row">
                        <div class="col-sm-12" id="transaction_column">
                            <!-- get all transections -->
                            <div class="tractions">
                                <!-- Main content -->
                                <section class="content" >
                                    <table id="categories"  datatable="ng" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="1%">#</th>
                                                <th width="8%">Order Id</th>
                                                <th width="5%">Amount</th>
                                                <th width="6%">Status</th>
                                                <th width="14%">Delivery started</th>
                                                <th width="14%">Delivery washed</th>
                                                <th width="14%">Delivery delivered</th>
                                                <th width="7%">Start Type</th>
                                                <th width="8%">User Type</th>
                                                <th width="14%">Reamining washes</th>
                                                <th width="14%">Transaction Type</th>
                                                <th width="6%">Expire/Days</th>
                                                <th width="10%">Manager</th>
                                            </tr>
                                        </thead>
                                        <tbody ng-repeat ="transaction in transactions | filter:search_transaction">
                                            <tr>
                                                <td>@{{$index+1}}</td>
                                                <td>@{{transaction.order_id}}</td>
                                                <td>@{{transaction.amount}}</td>
                                                <td>@{{transaction.status}}</td>
                                                <td>@{{transaction.dlvry_started}}</td>
                                                <td>@{{transaction.dlvry_washed}}</td>
                                                <td>@{{transaction.dlvry_delivered}}</td>
                                                <td>@{{transaction.start_type}}</td>
                                                <td>@{{transaction.user_type}}</td>
                                                <td>@{{transaction.remaining_washes}}</td>
                                                <td ng-show="@{{transaction.transaction_type=='credit'}}"><span class="label label-success">Credit</span></td>
                                                <td ng-show="@{{transaction.transaction_type=='debit'}}"><span class="label label-danger">Debit</span></td>
                                                <td>@{{transaction.expire_date}}</td>
                                                <td>@{{transaction.get_manager.name}}</td>
                                             
                                            </tr>
                                        </tbody> 
                                    </table>
                                </section>    
                                <!-- /.content -->
                            </div>
                        </div>    
                    </div>
                    <div class="modal-footer">
                        {{ Form::button('Cancel',['class'=>'btn btn-default','data-dismiss'=>'modal']) }}
                    </div>
                </div>
            </div>
        </div>
        <!--/ transactions -->
        
    </div>
@endsection
@section('script')
 
<script>
    var managerApp = angular.module("userApp",[]);
    
    managerApp.controller("userController",function($scope, $http) {
        //retailers listing   
         
        $scope.userData = [];
        $scope.getData = function() {
            $http.post("{{url('/')}}/get-users").then(response =>{
                $scope.userData = response.data.data.users;
                $scope.baseUrl = response.data.data.base_url;
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        $scope.getData();

        // delete user
        $scope.deleteModel = function(data){
            $scope.updateData = data;
            $('#deteteuser').modal('show');
		}
        $scope.deleteUser = function() {
            var delete_status = $scope.updateData.delete_status;
            var status ="";
            if(delete_status =='0'){
                status='1';
            }else{
                status='0';
            }
            var reqData={
                id:$scope.updateData.id.toString(),
                delete_status:status
            }
            $http.post("{{url('/')}}/delete-user",reqData).then(response =>{
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else{
                    $('#deteteuser').modal('hide');
                }
                $scope.getData();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        // show details
        $scope.showdetails = function(data) {
            $scope.userDetail = data;
            
            $('#showDetails').modal('show');       
        }
         
        //show trasaction 
        $scope.showtransactions = function(data){
            $scope.userDetail = data;
            var reqData={
                user_id:$scope.userDetail.id.toString(),
            }
            $http.post("{{url('/')}}/show-transactions",reqData).then(response =>{
                
                if(response.data.status == "data error"){
                    swal("Not Found!", "No Transaction Found Of This User!", "error");
                }else{
                    $scope.transactions = response.data.response;
                    $('#showTransactions').modal('show');
                }
                //$scope.getData();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });

        }
    });
</script>
@endsection	
 
