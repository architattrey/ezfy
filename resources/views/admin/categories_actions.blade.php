@extends('admin.layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" ng-app="categoryApp" ng-controller="categoryController">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Categories Section</li>
        </ol>
        </section>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="col-sm-6" id="search_div">
                    <button type="button" class="btn btn-success" id="search_button"><i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp; Search</button><input type="text" id="search" placeholder="&nbsp; Seach By Any.." ng-model="search">
                </div>
                <div class="back-bg" style="background-color:#fff; height: 64px; margin-top: 20px;">
                <a style="margin-top: 5px; padding: 10px 17px; float: right;b margin-right: 17px;"><button type="button" class="btn btn-primary" id="flip" href="" ng-click="addOpen()">Add More Categories</button></a>
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
                        <th width="5%">Categories</th>
                        <th width="10%">Image</th>
                        <th width="10%">Created At</th>
                        <th width="10%">Updated At</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody ng-repeat ="category in categoryData | filter:search">
                    <tr>
                        <td>@{{$index+1}}</td>
                        <td>@{{category.category}}</td> 
                        <td><img src ="@{{imageUrl}}@{{category.image}}" style="width:33%;"></td>
                        <td>@{{category.created_at|limitTo:10}}</td>
                        <td>@{{category.updated_at|limitTo:10}}</td>
                        <td>
                            <button type="button" class="btn btn-success"><a href="" ng-click="update(category)"><i class="fa fa-pencil" style="font-size:16px;color:white" aria-hidden="true"></i></a></button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-danger"><a href=""  ng-click="deleteModel(category)"><i class="fa fa-trash" style="font-size:16px;color:white" aria-hidden="true"></i></a></button> 
                        </td>
                    </tr>
                </tbody> 
            </table>
        </section>    
        <!-- /.content -->
        <!-- delete model -->
        <div class="modal fade" id="deteteCat" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Do you want to Delete</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" ng-click="deleteCategory()">Delete</button>
                        {{ Form::button('Cancel',['class'=>'btn btn-default','data-dismiss'=>'modal']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal close -->
         
        <!-- add model -->
        <div class="modal fade" id="AddCategory" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Do you want to Add Category</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- col-sm-6 -->
                            <div class="col-sm-6">
                                <!-- add cat -->
                                <div class="form-group">
                                    <label for="">Add Category</label>
                                    <input type="text" class="form-control" ng-model="Category" placeholder ="Add Category" required><br>
                                </div>
                                 
                            </div>    
                            <!--/ col-sm-6 -->
                            <!-- col-sm-6 -->
                            <div class="col-sm-6">
                                
                                <!-- Product Image -->
                                <div class="form-group">
                                    <label for="">Category Image</label>
                                    <input type="file" class="form-control" id="category_image" accept="image/*"onchange="angular.element(this).scope().uploadedFile(this)"placeholder="Select Category Image" /><br>
                                </div>
                                <!-- image show div -->
                                <img ng-src="@{{baseUrl}}@{{categoryImage}}" style="width:164px; height:139px;"/>
                            </div>
                            <!--/ col-sm-6 -->
                             
                        </div>    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" ng-click="addcategory()">Success</button>
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
                            <!-- add cat -->
                            <div class="form-group">
                                <label for="">Add Category</label>
                                <input type="text" class="form-control" ng-model="Category" placeholder ="Add Category" required><br>
                            </div>
                             
                        </div>    
                        <!--/ col-sm-6 -->
                        <!-- col-sm-6 -->
                        <div class="col-sm-6">
                             
                            <!-- Product Image -->
                            <div class="form-group">
                                <label for="">Category Image</label>
                                <input type="file" class="form-control" id="category_image" accept="image/*"onchange="angular.element(this).scope().uploadedFile(this)"placeholder="Select Category Image" /><br>
                            </div>
                            <!-- image show div -->
                            <img ng-src="@{{baseUrl}}@{{categoryImage}}" style="width:164px; height:139px;"/>
                        </div>
                        <!--/ col-sm-6 -->
                             
                    </div> 
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" ng-click="updatecategory()">Update</button>
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
    var categoryApp = angular.module("categoryApp",[]);
    
    categoryApp.controller("categoryController",function($scope, $http) {
        // categories listing   
        //$scope.dtOptions = DTOptionsBuilder.newOptions().withOption('order', [0, 'asc']);
        $scope.categoryData = [];
        $scope.getRequest = function() {
            $http.post("{{url('/')}}/get-categories").then(response =>{
                
                $scope.categoryData = response.data.data.categories;
                $scope.imageUrl = response.data.base_url;
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        $scope.getRequest();
        // upload image
        $scope.uploadFile = function (files) {
           //debugger;
           var file = files;
           var uploadUrl = "{{url('/')}}/image-upload";
           var fd = new FormData();
           fd.append('category_image', file);
           $http.post(uploadUrl, fd, {
               transformRequest: angular.identity,
               headers: { 'Content-Type': undefined }
           })
           .then(res => {
               
               $scope.categoryImage = res.data.image_url;
               $scope.baseUrl = res.data.base_url;
           })
           .catch(err => {
                swal("Something went wrong!", "Contact to administrator!", "error");
           });
       };
        $scope.uploadedFile = function(element) {
            $scope.currentFile = element.files[0];
            var reader = new FileReader();

            reader.onload = function(event) {
                $scope.categoryImage = event.target.result
                $scope.$apply(function($scope) {
                    $scope.files = event.target.result;
                    $scope.uploadFile(event.target.result);
                });
            }
            reader.readAsDataURL(element.files[0]);
        }

        // add category
        $scope.addOpen = function() {
            $scope.Category="";
            $scope.categoryImage="";
            $('#AddCategory').modal('show');
        }
        $scope.addcategory = function() {
            var reqData={
                categories:$scope.Category,
                image:$scope.categoryImage,
            }
            $http.post("{{url('/')}}/add-update",reqData).then(response =>{
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else{
                    $('#AddCategory').modal('hide');
                }
                $scope.getRequest();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        // update category
        $scope.update = function(data){
            $scope.updateData= data;
            $scope.Category  = $scope.updateData.category;
            $scope.categoryImage = $scope.updateData.image;
            $('#myModal').modal('show');
        }
        $scope.updatecategory = function() {
            var reqData={
                id:$scope.updateData.id,
                categories:$scope.Category,
                image:$scope.categoryImage
            }
            $http.post("{{url('/')}}/add-update",reqData).then(response =>{
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else{
                    $('#myModal').modal('hide');
                }
            $scope.getRequest();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        // delete category
        $scope.deleteModel = function(data){
            $scope.updateData = data;
            $('#deteteCat').modal('show');
		}
        $scope.deleteCategory = function() {
            var reqData={
                id:$scope.updateData.id.toString(),
            }
            $http.post("{{url('/')}}/delete-category",reqData).then(response =>{
                if(response.data.status=="error"){
                    swal("Something went wrong!", "Contact to administrator!", "error"); 
                }else{
                    $('#deteteCat').modal('hide');
                }
                $scope.getRequest();
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
    });
</script>
@endsection	
 
