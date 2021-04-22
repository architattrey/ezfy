@extends('admin.layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" ng-app="productApp" ng-controller="productController">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Products</li>
        </ol>
    </section>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="col-sm-6" id="search_div">
                <button type="button" class="btn btn-success" id="search_button"><i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp; Search</button><input type="text" id="search" placeholder="&nbsp; Seach By Any.." ng-model="search">
            </div>
            <div class="back-bg" style="background-color:#fff; height: 64px; margin-top: 20px;">
                <a style="margin-top: 5px; padding: 10px 17px; float: right;margin-right: 17px;"><button type="button" class="btn btn-primary" id="flip" href="" ng-click="addOpen()">Add More Products</button></a>
            </div>
        </div>
    </div>
    <div class="row">
    </div>
    <!-- view list of agents -->
    <!-- Main content -->
    <section class="content">
        <table id="categories" datatable="ng" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Institute</th>
                    <th>Sub category</th>
                    <th>Plan</th>
                    <th>Price (Rs)</th>
                    <th>Washes</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody ng-repeat="product in ProductData  | filter:search">
                <tr>
                    <td>@{{$index+1}}</td>
                    <td>@{{product.get_institute.institute}}</td>
                    <td>@{{product.get_sub_cat.sub_cat_name}}</td>
                    <td>@{{product.plan}}</td>
                    <td>@{{product.price}}</td>
                    <td>@{{product.washes}}</td>
                    <td>@{{product.created_at|limitTo:10}}</td>
                    <td>@{{product.updated_at|limitTo:10}}</td>
                    <td>
                        <button type=" button" class="btn btn-success"><a href="" ng-click="updateModal(product)"><i class="fa fa-pencil" style="font-size:16px;color:white" aria-hidden="true"></i></a></button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-danger"><a href="" ng-click="deleteModal(product)"><i class="fa fa-trash" style="font-size:16px;color:white" aria-hidden="true"></i></a></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
    <!-- /.content -->
    <!-- add product modal -->
    <div class="modal fade" id="AddProduct" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" id="product_content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Do you want add product</h4>
                </div>
                <div class="modal-body">
                    <!-- row strat -->
                    <div class="row">
                        <!-- column 3 -->
                        <div class="col-sm-3">
                            <!-- category id -->
                            <div class="form-group">
                                <label for="sub_category">Select Sub Category</label>
                                <select ng-model="subcatId" class="form-control">
                                    <option value="" label="Please Select Sub Category"></option>
                                    <option ng-repeat="subcategory in subcategoryData" value="@{{subcategory.id}}">
                                        @{{subcategory.sub_cat_name}}
                                    </option>
                                </select>
                            </div>
                            <!-- institute -->
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
                        <!--/column 3  -->
                        <!--column 3  -->
                        <div class="col-sm-3">
                            <!-- Plan -->
                            <div class="form-group">
                                <label for="">Plan</label>
                                <input type="text" class="form-control" ng-model="plan" placeholder="Enter Plan eg. 3 small, 2 large" required>
                            </div>
                        </div>
                        <!--/column 3  --> 
                        <!-- column 3  -->  
                        <div class ="col-sm-3">
                            <!-- Price -->
                            <div class="form-group">
                                <label for="">Price (Rs)</label>
                                <input type="text" class="form-control"  ng-model="price" placeholder="Enter Price" required>
                            </div>
                        </div>
                        <!--/column 3  --> 
                        <!-- column 3  -->  
                        <div class ="col-sm-3">
                            <!-- Washes -->
                            <div class="form-group">
                                <label for="">Washes</label>
                                <input type="text" class="form-control" ng-model="washes" placeholder="Enter washes" required>
                            </div>
                        </div>
                        <!--/column 3  --> 
                    </div>
                    <!--/ row end -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" ng-click="addproduct()">Submit</button>
                    {{ Form::button('Cancel',['class'=>'btn btn-default','data-dismiss'=>'modal']) }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- update product modal -->
    <div class="modal fade" id="UpdateProduct" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" id="product_content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Do you want update product</h4>
                </div>
                <div class="modal-body">
                    <!-- row strat -->
                    <div class="row">
                        <!-- column 3 -->
                        <div class="col-sm-3">
                            <!-- category id -->
                            <div class="form-group">
                                <label for="sub_category">Select Sub Category</label>
                                <select ng-model="subcatId" class="form-control">
                                    <option value="" label="Please Select Sub Category"></option>
                                    <option ng-repeat="subcategory in subcategoryData" value="@{{subcategory.id}}">
                                        @{{subcategory.sub_cat_name}}
                                    </option>
                                </select>
                            </div>
                            <!-- institute -->
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
                        <!--/column 3  -->
                        <!--column 3  -->
                        <div class="col-sm-3">
                            <!-- Plan -->
                            <div class="form-group">
                                <label for="">Plan</label>
                                <input type="text" class="form-control" ng-model="plan" placeholder="Enter Plan eg. 3 small, 2 large" required>
                            </div>
                        </div>
                        <!--/column 3  --> 
                        <!-- column 3  -->  
                        <div class ="col-sm-3">
                            <!-- Price -->
                            <div class="form-group">
                                <label for="">Price (Rs)</label>
                                <input type="text" class="form-control"  ng-model="price" placeholder="Enter Price" required>
                            </div>
                        </div>
                        <!--/column 3  --> 
                        <!-- column 3  -->  
                        <div class ="col-sm-3">
                            <!-- Washes -->
                            <div class="form-group">
                                <label for="">Washes</label>
                                <input type="text" class="form-control" ng-model="washes" placeholder="Enter washes" required>
                            </div>
                        </div>
                        <!--/column 3  --> 
                    </div>
                    <!--/ row end -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success" ng-click="updateproduct()">update</button>
                    {{ Form::button('Cancel',['class'=>'btn btn-default','data-dismiss'=>'modal']) }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!--/ update Modal  -->
    <!--delete Modal  -->
    <div class="modal fade" id="deteteProduct" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Do you want to Delete</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" ng-click="deleteProduct()">Delete</button>
                    {{ Form::button('Cancel',['class'=>'btn btn-default','data-dismiss'=>'modal']) }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!--/delete Modal -->
</div>
@endsection
@section('script')

<script>
    var productApp = angular.module("productApp", []);
    productApp.controller("productController", function ($scope, $http) {
        //products Listing
        //$scope.dtOptions = DTOptionsBuilder.newOptions().withOption('order', [0, 'asc']);

        // .directive('stringToNumber', function() {
        //     return {
        //         require: 'ngModel',
        //         link: function($scope, $element, $attrs, $ngModel) {
        //         $ngModel.$parsers.push(function(value) {
        //             return '' + value;
        //         });
        //         $ngModel.$formatters.push(function(value) {
        //             return parseFloat(value);
        //         });
        //         }
        //     };
        // });
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

        // get sub cat for dropdown
        $scope.subcategoryData = [];
        $scope.getsubcat = function() {
            $http.get("{{url('/')}}/get-subcategory").then(response =>{
                $scope.subcategoryData = response.data.data.subcategory;
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        $scope.getsubcat();
         
        // open add product modal
        $scope.addOpen = function () {
            
            $scope.subcatId = "";
            $scope.instituteId ="";
            $scope.plan = "";
            $scope.price = "";
            $scope.washes = "";
            $('#AddProduct').modal('show');
        }
        // add product
        $scope.addproduct = function () {
            var reqData = {
                institute_id: $scope.instituteId,
                subcat_id: $scope.subcatId,
                plan: $scope.plan,
                price: $scope.price,
                washes: $scope.washes,
            }
            $http.post("{{url('/')}}/add-update-product", reqData).then(response => {
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else{
                    $('#AddProduct').modal('hide');
                }
                $scope.getProducts();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        // Open update modal
        $scope.updateModal = function (data) {
            $scope.updateData = data;
            $scope.subcatId = $scope.updateData.subcat_id;
            $scope.instituteId = $scope.updateData.institute_id.toString();
            $scope.plan = $scope.updateData.plan;
            $scope.price = $scope.updateData.price;
            $scope.washes = $scope.updateData.washes;
            $('#UpdateProduct').modal('show');
        }
        //update data
        $scope.updateproduct = function () {
            var reqData = {
                id: $scope.updateData.id,
                subcat_id: $scope.subcatId,
                institute_id: $scope.instituteId,
                plan: $scope.plan,
                price: $scope.price,
                washes: $scope.washes  
            }
            $http.post("{{url('/')}}/add-update-product", reqData).then(response => {
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else{
                    $('#UpdateProduct').modal('hide');
                }
                $scope.getProducts();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        // delete modal
        $scope.deleteModal = function (data) {
            $scope.DeleteData = data;
            $('#deteteProduct').modal('show');
        }
        // delete data
        $scope.deleteProduct = function () {
            var reqData = {
                id: $scope.DeleteData.id.toString(),
            }
            $http.post("{{url('/')}}/delete-product", reqData).then(response => {
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else{
                    $('#deteteProduct').modal('hide');
                }
                $scope.getProducts();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
    });        
</script>
@endsection