<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\Admin\Subscriber;

class SubscribersExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $subscribers = Subscriber::where('is_subscribed', getConstant('SUBSCRIBED'))->get();

        return view('admin.subscribers.export_excel', compact('subscribers'));
    }
}
