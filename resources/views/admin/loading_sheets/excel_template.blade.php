<table border="" cellpadding="8"
    style="border-spacing: 0px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">
    @php
        $total_options = count($options);
        $a = 0;
    @endphp

    @if ($loading_sheet)

        <thead>
            <tr>
                <th colspan="{{ 2 + $total_options }}" rowspan="2" style="text-align: center;">
                    <strong>{{ date('F jS, Y', strtotime($loading_sheet->created_at)) }}</strong>
                </th>
            </tr>
            <tr>
                <th colspan="{{ 2 + $total_options }}"></th>
            </tr>
            <tr>
                <th><strong>Product</strong></th>
                <th><strong>Size</strong></th>
                <th><strong>Quantity</strong></th>
            </tr>
        </thead>

        <tbody>
            @if (count($item_all) > 0)
                @foreach ($item_all as $loading_sheet_item)
                    @php
                        $a++;
                    @endphp
                    <tr>
                        <td>
                            {{ $loading_sheet_item['name'] }}
                        </td>
                        <td>
                            {{ $loading_sheet_item['option'] }}
                        </td>
                   
                        <td style="text-align: right;">{{ $loading_sheet_item['quantity'] }}</td>
                    
                      
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="{{ 2 + $total_options }}">No Data found...</td>
                </tr>
            @endif
        </tbody>

    @endif
</table>
