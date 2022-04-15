<div class="modal fade" tabindex="-1" id="slug-modal">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h2 class="modal-title">Edit Slug
               <div class="spinner-border spinner-border-sm text-dark ms-5 custom-loader d-none" role="status">
                  <span class="visually-hidden">Loading...</span>
               </div>
            </h2>

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
                  <form method="post" action="{{route('products.updateSlug', ['id' => $product->id])}}" onsubmit="return handleSlugUpdate(this, event.preventDefault());">
                     @csrf
                     @php
                     $split_string = explode('-', $product->slug);
                     unset($split_string[count($split_string)-1]);
                     $slug = implode('-', $split_string);
                     @endphp
                     <div class="row">
                        <div class="mb-5 col-md-12">
                           <label class="form-label required" for="slug">Slug</label>
                           <input type="text" class="form-control form-control-solid" id="slug" name="slug" value="{{$slug}}" required>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12 text-end">
                           <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>