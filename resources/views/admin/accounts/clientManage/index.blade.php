@extends('layouts.app')

@section('title', 'Client Manage')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="fw-bold">Client</h4>
                    </div>
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="modal-product-search d-flex flex-wrap">
                                <form class="me-3 position-relative">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="exampleInputText"
                                            placeholder="Search Client">
                                        <a class="search-link" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="" width="20"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-block card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3 pb-md-0">
                                    <h5 class="fw-bold">Client List</h5>
                                    <button class="btn btn-secondary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="20"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Export
                                    </button>
                                </div>
                                <div class="table-responsive iq-invoice-table">
                                    <table class="table data-table mb-0">
                                        <thead class="table-color-heading">
                                            <tr class="text-light">
                                                <th><label class="text-muted m-0">ID</label></th>
                                                <th><label class="text-muted mb-0">Client Name</label></th>
                                                <th><label class="text-muted mb-0">Phone</label></th>
                                                <th><label class="text-muted mb-0">Ranking</label></th>
                                                <th><label class="text-muted mb-0">Status</label></th>
                                                <th class="text-start"><span class="text-muted">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($clients as $client)
                                                <tr class="white-space-no-wrap">
                                                    <td>{{ $client->id }}</td>    
                                                    <td class="">
                                                        <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                            <div class="h-avatar is-medium">
                                                                <img class="avatar rounded-circle" alt="user-icon"
                                                                    src="{{ asset('storage/' . $client->image) }}"
                                                                    onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010'">
                                                               </div>
                                                            <div class="data-content">
                                                                <div>
                                                                    <span class="fw-bold">{{ $client->name }}</span>
                                                                </div>
                                                                <p class="m-0 text-secondary small">
                                                                    {{ $client->email }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $client->phone }}</td>
                                                    <td>{{ $client->ranking->name }}</td>  
                                                    <td>{{ $client->status ? 'Active' : 'Inactive' }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-start align-items-center">

                                                            <!-- View -->
                                                            <a class="" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="View" 
                                                                href="{{ route('admin.accounts.client-manage.show', $client->id) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="text-secondary me-4" width="20"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                            </a>

                                                            <!-- Edit -->
                                                            <a class="" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Edit"
                                                                href="{{ route('admin.accounts.client-manage.edit', $client->id) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="text-secondary me-4" width="20"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                </svg>
                                                            </a>

                                                            <!-- Delete -->
                                                            <form
                                                                action="{{ route('admin.accounts.client-manage.destroy', $client->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Bạn có chắc chắn muốn xoá client này không?');"
                                                                style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-icon text-danger"
                                                                    data-bs-toggle="tooltip" title="Delete">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
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
@endsection
