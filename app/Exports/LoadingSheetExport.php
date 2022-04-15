<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use App\Models\Admin\Order;
use App\Models\Admin\Option;
use App\Models\Admin\LoadingSheet;
use App\Models\Admin\LoadingSheetItemOption;

class LoadingSheetExport implements FromView, ShouldAutoSize
{
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        // ### PARAMS ###
        // $delivery_date_range = (isset($this->request->delivery_date_range) && !is_null($this->request->delivery_date_range)) ? $this->request->delivery_date_range : "-1";

        // if ($delivery_date_range != '-1') {
        //     $split_delivery_date = explode(' to ', $delivery_date_range);
        // }
        // ### INIT QUERY ###
        // $query = Order::with([
        //     'order_status' => function ($q) {
        //         $q->select('id', 'name');
        //     },
        //     'order_products' => function ($q) {
        //     },
        //     'order_products.product.discount' => function ($q) {
        //     },
        //     'order_products.order_options' => function ($q) {
        //     },
        //     'order_options.product_option_value' => function ($q) {
        //     },
        // ])
        //     ->where('is_deleted', getConstant('IS_NOT_DELETED'));
        // ### ORDER DELIVERY DATE RANGE FILTER ###
        // if ($delivery_date_range != '-1') {
        //     $query->whereRaw('DATE(delivery_date) BETWEEN "' . $split_delivery_date[0] . '" AND "' . $split_delivery_date[1] . '" ');
        // }
        // ### RESULT ###
        // $orders = $query->orderBy('id', 'DESC')
        //     ->get();

        // $options = Option::select('id', 'type', 'sort_order', 'status', 'is_deleted')->with([
        //     'eng_description' => function ($q) {
        //         $q->select('option_id', 'language_id', 'name');
        //     }
        // ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
        //     ->get();

        // return view('admin.orders.export_loading_sheet', compact('orders', 'options'));

        $options = Option::select('id', 'type', 'sort_order', 'status', 'is_deleted')->with([
            'eng_description' => function ($q) {
                $q->select('option_id', 'language_id', 'name');
            }
        ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();

        // return count($options);
        $loading_sheet = LoadingSheet::with([
            'loading_sheet_items' => function ($q) {
                $q->with([
                    'loading_sheet_item_options',
                ]);
            },
        ])->where('id', $this->request->loading_sheet_id)->first();

        /**
         * Preventing `order` loading sheet items duplication
         */
        $item_all = [];
        if (count($loading_sheet->loading_sheet_items) > 0) {
            for ($i = 1; $i < 6; $i++) {
                foreach ($loading_sheet->loading_sheet_items as $loading_sheet_item) {
                    $structured = true;
                    /**
                     * generating product name
                     */
                    $product_name = $loading_sheet_item->name;

                    if (count($loading_sheet_item->loading_sheet_item_options) > 0) {

                        foreach ($loading_sheet_item->loading_sheet_item_options as $loading_sheet_item_option) {
                            if ($loading_sheet_item_option->value == 'King' && $i == '1') {
                                $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all);
                            } else if ($loading_sheet_item_option->value == 'Queen' && $i == '2') {
                                $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all);
                            } else if ($loading_sheet_item_option->value == 'Double' && $i == '3') {
                                $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all);
                            } else if ($loading_sheet_item_option->value == 'Single' && $i == '4') {
                                $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all);
                            }
                        }
                    }
                    if ($loading_sheet_item->loading_sheet_item_options->isEmpty() && $i == 5) {
                        $product_name = $loading_sheet_item->name;
                        if (array_search($product_name, array_column($item_all, 'product_name')) !== FALSE) {
                            $item_all[array_search($product_name, array_column($item_all, 'product_name'))]['quantity'] += $loading_sheet_item->quantity;
                        } else {
                            $temps['product_name'] =   $product_name;
                            $temps['name'] =  $product_name;
                            $temps['option'] = "";
                            $temps['quantity'] =   $loading_sheet_item->quantity;
                            $item_all[] = $temps;
                        }
                    }
                }
            }
        }


        return view('admin.loading_sheets.excel_template', compact('loading_sheet', 'options', 'item_all'));
    }

    protected function sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, &$item_all)
    {
        $product_name = $loading_sheet_item->name;
        $name = $loading_sheet_item->name;
    
        $loading_sheet_item_options = LoadingSheetItemOption::where('loading_sheet_item_id',$loading_sheet_item->id)->get();
        foreach($loading_sheet_item_options as $loading_sheeting_item_option)
        {
            $product_name .= "<br> - " . $loading_sheeting_item_option->name . ": " . $loading_sheeting_item_option->value;
            if($loading_sheeting_item_option->name != 'Size')
            {
                $name .="(".$loading_sheeting_item_option->name.":".$loading_sheeting_item_option->value.")";
            }
        }
        if (array_search($product_name, array_column($item_all, 'product_name')) !== FALSE) {
            $item_all[array_search($product_name, array_column($item_all, 'product_name'))]['quantity'] += $loading_sheet_item->quantity;
        } else {

            $temps['product_name'] =   $product_name;
            $temps['name'] = $name;
            $temps['option'] = $loading_sheet_item_option->value;
            $temps['quantity'] =   $loading_sheet_item->quantity;
            $item_all[] = $temps;
        }
    }
}
