 
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Manager Name</th>
            <th>Manager Email</th>
            <th>User Name</th>
            <th>User Contact Number</th>
            <th>User Email</th>
            <th>User Address</th>
            <th>Institute</th>
            <th>Product</th>
            <th>Plan</th>
            <th>Price</th>
            <th>Order Id</th>
            <th>Invoice Id</th>
            <th>Transaction Amount</th>
            <th>No Of Clothes</th>
            <th>Transaction Status</th>
            <th>User Type</th>
            <th>Order Placed</th>
            <th>Order Started</th>
            <th>Order Washed</th>
            <th>Order Delivered</th>
            <th>Start Type</th>
            <th>Remaining Washes</th>
            <th>Transaction Type</th>
            <th>Plan Expire Date</th>
        </tr>
    </thead> 
    <tbody>
    @foreach($transactions as $key => $transaction) 
        @php 
            $key++;
        @endphp
        @php
        if($transaction->status=='Fail'){
            $backgroundColour = '#f7b0ab';
        }else{
            $backgroundColour = '#def9de';
        }
        @endphp
        <tr style="background:{{isset($backgroundColour)?$backgroundColour:''}};">
            <td>{{$key}}</td>
            <td>{{$transaction->getManager['name']}}</td>
            <td>{{$transaction->getManager['email']}}</td>
            <td>{{$transaction->getUser['name']}}</td>
            <td>{{$transaction->getUser['phone_number']}}</td>
            <td>{{$transaction->getUser['mail_id']}}</td>
            <td>{{$transaction->getUser['address']}}</td>
            <td>{{$transaction->getInstitute['institute']}}</td>
            <td>{{$transaction->getProduct['products']}}</td>
            <td>{{$transaction->getProduct['plan']}}</td>
            <td>{{$transaction->getProduct['price']}}</td>
            <td>{{$transaction->order_id}}</td>
            <td>{{$transaction->invoice_id}}</td>
            <td>{{$transaction->amount}}</td>
            <td>{{$transaction->no_of_clothes}}</td>
            <td>{{$transaction->status}}</td>
            <td>{{$transaction->user_type}}</td>
            <td>{{$transaction->dlvry_placed}}</td>
            <td>{{$transaction->dlvry_started}}</td>
            <td>{{$transaction->dlvry_washed}}</td>
            <td>{{$transaction->dlvry_delivered}}</td>
            <td>{{$transaction->start_type}}</td>
            <td>{{$transaction->remaining_washes}}</td>
            <td>{{$transaction->transaction_type}}</td>
            <td>{{date('d-m-Y',strtotime($transaction->expire_date))}}</td>
            <td>{{date('d-m-Y',strtotime($transaction->created_at))}}</td>
        </tr>
    @endforeach    
    </tbody>    
</table>