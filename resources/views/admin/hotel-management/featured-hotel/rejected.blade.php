@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Rejected') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Hotels Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Featured Hotels') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Requests') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Rejected') }}</a>
      </li>
    </ul>

  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <form id="searchForm" action="{{ route('admin.hotel_management.featured_hotel.rejected_request') }}"
                method="GET">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Order Number') }}</label>
                      <input name="order_no" type="text" id="order_no" class="form-control"
                        placeholder="{{ __('Search Here') . '...' }}"
                        value="{{ !empty(request()->input('order_no')) ? request()->input('order_no') : '' }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Hotel Title') }}</label>
                      <input name="title" type="text" id="hotel_title" class="form-control"
                        placeholder="{{ __('Search Here') . '...' }}"
                        value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Payment Status') }}</label>
                      <select class="form-control h-42" name="payment_status"
                        onchange="document.getElementById('searchForm').submit()">
                        <option value="" {{ empty(request()->input('payment_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="completed"
                          {{ request()->input('payment_status') == 'completed' ? 'selected' : '' }}>
                          {{ __('Completed') }}
                        </option>
                        <option value="pending" {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <div class="col-lg-2">
              <button class="btn btn-danger btn-sm d-none bulk-delete float-lg-right"
                data-href="{{ route('admin.hotel_management.featured_hotel.bulk_delete_order') }}"
                class="card-header-button">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($orders) == 0)
                <h3 class="text-center mt-3">{{ __('NO REQUEST FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-2">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Order No.') }}</th>
                        <th scope="col">{{ __('Hotel Title') }}</th>
                        <th scope="col">{{ __('Paid via') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Days') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $order->id }}">
                          </td>
                          <td>{{ '#' . $order->order_number }}</td>
                          <td class="title">
                            @php
                              $hotel_content = App\Models\HotelContent::Where([
                                  ['hotel_id', $order->hotel_id],
                                  ['language_id', $language->id],
                              ])
                                  ->select('title', 'hotel_id', 'slug')
                                  ->first();
                            @endphp
                            @if (!empty($hotel_content))
                              <a href="{{ route('frontend.hotel.details', ['slug' => $hotel_content->slug, 'id' => $hotel_content->hotel_id]) }}"
                                target="_blank">
                                {{ strlen(@$hotel_content->title) > 60 ? mb_substr(@$hotel_content->title, 0, 60, 'utf-8') . '...' : @$hotel_content->title }}
                              </a>
                            @else
                              --
                            @endif
                          </td>
                          <td>{{ __($order->payment_method) }}</td>
                          <td>
                            @if ($order->gateway_type == 'online')
                              <h2 class="d-inline-block">
                                <span
                                  class="badge badge-{{ $order->payment_status == 'completed' ? 'success' : ($order->payment_status == 'pending' ? 'warning' : 'danger') }}">
                                  {{ __($order->payment_status) }}
                                </span>
                              </h2>
                            @else
                              @if ($order->payment_status == 'pending')
                                <form id="paymentStatusForm-{{ $order->id }}" class="d-inline-block"
                                  action="{{ route('admin.hotel_management.featured_hotel.update_payment_status', ['id' => $order->id]) }}"
                                  method="post">
                                  @csrf
                                  <select
                                    class="form-control form-control-sm @if ($order->payment_status == 'pending') bg-warning text-dark @elseif ($order->payment_status == 'completed') bg-success @else bg-danger @endif"
                                    name="payment_status"
                                    onchange="document.getElementById('paymentStatusForm-{{ $order->id }}').submit()">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>
                                      {{ __('Pending') }}
                                    </option>
                                    <option value="completed"
                                      {{ $order->payment_status == 'completed' ? 'selected' : '' }}>
                                      {{ __('Completed') }}
                                    </option>
                                    <option value="rejected"
                                      {{ $order->payment_status == 'rejected' ? 'selected' : '' }}>
                                      {{ __('Rejected') }}
                                    </option>
                                  </select>
                                </form>
                              @else
                                <h2 class="d-inline-block"><span
                                    class="badge badge-{{ $order->payment_status == 'completed' ? 'success' : 'danger' }}">{{ __($order->payment_status) }}</span>
                                </h2>
                              @endif
                            @endif
                          </td>
                          <td>
                            @if ($order->order_status == 'pending')
                              <form id="orderStatusForm-{{ $order->id }}" class="d-inline-block"
                                action="{{ route('admin.hotel_management.featured_hotel.update_order_status', ['id' => $order->id]) }}"
                                method="post">
                                @csrf
                                <select
                                  class="form-control form-control-sm @if ($order->order_status == 'pending') bg-warning text-dark @elseif ($order->order_status == 'processing') bg-primary @elseif ($order->order_status == 'completed') bg-success @else bg-danger @endif"
                                  name="order_status"
                                  onchange="document.getElementById('orderStatusForm-{{ $order->id }}').submit()">
                                  <option value="pending" selected>{{ __('Pending') }}</option>
                                  <option value="completed">{{ __('Completed') }}</option>
                                  <option value="rejected">{{ __('Rejected') }}</option>
                                </select>
                              </form>
                            @else
                              <h2 class="d-inline-block"><span
                                  class="badge badge-{{ $order->order_status == 'completed' ? 'success' : 'danger' }}">
                                  {{ $order->order_status == 'completed' ? 'approved' : $order->order_status }}
                                </span>
                              </h2>
                            @endif
                          </td>
                          <td>
                            @if ($order->order_status == 'completed')
                              {{ $order->days }}{{ __('Days') }}
                              ({{ \Carbon\Carbon::parse($order->start_date)->format('j F, Y') }} -
                              {{ \Carbon\Carbon::parse($order->end_date)->format('j F, Y') }})
                            @else
                              {{ $order->days }}
                            @endif
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if (!empty($order->attachment))
                                  <a href="#" class="dropdown-item" data-toggle="modal"
                                    data-target="#receiptModal-{{ $order->id }}">
                                    {{ __('Receipt') }}
                                  </a>
                                @endif

                                <a href="#" class="dropdown-item" data-toggle="modal"
                                  data-target="#detailsModal_{{ $order->id }}">
                                  {{ __('Details') }}
                                </a>

                                @if ($order->invoice)
                                  <a href="{{ asset('assets/file/invoices/hotel-feature/' . $order->invoice) }}"
                                    class="dropdown-item" target="_blank">
                                    {{ __('Invoice') }}
                                  </a>
                                @endif


                                <form class="deleteForm d-block"
                                  action="{{ route('admin.hotel_management.featured_hotel.delete', ['id' => $order->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                        </tr>
                        @includeIf('admin.hotel-management.featured-hotel.show-receipt')
                        @includeIf('admin.hotel-management.featured-hotel.details')
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="mt-3 text-center">
            <div class="d-inline-block mx-auto">
              {{ $orders->appends([
                      'order_no' => request()->input('order_no'),
                      'payment_status' => request()->input('payment_status'),
                      'order_status' => request()->input('order_status'),
                      'title' => request()->input('title'),
                      'language' => request()->input('language'),
                  ])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
