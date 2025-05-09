@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Recipient Mail') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('vendor.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Recipient Mail') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="ajaxForm" action="{{ route('vendor.update_mail_to_vendor') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Recipient Mail') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body py-5">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="alert alert-warning text-center" role="alert">
                  <strong
                    class="text-dark">{{ __('This mail address will be used to receive all mails from contact forms.') }}</strong>
                </div>

                <div class="form-group">
                  <label>{{ __('Email Address') . '*' }}</label>
                  <input class="form-control" type="email" name="to_mail" value="{{ $data->to_mail }}">
                  @if ($errors->has('to_mail'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('to_mail') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button id="submitBtn" type="button" class="btn btn-primary btn-sm">
                  {{ __('Save') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
