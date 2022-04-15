<div class="modal fade" tabindex="-1" id="orders-modal">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h2 class="modal-title">Orders</h2>

            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
               <span class="svg-icon svg-icon-2x">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                     <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">
                        <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1"></rect>
                        <rect fill="#000000" opacity="0.5" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)" x="0" y="7" width="16" height="2" rx="1">
                        </rect>
                     </g>
                  </svg>
               </span>
            </div>
            <!--end::Close-->
         </div>

         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <!--begin::Table-->
                  <table id="generic-datatable" class="table table-striped table-sm gy-2 gs-2 align-middle">
                     <thead>
                        <tr class="fw-bolder fs-6 text-gray-800">
                           <th class="min-w-20px">
                              <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                 <input class="form-check-input multi-dispatch-checkbox" type="checkbox" data-kt-check="true" data-kt-check-target="#generic-datatable .form-check-input" />
                              </div>
                           </th>
                           <th class="min-w-100px">Order #</th>
                           <th class="min-w-150px">Customer</th>
                           <th class="min-w-150px">Telephone</th>
                           <th class="min-w-150px">City</th>
                           <th class="min-w-400px">Address</th>
                           <th class="min-w-150px">Total</th>
                           <th class="min-w-150px">Date Added</th>
                           <th class="min-w-150px">Delivery Date</th>
                           <th class="min-w-150px">Status</th>
                           <th class="min-w-100px">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
                  <!--end::Table-->
               </div>
            </div>

            @if (Auth::guard('web')->user()->hasRole("Super Admin") ||
            Auth::guard('web')->user()->hasRole("Dispatch Manager") ||
            Auth::guard('web')->user()->hasRole("Office Admin") ||
            Auth::guard('web')->user()->hasRole("Delivery Manager"))
            <div class="row mt-3">
               <div class="col-md-12 text-end">
                  <button type="button" class="btn btn btn-info btn-sm" id="assign-route" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top"
                     title="Select Order(s) to generate Route." onclick="loadRouteOrdersModal('{{route('routes.checkOrdersRoutes')}}')">
                     <i class="fas fa-route"></i> Create Route
                  </button>
               </div>
            </div>
            @endif

         </div>
      </div>
   </div>
</div>