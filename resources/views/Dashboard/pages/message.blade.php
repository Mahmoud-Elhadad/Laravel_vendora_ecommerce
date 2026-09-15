@extends("Dashboard.layout.main")


@section("body")

     <div class="page-header m-3">
        <div class="page-header-text">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb page-breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route("vendora.index") }}"><i class="fa-solid fa-house-chimney"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Customers</li>
            <li class="breadcrumb-item active" aria-current="page">Messages</li>
            </ol>
        </nav>
            <h1 class="page-title mt-3">Messages</h1>
        </div>

    </div>


                <div class="table-responsive">

                    <div class="alert alert-danger ms-3 me-3">Number of unseen messages : <span class="unseen_ms">{{ $unseen_messages }}</span></div>

                    <table class="table table-hover align-middle mb-0 table-mobile-cards">
                            <thead>
                                <tr>

                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">View</th>

                                    <th scope="col">Message</th>
                                    <th class="table-actions" scope="col">Delete</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($messages as $key => $user_message)

                                    <tr data-status="active" data-role="Super Admin">
                                        <td class="table-check">{{ ++$key }}</td>

                                        <td><span class=" text-capitalize">{{ $user_message->name }}</span></td>
                                        <td  class="has-entity" >{{ $user_message->email }}</td>
                                        <td class="text-capitalize">{{ $user_message->subject }}</td>
                                        <td class="text-capitalize _view"><?= $user_message->view == 0 ? "unseen" : "seen" ?></td>

                                            <td>
                                                @include("Dashboard.layout.modal_showMessage")
                                            </td>

                                            <td>
                                                <form action="{{ route("delete.message" , $user_message->id) }}" method = "post">
                                                    @csrf
                                                    @method("delete")

                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fa-regular fa-trash-can me-1"></i>
                                                    </button>
                                                </form>
                                            </td>

                                    </tr>
                                @endforeach



                            </tbody>
                    </table>

                </div>

@endsection
