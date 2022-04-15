<table border="" cellpadding="8" style="border-spacing: 0px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">
   <thead>
      <tr>
         <th><strong>#</strong></th>
         <th><strong>Email</strong></th>
         <th><strong>Subscription Date</strong></th>
      </tr>
   </thead>

   <tbody>
      @if (count($subscribers))
      @foreach ($subscribers as $key => $subscriber)
      <tr>
         <td>
            {{$key + 1}}
         </td>
         <td>
            {{$subscriber->email}}
         </td>
         <td>
            {{formatGivenDateTime($subscriber->created_at, getConstant("DATE_DB_FORMAT"))}}
         </td>
      </tr>
      @endforeach
      @else
      <tr>
         <td colspan="3">No data found...</td>
      </tr>
      @endif
   </tbody>
</table>