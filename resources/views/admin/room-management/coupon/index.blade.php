@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Coupons') }}</h4>
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
        <a href="#">{{ __('Rooms Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Coupons') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex gap-10 flex-wrap align-items-center justify-content-between">
            <div class="card-title d-inline-block">{{ __('Coupons') }}</div>

            <div class="d-flex gap-10 flex-wrap align-items-center">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add') }}</a>
              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.room_management.coupon.bulk_delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($coupons) == 0)
                <h3 class="text-center mt-2">{{ __('NO COUPON FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Code') }}</th>
                        <th scope="col">{{ __('Discount') }}</th>
                        <th scope="col">{{ __('Created') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($coupons as $coupon)
                        @php
                          $todayDate = Carbon\Carbon::now();
                          $startDate = Carbon\Carbon::parse($coupon->start_date);
                          $endDate = Carbon\Carbon::parse($coupon->end_date);
                        @endphp

                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $coupon->id }}">
                          </td>
                          <td>
                            {{ strlen($coupon->name) > 30 ? mb_substr($coupon->name, 0, 30, 'UTF-8') . '...' : $coupon->name }}
                          </td>
                          <td>{{ $coupon->code }}</td>
                          <td>
                            @if ($coupon->type == 'fixed')
                              {{ symbolPrice($coupon->value) }}
                            @else
                              {{ $coupon->value . '%' }}
                            @endif
                          </td>
                          <td>
                            @php
                              $createDate = $coupon->created_at;

                              $diff = $createDate->diffInDays($todayDate);
                            @endphp

                            {{-- then, get the human read-able value from those dates --}}
                            {{ $createDate->subDays($diff)->diffForHumans() }}
                          </td>
                          <td>
                            @if ($startDate->greaterThan($todayDate))
                              <h2 class="d-inline-block"><span class="badge badge-warning">{{ __('Pending') }}</span>
                              </h2>
                            @elseif ($todayDate->between($startDate, $endDate))
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Active') }}</span>
                              </h2>
                            @elseif ($endDate->lessThan($todayDate))
                              <h2 class="d-inline-block"><span class="badge badge-danger">{{ __('Expired') }}</span></h2>
                            @endif
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1  mt-1 btn-sm editBtn" href="#"
                              data-toggle="modal" data-target="#editModal" data-id="{{ $coupon->id }}"
                              data-name="{{ $coupon->name }}" data-code="{{ $coupon->code }}"
                              data-type="{{ $coupon->type }}" data-value="{{ $coupon->value }}"
                              data-start_date="{{ date_format($startDate, 'm/d/Y') }}"
                              data-end_date="{{ date_format($endDate, 'm/d/Y') }}"
                              data-minimum_spend="{{ $coupon->minimum_spend }}"
                              data-rooms="{{ empty($coupon->rooms) ? '' : $coupon->rooms }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.room_management.coupon.delete', ['id' => $coupon->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger  mt-1 btn-sm btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer"></div>
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('admin.room-management.coupon.create')

  {{-- edit modal --}}
  @include('admin.room-management.coupon.edit')
@endsection
