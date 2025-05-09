@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Posts') }}</h4>
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
        <a href="#">{{ __('Blog Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Posts') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex gap-10 flex-wrap align-items-center justify-content-between">
            <div class="card-title d-inline-block">{{ __('Posts') }}</div>

            <div class="d-flex gap-10 flex-wrap align-items-center">
              <a href="{{ route('admin.pages.blog.create_blog') }}" class="btn btn-primary btn-sm float-right"><i
                  class="fas fa-plus"></i> {{ __('Add Post') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.pages.blog.bulk_delete_blog') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($blogs) == 0)
                <h3 class="text-center mt-2">{{ __('NO POST FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Publish Date') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($blogs as $blog)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $blog->id }}">
                          </td>
                          <td>
                            {{ strlen($blog->title) > 50 ? mb_substr($blog->title, 0, 50, 'UTF-8') . '...' : $blog->title }}
                          </td>
                          <td>{{ $blog->categoryName }}</td>
                          <td>
                            <form id="StatusForm{{ $blog->id }}" class="d-inline-block"
                              action="{{ route('admin.pages.blog.update_blog_status') }}" method="post">
                              @csrf
                              <input type="hidden" name="blogId" value="{{ $blog->id }}">

                              <select
                                class="form-control form-control-sm {{ $blog->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="status"
                                onchange="document.getElementById('StatusForm{{ $blog->id }}').submit();">

                                <option value="1" {{ $blog->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $blog->status == '0' ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>

                              </select>
                            </form>

                          </td>
                          <td>
                            @php
                              // first, convert the string into date object
                              $date = Carbon\Carbon::parse($blog->created_at);
                            @endphp

                            {{ date_format($date, 'M d, Y') }}
                          </td>
                          <td>{{ $blog->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary mt-1 btn-sm mr-1"
                              href="{{ route('admin.pages.blog.edit_blog', ['id' => $blog->id]) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.pages.blog.delete_blog', ['id' => $blog->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger mt-1 btn-sm deleteBtn">
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
@endsection
