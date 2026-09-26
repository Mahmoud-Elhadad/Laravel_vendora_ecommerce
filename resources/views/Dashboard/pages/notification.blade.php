@extends("Dashboard.layout.main")

@section("body")



      <main class="main-content" id="main-content">
        <div class="page-header">
          <div class="page-header-text">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb page-breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route("vendora.index") }}"><i class="fa-solid fa-house-chimney"></i></a></li>
        <li class="breadcrumb-item" aria-current="false">Administration</li>
        <li class="breadcrumb-item active" aria-current="page">Notifications</li>
        </ol>
      </nav>
            <h1 class="page-title">Notifications</h1>
          </div>

        </div>


        <div class="row g-3 mb-3">
          <div class="col-6 col-xl-6">
            <div class="info-card">
              <span class="info-card-icon bg-primary-subtle text-primary"><i class="fa-solid fa-bell"></i></span>
              <span class="info-card-body">
                <span class="info-card-label">Notifications</span>
                <span class="info-card-value">{{ $num_all }}</span>
                <span class="info-card-sub">{{ $num_unread }} unread</span>
              </span>
            </div>
          </div>
          <div class="col-6 col-xl-6">
            <div class="info-card">
              <span class="info-card-icon bg-warning-subtle text-warning"><i class="fa-solid fa-envelope"></i></span>
              <span class="info-card-body">
                <span class="info-card-label">Unread</span>
                <span class="info-card-value">{{ $num_unread }}</span>
                <span class="info-card-sub">need your attention</span>
              </span>
            </div>
          </div>


        </div>



        <div class="row g-3">


          <div class="col-12 col-xl-12">
            <div class="card h-100">
              <div class="card-header">
                <div class="card-header-title">
                  <h2 class="card-title">Customer Messages</h2>
                  <p class="card-subtitle mb-0">Support requests routed from the storefront</p>
                </div>
              </div>
              <div class="card-body p-0">

                @foreach ($notifications as $notify)

                    <div class="notify-row {{ $notify->read_at ? '' : 'unread' }}">
                    <div class="notify-row-body">
                        <div class="notify-row-head">
                        <span class="notify-row-time">{{ $notify->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="notify-row-text">{{ $notify->data["content"] }}</p>
                    </div>
                    @if (!$notify->read_at)
                        <form action="{{ route('admin.notify.read', $notify->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-subtle"><i class="fa-solid fa-check-double me-2"></i>Mark this read</button>
                        </form>
                    @else
                        <span class="text-body-tertiary small"><i class="fa-solid fa-check-double me-1"></i>Read</span>
                    @endif
                    </div>
                @endforeach



              </div>
            </div>
          </div>
        </div>
      </main>



@endsection
