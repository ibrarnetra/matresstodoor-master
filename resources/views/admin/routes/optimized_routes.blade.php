@if ($optimized_routes->status)
<table class="table table-sm table-row-bordered table-striped table-row-gray-300 border gs-3 gy-3">
   <thead>
      <tr class="fw-bolder fs-6 text-gray-800">
         <th style="width: 8%">Index</th>
         <th style="width: 78%">Address</th>
         <th style="width: 20%">Distance</th>
      </tr>
   </thead>
   <tbody>
      @if ($optimized_routes->data->route)
      @foreach ($optimized_routes->data->route as $key => $route)
      <tr>
         <td>{{$key}}</td>
         <td>{{$route->name}}</td>
         <td>{{$route->distance}}</td>
      </tr>
      @endforeach
      @else
      <tr>
         <td colspan="10" class="text-center"><strong>No Data Found...</strong></td>
      </tr>
      @endif
   </tbody>
</table>
@else
<p>The information given for route optimization is incorrect, please contact admin for further details.</p>
@endif