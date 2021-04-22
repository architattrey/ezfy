@extends('admin.layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" ng-app="transactionApp" ng-controller="transactionController">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transactions</li>
        </ol>
    </section>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="col-sm-6" id="search_div">
                <button type="button" class="btn btn-success" id="search_button"><i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp; Search</button><input type="text" id="search" placeholder="&nbsp; Seach By Any.." ng-model="search">
            </div>
            <div class ="col-sm-6">
            <a href="{{route('download-file')}}" style="float:right;margin-top:37px;"><button type="button" class="btn btn-info"><i class="fa fa-file-text" aria-hidden="true"></i> &nbsp; Export Transaction Data</button></a>
            </div>
            <div class="back-bg" style="background-color:#fff; height: 64px; margin-top: 20px;">
                
            </div>
        </div>
    </div>
    <div class="row">
    </div>
    <!-- view list of transactions -->
    <!-- Main content -->
    <section class="content">
        <table id="categories" datatable="ng" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Number</th>
                    <th>Date Of Transaction</th>
                    <th>Dlvry Placed</th>
                    <th>Dlvry Started</th>
                    <th>Dlvry Washed</th>
                    <th>Dlvry Delivered</th>
                    <th>Name Of user</th>
                    <th>Name Of Exicutive</th>
                    <th>Number Of Clothes</th>
                    <th>Institute Name</th>
                    <th>Transaction Type</th>
                    
                </tr>
            </thead>
            <tbody ng-repeat="transaction in transactionData  | filter:search">
                <tr>
                    <td>@{{$index+1}}</td>
                    <td>@{{transaction.order_id}}</td>
                    <td>@{{transaction.created_at|limitTo:10}}</td>
                    <td>@{{transaction.dlvry_placed}}</td>
                    <td>@{{transaction.dlvry_started}}</td>
                    <td>@{{transaction.dlvry_washed}}</td>
                    <td>@{{transaction.dlvry_delivered}}</td>
                    <td>@{{transaction.get_user.name}}</td>
                    <td>@{{transaction.get_manager.name}}</td>
                    <td>@{{transaction.no_of_clothes}}</td>
                    <td>@{{transaction.get_institute.institute}}</td>
                    <td ng-show="@{{transaction.transaction_type=='credit'}}"><span class="label label-success">Credit</span></td>
                    <td ng-show="@{{transaction.transaction_type=='debit'}}"><span class="label label-danger">Debit</span></td>
                </tr>
            </tbody>
        </table>
    </section>
    <!-- /.content -->
</div>
@endsection
@section('script')

<script>
    var transactionApp = angular.module("transactionApp", []);
    transactionApp.controller("transactionController", function ($scope, $http) {
        //transactions Listing
        $scope.transactionData = [];
        $scope.getTransactions = function () {
            $http.get("{{url('/')}}/get-all-transactions").then(response => {
                console.log(response.data.response);
                $scope.transactionData = response.data.response;
            }).catch(error => {
                swal("Something went wrong!", "Contact to administrator!", "error");
            });
        };
        $scope.getTransactions();
    });        
</script>
@endsection