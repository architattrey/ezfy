<?php
namespace App\Exports;
use App\models\UserTransaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoiceExportView implements FromView
{
   public function view(): View
   {
      return view('admin.transaction_export_view', [
         'transactions'=> UserTransaction::with(['getManager','getUser','getInstitute'])->get()
      ]);
   }
}
