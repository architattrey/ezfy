@extends('admin.layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" ng-app="managerApp" ng-controller="managerController">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manager Section</li>
        </ol>
        </section>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="col-sm-6" id="search_div">
                    <button type="button" class="btn btn-success" id="search_button"><i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp; Search</button><input type="text" id="search" placeholder="&nbsp; Seach By Any.." ng-model="search">
                </div>
                <div class="back-bg" style="background-color:#fff; height: 64px; margin-top: 20px;">
                <a style="margin-top: 5px; padding: 10px 17px; float: right;b margin-right: 17px;"><button type="button" class="btn btn-primary" id="flip" href="" ng-click="addOpen()">Add More Managers</button></a>
                </div>
            </div>
        </div>
        <div class="row">
        </div>
        <!-- view list of agents -->
        <!-- Main content -->
        <section class="content" >
            <table id="categories" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th width="2%">#</th>
                        <th width="20%">Institute</th>
                        <th width="10%">Name</th>
                        <th width="10%">Email</th>
                        <th width="10%">Status</th>
                        <th width="10%">Created At</th>
                        <th width="10%">Updated At</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
                <tbody ng-repeat ="manager in managerData | filter:search">
                    <tr>
                     
                        <td>@{{$index+1}}</td>
                        <td>@{{manager.get_institute.institute}}</td> 
                        <td>@{{manager.name}}</td>
                        <td>@{{manager.email}}</td>
                        <td ng-show ="@{{manager.delete_status==1}}"><span class="label label-success">Enabled</span></td>
                        <td ng-show ="@{{manager.delete_status==0}}"><span class="label label-danger">Disabled</span></td> 
                        <td>@{{manager.created_at|limitTo:10}}</td>
                        <td>@{{manager.updated_at|limitTo:10}}</td>
                        <td>
                            <button type="button" class="btn btn-success"><a href="#" ng-click="update(manager)"><i class="fa fa-pencil" style="font-size:16px;color:white" aria-hidden="true"></i></a></button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-warning"><a href="#"  ng-click="deleteModel(manager)"><i class="fa fa-reply-all" style="font-size:16px;color:white" aria-hidden="true"></i></a></button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-primary"><a href="#"  ng-click="showCredentials(manager)"><i class="fa fa-eye" style="font-size:16px;color:white" aria-hidden="true"></i></a></button> 
                        </td>
                    </tr>
                </tbody> 
            </table>
        </section>    
        <!-- /.content -->
        <!-- delete model -->
        <div class="modal fade" id="detetemanager" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Do you want to Change Status</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" ng-click="deleteManager()">Change Status</button>
                        {{ Form::button('Cancel',['class'=>'btn btn-default','data-dismiss'=>'modal']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal close -->
        <!-- Credentials model -->
        <div class="modal fade" id="showcredentioals" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Manager email and password</h4><hr>
                        <div class ="row">
                            <!-- col-sm-6 -->
                            <div class="col-sm-6" style="margin-top: 19px;">
                                <h4><b>Email : @{{ManagerEmail}}</b></h4>
                            </div>
                            <!--/ col-sm-6 -->
                            <!-- col-sm-6 -->
                            <div class="col-sm-6" style="margin-top: 19px;">
                                <h4><b>Password : @{{ManagerPassword}}<b></h4>
                            </div>
                            <!--/ col-sm-6 -->
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
         
        <!-- add model -->
        <div class="modal fade" id="AddManager" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Do you want to Create New Manager</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- col-sm-6 -->
                            <div class="col-sm-6">
                                <!-- Institute -->
                                <div class="form-group">
                                    <label for="institute">Select Institute</label>
                                    <select ng-model="instituteId" class="form-control">
                                        <option value="" label="Please Select Institute"></option>
                                        <option ng-repeat="institute in instituteData" value="@{{institute.id}}">@{{institute.institute}}</option>
                                    </select>
                                </div>
                                <!-- email-->
                                <div class="form-group">
                                    <label for="">Email id</label>
                                    <input type="email" class="form-control" ng-model="Email" placeholder ="example@gmail.com" required>
                                </div> 
                            </div>    
                            <!--/ col-sm-6 -->
                            <!-- col-sm-6 -->
                            <div class="col-sm-6">
                                <!-- add name -->
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" ng-model="Name" placeholder ="Add Name" required> 
                                </div>
                                <!-- add password -->
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="text" class="form-control" ng-model="Password" placeholder ="Make Password for Manager" required> 
                                </div>
                            </div>
                            <!--/ col-sm-6 -->   
                        </div>    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" ng-click="addmanager()">Success</button>
                        {{ Form::button('Cancel',['class'=>'btn btn-default','data-dismiss'=>'modal']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal close -->
        <!-- update model -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Do you want to Update</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- col-sm-6 -->
                            <div class="col-sm-6">
                                <!-- Institute -->
                                <div class="form-group">
                                    <label for="institute">Select Institute</label>
                                    <select ng-model="instituteId" class="form-control">
                                        <option value="" label="Please Select Institute"></option>
                                        <option ng-repeat="institute in instituteData" value="@{{institute.id}}" ng-selected="institute.id == updateData.institute_id">@{{institute.institute}}</option>
                                    </select>
                                </div>
                                <!-- email-->
                                <div class="form-group">
                                    <label for="">Email id</label>
                                    <input type="email" class="form-control" ng-model="Email" placeholder ="example@gmail.com" required>
                                </div> 
                            </div>    
                            <!--/ col-sm-6 -->
                            <!-- col-sm-6 -->
                            <div class="col-sm-6">
                                <!-- add name -->
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" ng-model="Name" placeholder ="Add Name" required> 
                                </div>
                                <!-- add password -->
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="text" class="form-control" placeholder ="Make Password for Manager" value="*******" disabled> 
                                </div>
                            </div>
                            <!--/ col-sm-6 -->   
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" ng-click="updatemanager()">Update</button>
                        {{ Form::button('Cancel',['class'=>'btn btn-default','data-dismiss'=>'modal']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal close -->
        
       
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        // datatable
        // var table = $('#categories').removeAttr('width').DataTable({
        //     scrollY:        "400px",
        //     scrollX:        true,
        //     scrollCollapse: true,
        //     paging:         true,
        //     columnDefs: [
        //         { width: 200 }
        //     ],
        //     fixedColumns: true
        // });
    });
</script>

<script>
    var managerApp = angular.module("managerApp",[]);
    
    managerApp.controller("managerController",function($scope, $http) {
        // managers listing   
        //$scope.dtOptions = DTOptionsBuilder.newOptions().withOption('order', [0, 'asc']);
         
        $scope.managerData = [];
        $scope.getData = function() {
            $http.post("{{url('/')}}/get-managers").then(response =>{
                $scope.managerData = response.data.data.managers;
               // console.log($scope.managerData);
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        $scope.getData();
        // get institute for dropdown
        $scope.instituteData = [];
        $scope.getRequest = function() {
            $http.get("{{url('/')}}/get-institutes").then(response =>{
                $scope.instituteData = response.data.data.institutes;
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        $scope.getRequest();
         
        // add manager
        $scope.addOpen = function() {
            $scope.instituteId="";
            $scope.Name="";
            $scope.Email="";
            $scope.Password="";
            $('#AddManager').modal('show');
        }
        $scope.addmanager = function() {
            var reqData={
                institute_id:$scope.instituteId,
                name:$scope.Name,
                email:$scope.Email,
                password:$scope.Password
            }
            $http.post("{{url('/')}}/add-update-manager",reqData).then(response =>{
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else if(response.data.status=="required"){
                    swal("Required!", "Email and passowrd can not be empty!", "error"); 
                }else{
                    $('#AddManager').modal('hide');
                }
                $scope.getData();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        // update Manager
        $scope.update = function(data){
            $scope.updateData = data;
            $scope.instituteId= $scope.updateData.institute_id;
            $scope.Name = $scope.updateData.name;
            $scope.Email = $scope.updateData.email;
            $('#myModal').modal('show');
        }
        $scope.updatemanager = function() {
            var reqData={
                id:$scope.updateData.id,
                institute_id:$scope.instituteId,
                name:$scope.Name,
                email:$scope.Email
            }
            $http.post("{{url('/')}}/add-update-manager",reqData).then(response =>{
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else{
                    $('#myModal').modal('hide');
                }
            $scope.getData();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        // delete manager
        $scope.deleteModel = function(data){
            $scope.updateData = data;
            $('#detetemanager').modal('show');
		}
        $scope.deleteManager = function() {
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
            $http.post("{{url('/')}}/delete-manager",reqData).then(response =>{
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else{
                    $('#detetemanager').modal('hide');
                }
                $scope.getData();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        // show credentials
        $scope.showCredentials = function(data) {
            var reqData={
                id:data.id.toString(),
            }
            $http.post("{{url('/')}}/show-manager-credentials",reqData).then(response =>{
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else{
                    $scope.ManagerEmail    = response.data.email;
                    $scope.ManagerPassword = response.data.password;   
                    $('#showcredentioals').modal('show');
                }
                    
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
    });
</script>
@endsection	
 
