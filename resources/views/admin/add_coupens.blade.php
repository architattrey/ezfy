@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper" ng-app="addCouponsApp" ng-controller="addCouponsController">
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Add Coupons Section</li>
        </ol>
    </section>
    <!-- ADD COUPONS BY INSTITUTE -->
    <div class="container"> 
        <div class="row" style="background:white;">
            <div class="coupens_institute" style="background:white; margin-top:12px;">
                <h2 style="margin-top: 47px;">ADD &nbsp; COUPONS  &nbsp; FOR &nbsp; INSTITUTE</h2>
                <div class = "col-sm-12" id="all_coupns_fields">
                    
                    <!-- col-sm-4 col-md-4 -->
                    <div class="col-sm-4 col-md-4 " >
                        <!-- institute 1 -->
                        <div class="institute1">
                            <div class="form-group">
                                <label for="product">Select Product</label>
                                <select ng-model="productId" class="form-control">
                                    <option value="" label="Please Select Product"></option>
                                    <option ng-repeat="Product in ProductData" value="@{{Product.id}}">
                                        @{{Product.plan}} / washes-@{{Product.washes}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!--/ institute 1 -->
                    </div>
                    <!--/ col-sm-4 col-md-4 -->
                    <!-- col-sm-4 col-md-4 -->
                    <div class="col-sm-4 col-md-4">
                        <!-- institute 2 -->
                        <div class="institute2">
                            <div class="form-group">
                                <label for="institute">Select Institute</label>
                                <select ng-model="instituteId" class="form-control">
                                    <option value="" label="Please Select Institute"></option>
                                    <option ng-repeat="institute in instituteData" value="@{{institute.id}}">
                                        @{{institute.institute}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!--/ institute 2 -->
                    </div>
                    <!--/ col-sm-4 col-md-4 -->
                    <!--  col-sm-4 col-md-4 -->
                    <div class="col-sm-4 col-md-4">
                        <!-- institute 3 -->
                        <div class="institute3">
                            <div class="form-group">
                                <label for="user_type">Select User Type</label>
                                <select ng-model="userType" class="form-control">
                                    <option value="" label="Please Select User Type"></option>
                                    <option value="Student">Student</option>
                                    <option value="Staff">  Staff</option>
                                </select>
                            </div>
                            
                        </div>
                        <!-- /institute 3 -->
                        <button type="button" class="btn btn-success" ng-click="addcoupons()" style="margin-left: 61%; margin-bottom: 25px;">Success</button>
                    </div> 
                    <!--/ col-sm-4 col-md-4 -->
                </div>    
            </div>
             
        </div>
    </div>
    <hr>
    <!--/ ADD COUPONS BY INSTITUTE -->
    <!-- ADD COUPONS BY USER iD -->
    <div class="container"> 
        <div class="row" style="background:white;">
            <div class="coupens_institute" style="background:white; margin-top:12px;">
                <h2 style="margin-top: 47px;">ADD &nbsp; COUPONS &nbsp; FOR &nbsp; PARTICULAR &nbsp; USER</h2>
                <div class = "col-sm-12" id="all_coupns_fields">
                    <!-- col-sm-4 col-md-4 -->
                    <div class="col-sm-4 col-md-4 " >
                        <!-- institute 1 -->
                        <div class="institute1">
                            <div class="form-group">
                                <label for="product">Select Product</label>
                                <select ng-model="productId" class="form-control">
                                    <option value="" label="Please Select Product"></option>
                                    <option ng-repeat="Product in ProductData" value="@{{Product.id}}">
                                        @{{Product.plan}} / washes-@{{Product.washes}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!--/ institute 1 -->
                    </div>
                    <!--/ col-sm-4 col-md-4 -->
                    <!-- col-sm-4 col-md-4 -->
                    <div class="col-sm-4 col-md-4">
                        <!-- institute 2 -->
                        <div class="institute2">
                            <div class="form-group">
                                <label for="user">Select User</label>
                                <select ng-model="userId" class="form-control">
                                    <option value="" label="Please Select user"></option>
                                    <option ng-repeat="user in userData" value="@{{user.id}}">
                                    @{{user.name}} / @{{user.get_institute.institute}} Institute
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!--/ institute 2 -->
                    </div>
                    <!--/ col-sm-4 col-md-4 -->
                    <!--  col-sm-4 col-md-4 -->
                    <div class="col-sm-4 col-md-4">
                        <!-- institute 3 -->
                        <div class="institute3">
                            <div class="form-group">
                                <!-- <label for="user_type">Select User Type</label>
                                <select ng-model="userType" class="form-control">
                                    <option value="" label="Please Select User Type"></option>
                                    <option value="Student">Student</option>
                                    <option value="Staff">  Staff</option>
                                </select> -->
                            </div>
                            
                        </div>
                        <!-- /institute 3 -->
                        <button type="button" class="btn btn-success" ng-click="addcoupons()" style="margin-left: 61%; margin-bottom: 25px;">Success</button>
                    </div> 
                    <!--/ col-sm-4 col-md-4 -->
                </div>    
            </div>
             
        </div>
    </div>
    <!--/ ADD COUPONS BY USER iD -->
     

   
</div>
@endsection
@section('script')
<script>
    var addCouponsApp = angular.module("addCouponsApp", []);
    addCouponsApp.controller("addCouponsController", function ($scope, $http) {
        $scope.productId   = "";
        $scope.instituteId = "";
        $scope.userType    = "";
        $scope.userId      = "";

        //all products
        $scope.ProductData = [];
        $scope.getProducts = function () {
            $http.get("{{url('/')}}/get-products").then(response => {
                $scope.ProductData = response.data.data.products;
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        $scope.getProducts();
        // institutes listing
        $scope.instituteData = [];
        $scope.getRequest = function() {
            $http.get("{{url('/')}}/get-institutes").then(response =>{
                $scope.instituteData = response.data.data.institutes;
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        $scope.getRequest();
        //get users
        $scope.userData = [];
        $scope.getData = function() {
            $http.post("{{url('/')}}/get-users").then(response =>{
                $scope.userData = response.data.data.users;
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        $scope.getData();
        // add coupons
        $scope.addcoupons = function() {
            var reqData={
                product_id:$scope.productId,
                institute_id:$scope.instituteId,
                user_type : $scope.userType,
                user_id : $scope.userId
            }
            $http.post("{{url('/')}}/institute-wise-add-coupons",reqData).then(response =>{
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else if(response.data.status=="not found"){
                    swal("Something not found with provided data!", "Contact to administrator!", "warning");
                }else{
                    swal("Successfully added coupons to user", "Successfully Submited", "success");
                }
                $scope.getRequest();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
    });
</script>
@endsection