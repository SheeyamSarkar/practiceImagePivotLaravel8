@extends('layouts.master')
@section('content')

    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-6">
                <h1 class="dashboard-title">Item</h1>
            </div>
            <div class="col-6 dashboard-title text-end">
                <button data-bs-toggle="modal" data-bs-target="#itemAddModal" class="btn-outline">+ Add Item</button>
            </div>
        </div>
    </div>

    <div class="container-fluid section-padding">
        <div class="row mb-5 g-4">
            <div class="col-12">
                <div class="card summary-card" style="width: 100%">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="">
                                    <table
                                        class="table table-borderless align-middle text-center dashboardTable customTable"
                                        id="itemTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">Category Name</th>
                                                <th scope="col">Sub Category Name</th>
                                                <th scope="col">Item Name</th>
                                                <th scope="col">Asset</th>
                                                <th scope="col">Description</th>
                                                <th scope="col" class="text-center">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if (!empty($items))

                                                @foreach ($items as $item)
                                                    <tr class="item{{ $item->id }}">

                                                        <!-- <td>{{ $item->getCategory ? $item->getCategory->name : 'N/A' }}</td> -->

                                                        <td>{{ $item->catname }}</td>

                                                        <td>{{ $item->getSubCategory ? $item->getSubCategory->name : 'N/A' }}
                                                        </td>
                                                        <td>{{ $item->name }}</td>
                                                        <td>
                                                            <img src="{{ asset('images/' . $item->image_path) }}"
                                                                alt="item Image" style="height: 40px;width: 40px">
                                                        </td>


                                                        <td>{{ $item->description }}</td>

                                                        <td class="actionBtn text-center">
                                                            <button onclick='viewItem({{ $item->id }})'><svg
                                                                    xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" fill="none" viewBox="0 0 24 24">
                                                                    <path fill="#BDBDBD"
                                                                        d="M21.92 11.6C19.9 6.91 16.1 4 12 4s-7.9 2.91-9.92 7.6a1 1 0 000 .8C4.1 17.09 7.9 20 12 20s7.9-2.91 9.92-7.6a1 1 0 000-.8zM12 18c-3.17 0-6.17-2.29-7.9-6C5.83 8.29 8.83 6 12 6s6.17 2.29 7.9 6c-1.73 3.71-4.73 6-7.9 6zm0-10a4 4 0 100 8 4 4 0 000-8zm0 6a2 2 0 110-4 2 2 0 010 4z" />
                                                                </svg>
                                                            </button>
                                                            <button onclick='editItem({{ $item->id }})'><svg
                                                                    xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" fill="none" viewBox="0 0 24 24">
                                                                    <path fill="#BDBDBD"
                                                                        d="M5 18h4.24a1 1 0 00.71-.29l6.92-6.93L19.71 8a1 1 0 000-1.42l-4.24-4.29a1 1 0 00-1.42 0l-2.82 2.83-6.94 6.93a.999.999 0 00-.29.71V17a1 1 0 001 1zm9.76-13.59l2.83 2.83-1.42 1.42-2.83-2.83 1.42-1.42zM6 13.17l5.93-5.93 2.83 2.83L8.83 16H6v-2.83zM21 20H3a1 1 0 100 2h18a1 1 0 000-2z" />
                                                                </svg>
                                                            </button>
                                                            <button onclick='deleteItem({{ $item->id }})'><svg
                                                                    xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" fill="none" viewBox="0 0 24 24">
                                                                    <path fill="#EB5757"
                                                                        d="M10 16.8a1 1 0 001-1v-6a1 1 0 00-2 0v6a1 1 0 001 1zm10-12h-4v-1a3 3 0 00-3-3h-2a3 3 0 00-3 3v1H4a1 1 0 000 2h1v11a3 3 0 003 3h8a3 3 0 003-3v-11h1a1 1 0 100-2zm-10-1a1 1 0 011-1h2a1 1 0 011 1v1h-4v-1zm7 14a1 1 0 01-1 1H8a1 1 0 01-1-1v-11h10v11zm-3-1a1 1 0 001-1v-6a1 1 0 00-2 0v6a1 1 0 001 1z" />
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="modal custom-modal fade" id="itemAddModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="itemAddForm" enctype="multipart/form-data">@csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path fill="#BDBDBD"
                                    d="M13.41 12l6.3-6.29a1.004 1.004 0 10-1.42-1.42L12 10.59l-6.29-6.3a1.004 1.004 0 00-1.42 1.42l6.3 6.29-6.3 6.29a1 1 0 000 1.42.998.998 0 001.42 0l6.29-6.3 6.29 6.3a.999.999 0 001.42 0 1 1 0 000-1.42L13.41 12z" />
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 form-input">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control"
                                    onchange="selectCategory()">
                                    <option value="">Select</option>
                                    @if (!empty($categories))
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-6 form-input">
                                <label for="sub_category_id">Sub Category</label>
                                <select name="sub_category_id" id="sub_category_id" class="form-control">
                                    <option value="">Select</option>
                                    @if (!empty($subcategories))
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-6 form-input">
                                <label for="name">Item Name</label>
                                <input class="form-control" type="text" placeholder="Add Name" name="name">
                            </div>
                            <div class="col-6 form-input">
                                <label for="asset_type_id">Asset Type</label>
                                <select name="asset_type_id" id="asset_type_id" class="form-control">
                                    <option value="">Select</option>
                                    @if (!empty($assets))
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}">{{ $asset->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-12 form-input">
                                <label for="description">Description</label>
                                <textarea name="description" placeholder="Add Description"
                                    class="form-control"></textarea>
                            </div>


                            <!-- Image Add Input Field Start -->
                            <div class="col-12 form-input">
                                <span id="select_image_div">
                                    <div class=' row image_row image_div0'>
                                        <div class="col-10 form-input">
                                            <label for="add_photo">Image</label>
                                            <div class="custom-file error0 program_image0">
                                                <input type="file" name="add_photo[]" id="add_photo0"
                                                    class="custom-file-input error0 dropify" data-errors-position="outside"
                                                    data-allowed-file-extensions='["jpg", "png","jpeg","svg"]'
                                                    data-max-file-size="1.0M" data-height="200" data-width="200"
                                                    data-id='0'>
                                            </div>
                                            <span class="error_msg0 text-danger" style="font-size: 1.8rem;"></span>
                                        </div>
                                        <div class="col-2 form-input">
                                            <div class=" remove_row0" onclick="remove(0)" style="padding-top:20px;">
                                                <button class="btn btn-secondary close_icon_add_form" type="button"
                                                    style="position: absolute;right: 45px;margin-top: 35px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path fill="#BDBDBD"
                                                            d="M13.41 12l6.3-6.29a1.004 1.004 0 10-1.42-1.42L12 10.59l-6.29-6.3a1.004 1.004 0 00-1.42 1.42l6.3 6.29-6.3 6.29a1 1 0 000 1.42.998.998 0 001.42 0l6.29-6.3 6.29 6.3a.999.999 0 001.42 0 1 1 0 000-1.42L13.41 12z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="float-md-right">
                                        <button class="btn btn-secondary add_new_row_btn1" type="button"
                                            style="position: absolute; right: 10px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z">
                                                </path>
                                                <path
                                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </span>
                            </div>
                            <!-- Image Add Input Field End -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="submit_btn">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal custom-modal fade" id="itemEditModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="itemEditForm" enctype="multipart/form-data">@csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path fill="#BDBDBD"
                                    d="M13.41 12l6.3-6.29a1.004 1.004 0 10-1.42-1.42L12 10.59l-6.29-6.3a1.004 1.004 0 00-1.42 1.42l6.3 6.29-6.3 6.29a1 1 0 000 1.42.998.998 0 001.42 0l6.29-6.3 6.29 6.3a.999.999 0 001.42 0 1 1 0 000-1.42L13.41 12z" />
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="hidden_id" name="hidden_id" value="">

                        <div class="row">
                            <div class="col-12 form-input">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="e_category_id" class="form-control">
                                    <option value="">Select</option>
                                    @if (!empty($categories))
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-12 form-input">
                                <label for="sub_category_id">Sub Category</label>
                                <select name="sub_category_id" id="e_sub_category_id" class="form-control">
                                    <option value="">Select</option>
                                    @if (!empty($subcategories))
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-6 form-input">
                                <label for="name">Item Name</label>
                                <input class="form-control" type="text" placeholder="Add Name" id="name" name="name">
                            </div>

                            <div class="col-6 form-input">
                                <label for="asset_type_id">Asset Type</label>
                                <select name="asset_type_id" id="e_asset_type_id" class="form-control">
                                    <option value="">Select</option>
                                    @if (!empty($assets))
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}">{{ $asset->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-12 form-input">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description"
                                    placeholder="Add Description"></textarea>
                            </div>


                            <!-- Edit Image Input Field Start -->
                            <div class="col-12 form-input">
                                <label for="image">Image</label>
                                <span id="e_select_image_div">

                                </span>
                                <div class="float-right">
                                    <button class="btn btn-secondary add_new_row_btn_image_edit" type="button"
                                        style="position: absolute; right: 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-plus-circle" viewBox="0 0 16 16">
                                            <path
                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                            <path
                                                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Edit Image Input Field End -->

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="submit_btn">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal custom-modal view-modal fade" id="itemViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Item Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path fill="#BDBDBD"
                                d="M13.41 12l6.3-6.29a1.004 1.004 0 10-1.42-1.42L12 10.59l-6.29-6.3a1.004 1.004 0 00-1.42 1.42l6.3 6.29-6.3 6.29a1 1 0 000 1.42.998.998 0 001.42 0l6.29-6.3 6.29 6.3a.999.999 0 001.42 0 1 1 0 000-1.42L13.41 12z" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <div class="col-12">
                            <h6>Category Name</h6>
                            <h5 id="v_category_id"></h5>
                        </div>
                        <div class="col-12">
                            <h6>Sub Category Name</h6>
                            <h5 id="v_sub_category_id"></h5>
                        </div>
                        <div class="col-12">
                            <h6>Item Name</h6>
                            <h5 id="v_name"></h5>
                        </div>
                        <div class="col-12">
                            <h6>Asset Type</h6>
                            <h5 id="v_asset_type_id"></h5>
                        </div>

                        <div class="col-12">
                            <h6>Description</h6>
                            <h5 id="v_description"></h5>
                        </div>

                        <div class="col-12">
                            <h6>Asset</h6>
                            <h5 id="v_asset"></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('pageScripts')
    <script>
        var toastMixin = Swal.mixin({
            toast: true,
            title: 'General Title',
            animation: false,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        var config = {
            routes: {
                add: "{!! route('item.store') !!}",
                edit: "{!! route('item.edit') !!}",
                update: "{!! route('item.update') !!}",
                delete: "{!! route('item.destroy') !!}",
                filter: "{!! route('filter') !!}",
                delete_image: "{!! route('asset.delete') !!}",

            }
        };
        $(document).ready(function() {
            // data table
            $('#itemTable').DataTable({
                "ordering": false,
                scrollX: true,
            });
            $(".dropify").dropify();
            //selectCategory();
        });
        // While adding new data this code will generate new row
        var i = 0;
        $(document).on('click', '.add_new_row_btn1', function() {
            var image = $('.image_row').find('#add_photo' + i).val();
            //alert(i);
            if (image === '') {
                //alert(i);
                $('.error_msg' + i).addClass('mt-1').text('This field is required');
                $('.error_msg' + i).show();
            } else {
                ++i;
                var new_i = i - 1;
                $('.error_msg' + new_i).hide();
                //alert('Test');
                $('.image_row:last').after(` <div class='row image_row image_div${i}' >
                <div class="col-10 form-input">
                    <div class="custom-file error${i} program_image${i}">
                        <input type="file" name="add_photo[]" id="add_photo${i}" class="custom-file-input error${i} dropify"
                            data-errors-position="outside" data-allowed-file-extensions='["jpg", "png","jpeg","svg"]'
                            data-max-file-size="1.0M" data-height="200" data-width="200" data-id='${i}'>
                    </div>
                <span class="error_msg${i} text-danger" style="font-size: 1.8rem;"></span>
                </div>
                <div class="col-2 form-input">
                    <div class=" remove_row${i}" onclick="remove(${i})" style="position: absolute; right: 0; padding-top:20px;">
                        <button class="btn btn-secondary close_icon_add_form" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                                <path fill="#BDBDBD" d="M13.41 12l6.3-6.29a1.004 1.004 0 10-1.42-1.42L12 10.59l-6.29-6.3a1.004 1.004 0 00-1.42 1.42l6.3 6.29-6.3 6.29a1 1 0 000 1.42.998.998 0 001.42 0l6.29-6.3 6.29 6.3a.999.999 0 001.42 0 1 1 0 000-1.42L13.41 12z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>`);
                $('#add_photo' + i).dropify();
            }
        });

        // While adding new data this code will remove new row
        function remove(id) {
            var length = $('.image_row').length;
            // alert(length)
            if (length === 1) {
                $(".image_div" + id).empty();
            } else {
                $(".image_div" + id).remove();
            }
        }

        /*Edit Image Input Field End*/
        // While editing existing data this code will generate new row after click add button if you necessary
        var j = 0;
        $(document).on('click', '.add_new_row_btn_image_edit', function() {

            var image = $('.image_row_edit').find('#edit_photo' + j).val();

            if (image === '') {
                $('.error_msg' + j).addClass('mt-1').text('This field is required');
                $('.error_msg' + j).show();
            } else {
                ++j;
                var new_j = j - 1;
                $('.error_msg' + new_j).hide();
                //alert('Image');

                $('.image_row_edit:last').after(` <div class=' row image_row_edit image_edit_new${j}' >
                <div class="col-10 form-input">
                    <div class="custom-file program_image${j}">
                        <input type="file" name="edit_photo[]" id="edit_photo${j}"
                            class="custom-file-input dropify" data-errors-position="outside"
                            data-allowed-file-extensions='["jpg", "png","jpeg","svg"]'
                            data-max-file-size="1.0M" data-height="200" data-width="200"
                            data-id='${j}'>
                    </div>
                    <span class="error_msg${j} text-danger" style="font-size: 1.8rem;"></span>
                </div>
                <div class="col-2 form-input">
                    <div class=" remove_row${j}" onclick="remove_image_edit_row(${j})" style="position: absolute; right: 0; padding-top:20px;">
                        <button class="btn btn-secondary close_icon_add_form" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                                <path fill="#BDBDBD" d="M13.41 12l6.3-6.29a1.004 1.004 0 10-1.42-1.42L12 10.59l-6.29-6.3a1.004 1.004 0 00-1.42 1.42l6.3 6.29-6.3 6.29a1 1 0 000 1.42.998.998 0 001.42 0l6.29-6.3 6.29 6.3a.999.999 0 001.42 0 1 1 0 000-1.42L13.41 12z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>`);
                $('#edit_photo' + j).dropify();
            }
        });

        //while editing if necessary this code will remove certain row from your existing rowdata
        function remove_image_edit(id) {
            var length = $('.image_row_edit').length;
            // alert(length)
            if (length === 1) {
                $(".image_div_edit" + id).empty();
            } else {
                $(".image_div_edit" + id).remove();
            }
        }

        //while editing if necessary this code will remove certain row which you have created newely
        function remove_image_edit_row(id) {
            var length = $('.image_row_edit').length;
            // alert(length)
            if (length === 1) {
                $(".image_edit_new" + id).empty();
            } else {
                $(".image_edit_new" + id).remove();
            }
        }
        /*Edit Image Input Field End*/


        // add form validation
        ///Form validation client side
        // rules:fieldName
        $(document).ready(function() {
            $("#itemAddForm").validate({
                rules: {
                    category_id: {
                        required: true,
                    },
                    sub_category_id: {
                        required: true,
                    },
                    name: {
                        required: true,
                        maxlength: 100,
                    },
                    // 'add_photo[]': {
                    //     required: true,
                    // },
                },
                messages: {
                    category_id: {
                        required: 'Please Insert CategoryName',
                    },
                    sub_category_id: {
                        required: 'Please Insert Sub CategoryName',
                    },
                    name: {
                        required: 'Please Insert Name',
                    },
                    // 'add_photo[]': {
                    //     required: 'Please Insert Image',
                    // },
                },
                errorPlacement: function(label, element) {
                    label.addClass('mt-2 text-danger');
                    label.insertAfter(element);
                },
            });

            $("#itemEditForm").validate({
                rules: {
                    category_id: {
                        required: true,
                    },
                    sub_category_id: {
                        required: true,
                    },
                    name: {
                        required: true,
                        maxlength: 100,
                    },
                },
                messages: {
                    category_id: {
                        required: 'Please Insert CategoryName',
                    },
                    sub_category_id: {
                        required: 'Please Insert Sub CategoryName',
                    },
                    name: {
                        required: 'Please Insert Name',
                    },
                },
                errorPlacement: function(label, element) {
                    label.addClass('mt-2 text-danger');
                    label.insertAfter(element);
                },
            });
        });
        //end
        //end

        var path = "{{ url('/') }}" + '/images/';
        //

        //-----------------------------Add Request----------------------//
        $(document).off('submit', '#itemAddForm');
        $(document).on('submit', '#itemAddForm', function(event) {
            event.preventDefault();
            $.ajax({
                url: config.routes.add,
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(response) {
                    if (response.success == true) {
                        var idea_table = $('#itemTable').DataTable();
                        var trDOM = idea_table.row.add([
                            "" + response.data.category_name + "",
                            "" + response.data.sub_category_id + "",
                            "" + response.data.name + "",
                            "" +
                            `<img width="40px" height="40px" src="${path+response.data.asset}" class="dropify"/>` +
                            "",
                            "" + response.data.description + "",

                            `
                                <button  onclick='viewItem(${response.data.id})'><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#BDBDBD" d="M21.92 11.6C19.9 6.91 16.1 4 12 4s-7.9 2.91-9.92 7.6a1 1 0 000 .8C4.1 17.09 7.9 20 12 20s7.9-2.91 9.92-7.6a1 1 0 000-.8zM12 18c-3.17 0-6.17-2.29-7.9-6C5.83 8.29 8.83 6 12 6s6.17 2.29 7.9 6c-1.73 3.71-4.73 6-7.9 6zm0-10a4 4 0 100 8 4 4 0 000-8zm0 6a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                </button>
                                <button  onclick='editItem(${response.data.id})'><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#BDBDBD" d="M5 18h4.24a1 1 0 00.71-.29l6.92-6.93L19.71 8a1 1 0 000-1.42l-4.24-4.29a1 1 0 00-1.42 0l-2.82 2.83-6.94 6.93a.999.999 0 00-.29.71V17a1 1 0 001 1zm9.76-13.59l2.83 2.83-1.42 1.42-2.83-2.83 1.42-1.42zM6 13.17l5.93-5.93 2.83 2.83L8.83 16H6v-2.83zM21 20H3a1 1 0 100 2h18a1 1 0 000-2z" /></svg>
                                </button>
                                <button onclick='deleteItem(${response.data.id})'><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#EB5757" d="M10 16.8a1 1 0 001-1v-6a1 1 0 00-2 0v6a1 1 0 001 1zm10-12h-4v-1a3 3 0 00-3-3h-2a3 3 0 00-3 3v1H4a1 1 0 000 2h1v11a3 3 0 003 3h8a3 3 0 003-3v-11h1a1 1 0 100-2zm-10-1a1 1 0 011-1h2a1 1 0 011 1v1h-4v-1zm7 14a1 1 0 01-1 1H8a1 1 0 01-1-1v-11h10v11zm-3-1a1 1 0 001-1v-6a1 1 0 00-2 0v6a1 1 0 001 1z" /></svg>
                                </button>
                                `
                        ]).draw().node();
                        $(trDOM).addClass('item' + response.data.id + '');
                        $('table tr:last td:last').addClass('actionBtn text-center');
                        $('#itemAddForm').trigger('reset');
                        if (response.data.message) {
                            $('#itemAddModal').modal('hide');
                            toastMixin.fire({
                                icon: 'success',
                                animation: true,
                                title: "" + response.data.message + ""
                            });

                        }
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: response.data.error,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }, //success end
            });
        });

        //request end
        var path = "{{ url('/') }}" + '/images/';

        function viewItem(id) {

            $.ajax({
                url: config.routes.edit,
                method: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    if (response.success == true) {

                        $('#v_category_id').text(response.data.category_name);
                        $('#v_sub_category_id').text(response.data.get_sub_category.name);
                        $('#v_name').text(response.data.name);
                        $('#v_asset_type_id').text(response.data.type_name);
                        $('#v_asset').empty();
                        $.each(response.data.Image_assets, function(key, value) {
                            $('#v_asset').append(
                                `<img src="${path+value.asset}" class="dropify" style="width:370px; height:200px; padding-bottom:10px;"/>`
                            );
                        });
                        $('#v_description').text(response.data.description);

                        $('#itemViewModal').modal('show');

                    }

                } //success end
            }); //ajax end

        }

        //-----------------------------Edit Methods----------------------//
        var base_url = "{{ url('/') }}";
        var path = "{{ url('/') }}" + '/images/';

        function editItem(id) {

            //alert('test');
            $.ajax({
                url: config.routes.edit,
                method: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    if (response.success == true) {

                        $('#e_category_id').val(response.data.get_sub_category.category_id);
                        $('#e_sub_category_id').val(response.data.sub_category_id);
                        $('#name').val(response.data.name);

                        $('#e_asset_type_id').val(response.data.type_id);
                        $('#description').val(response.data.description);

                        /*Edit Image Start  deleteimagerow()*/
                        $('#e_select_image_div').empty();
                        $.each(response.data.Image_assets, function(key, value) {

                            $('#e_select_image_div').append(`<div class=' row image_row_edit image_div_edit${key}' >
                            
                            <img src="${path+value.asset}" class="dropify" style="width:370px; height:200px; padding-bottom:10px;"/>
                            <div class="col-2 form-input">
                                <div class=" remove_row${value.id}" onclick="remove_image_edit(${key})" style="position: absolute; right: 0; padding-top:20px;">
                                    <button onclick="deleteimagerow(${value.id})" class="btn btn-secondary close_icon_add_form" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                                <path fill="#BDBDBD" d="M13.41 12l6.3-6.29a1.004 1.004 0 10-1.42-1.42L12 10.59l-6.29-6.3a1.004 1.004 0 00-1.42 1.42l6.3 6.29-6.3 6.29a1 1 0 000 1.42.998.998 0 001.42 0l6.29-6.3 6.29 6.3a.999.999 0 001.42 0 1 1 0 000-1.42L13.41 12z"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>`);
                            // $('.program_image' + key).dropify();
                            $('.program_image' + key, value).dropify();
                        });

                        $('#hidden_id').val(response.data.id);
                        $('#itemEditModal').modal('show');

                    }

                } //success end
            }); //ajax end

            //-----------------------------Update----------------------//
            $(document).off('submit', '#itemEditForm');
            $(document).on('submit', '#itemEditForm', function(event) {
                event.preventDefault();
                $.ajax({
                    url: config.routes.update,
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function(response) {

                        if (response.success == true) {
                            $('.item' + response.data.id).replaceWith(`<tr class='item${response.data.id}'>
                            <td>${response.data.category_name}</td>
                            <td>${response.data.sub_category_id}</td>
                           
                            <td>${response.data.name}</td>
                            <td><img width="40px" height="40px" src="${path+response.data.image_list[0]}" class="dropify"/></td>
                            <td>${response.data.description}</td>
                            <td class="actionBtn text-center">
                            <button  onclick='viewItem(${response.data.id})'><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#BDBDBD" d="M21.92 11.6C19.9 6.91 16.1 4 12 4s-7.9 2.91-9.92 7.6a1 1 0 000 .8C4.1 17.09 7.9 20 12 20s7.9-2.91 9.92-7.6a1 1 0 000-.8zM12 18c-3.17 0-6.17-2.29-7.9-6C5.83 8.29 8.83 6 12 6s6.17 2.29 7.9 6c-1.73 3.71-4.73 6-7.9 6zm0-10a4 4 0 100 8 4 4 0 000-8zm0 6a2 2 0 110-4 2 2 0 010 4z" /></svg>
                            </button>
                            <button  onclick='editItem(${response.data.id})'><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#BDBDBD" d="M5 18h4.24a1 1 0 00.71-.29l6.92-6.93L19.71 8a1 1 0 000-1.42l-4.24-4.29a1 1 0 00-1.42 0l-2.82 2.83-6.94 6.93a.999.999 0 00-.29.71V17a1 1 0 001 1zm9.76-13.59l2.83 2.83-1.42 1.42-2.83-2.83 1.42-1.42zM6 13.17l5.93-5.93 2.83 2.83L8.83 16H6v-2.83zM21 20H3a1 1 0 100 2h18a1 1 0 000-2z" /></svg>
                            </button>
                            <button onclick='deleteItem(${response.data.id})'><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#EB5757" d="M10 16.8a1 1 0 001-1v-6a1 1 0 00-2 0v6a1 1 0 001 1zm10-12h-4v-1a3 3 0 00-3-3h-2a3 3 0 00-3 3v1H4a1 1 0 000 2h1v11a3 3 0 003 3h8a3 3 0 003-3v-11h1a1 1 0 100-2zm-10-1a1 1 0 011-1h2a1 1 0 011 1v1h-4v-1zm7 14a1 1 0 01-1 1H8a1 1 0 01-1-1v-11h10v11zm-3-1a1 1 0 001-1v-6a1 1 0 00-2 0v6a1 1 0 001 1z" /></svg>
                            </button>
                            </td></tr>`)
                            if (response.data.message) {
                                $('#itemEditModal').modal('hide');
                                toastMixin.fire({
                                    icon: 'success',
                                    animation: true,
                                    title: response.data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                $('#itemEditForm').trigger('reset');
                            }

                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'warning',
                                title: response.data.error,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }, //success end
                });
            });
        }

        // // delete

        function deleteItem(id) {
            // alert(id)
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: config.routes.delete,
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: 'JSON',
                        success: function(response) {

                            if (response.success === true) {
                                toastMixin.fire({
                                    icon: 'success',
                                    animation: true,
                                    title: "" + response.data.message + ""
                                });

                                $('#itemTable').DataTable().row('.item' + response.data.id).remove()
                                    .draw();
                            } else {
                                Swal.fire("Error!", "Can't delete item", "error");
                            }
                        }
                    });
                }
            })
        }

        function selectCategory() {
            var category_id = $('#category_id').val();
            //alert(category_id);
            $.ajax({
                url: config.routes.filter,
                type: "POST",
                data: {
                    category_id: category_id,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(result) {

                    $('select[name="sub_category_id"]').empty();
                    $('select[name="sub_category_id"]').append('<option value="">Select</option>');
                    $.each(result, function(key, value) {
                        $('select[name="sub_category_id"]').append('<option value="' + value.id + '">' +
                            value.name + '</option>');
                    });
                }
            })
        }

        //end

        function deleteimagerow(id) {

            //alert(id);
            $.ajax({
                type: "POST",
                url: config.routes.delete_image,
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.success === true) {

                        $('#itemTable').DataTable().row('.item' + response.data.id).remove()
                            .draw();
                    } else {
                        Swal.fire("Error!", "Can't delete item", "error");
                    }
                }
            });
        }
    </script>
@endsection
