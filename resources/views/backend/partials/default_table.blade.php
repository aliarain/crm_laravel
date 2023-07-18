<div class="table-content table-basic">
  <div class="">
    <div class="card-header">{{ __('Basic Datatable') }}</div>
    <!-- toolbar table start -->
    <div
      class="table-toolbar d-flex flex-wrap gap-2 flex-xl-row justify-content-center justify-content-xxl-between align-content-center pb-3">
      <div class="align-self-center">
        <div class="d-flex flex-wrap gap-2  flex-lg-row justify-content-center align-content-center">
          <!-- show per page -->
          <div class="align-self-center">
            <label>
              <span class="mr-8">{{ __('Show') }}</span>
              <select class="form-select d-inline-block">
                <option value="10">{{ __('10') }}</option>
                <option value="25">{{ __('25') }}</option>
                <option value="50">{{ __('50') }}</option>
                <option value="100">{{ __('100') }}</option>
              </select>
              <span class="ml-8">{{ __('Entries') }}</span>
            </label>
          </div>

          <div class="align-self-center d-flex flex-wrap gap-2">
            <!-- add btn -->
            <div class="align-self-center">
              <a href="#" role="button" class="btn-add" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="{{ _trans('common.Add') }}">
                <span><i class="fa-solid fa-plus"></i> </span>
                <span class="d-none d-xl-inline">{{ __('Add') }}</span>
              </a>
            </div>
            <!-- daterange -->
            <div class="align-self-center">
              <button type="button" class="btn-daterange" id="daterange" data-bs-toggle="tooltip"
                data-bs-placement="right" data-bs-title="{{ _trans('common.Date Range') }}">
                <span class="icon"><i class="fa-solid fa-calendar-days"></i>
                </span>
                <span class="d-none d-xl-inline">{{ __('Date Range') }}</span>
              </button>
            </div>
            <!-- Designation -->
            <div class="align-self-center">
              <div class="dropdown dropdown-designation" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Designation">
                <button type="button" class="btn-designation" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="icon"><i class="fa-solid fa-user-shield"></i></span>

                  <span class="d-none d-xl-inline">{{ __('Designation') }}</span>
                </button>

                <div class="dropdown-menu">
                  <div class="search-content">
                    <div class="search-box d-flex">
                      <input class="form-control" placeholder="{{ __('Search') }}" name="search" />
                      <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                    </div>
                  </div>

                  <div class="dropdown-divider"></div>

                  <ul class="list">
                    <li class="list-item">
                      <a class="dropdown-item" href="#">{{ __('Main Department') }}</a>
                    </li>

                    <li class="list-item">
                      <a class="dropdown-item" href="#">{{ __('Admin & HRM') }}</a>
                    </li>

                    <li class="list-item">
                      <a class="dropdown-item" href="#">{{ __('Accounts') }}</a>
                    </li>

                    <li class="list-item">
                      <a class="dropdown-item" href="#">{{ __('Development') }}</a>
                    </li>

                    <li class="list-item">
                      <a class="dropdown-item" href="#">{{ __('Software') }}</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- search -->
          <div class="align-self-center">
            <div class="search-box d-flex">
              <input class="form-control" placeholder="{{ __('Search') }}" name="search" />
              <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
            </div>
          </div>

          <!-- dropdown action -->
          <div class="align-self-center">
            <div class="dropdown dropdown-action" data-bs-toggle="tooltip" data-bs-placement="right"
              data-bs-title="{{ _trans('common.Action') }}">
              <button type="button" class="btn-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-ellipsis"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="#"><span class="icon mr-10"><i class="fa-solid fa-eye"></i></span>
                    {{ __('Make Publish') }}</a>
                </li>
                <li>
                  <a class="dropdown-item" href="#" aria-current="true"><span class="icon mr-8"><i
                        class="fa-solid fa-eye-slash"></i></span>
                    {{ __('Make Unpublish') }}</a>
                </li>
                <li>
                  <a class="dropdown-item" href="#">
                    <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>{{ __('Delete') }}
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- export -->
      <div class="align-self-center">
        <div class="d-flex justify-content-center justify-content-xl-end align-content-center">
          <div class="dropdown dropdown-export" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-title="{{ _trans('common.Export') }}">
            <button type="button" class="btn-export" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>

              <span class="d-none d-xl-inline">{{ __('Export') }}</span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="#"><span class="icon mr-8"><i class="fa-solid fa-copy"></i></span>
                  {{ __('Copy') }}</a>
              </li>
              <li>
                <a class="dropdown-item" href="#" aria-current="true"><span class="icon mr-10"><i
                      class="fa-solid fa-file-excel"></i></span>
                  {{ __('Exel File') }}</a>
              </li>
              <li>
                <a class="dropdown-item" href="#">
                  <span class="icon mr-14"><i class="fa-solid fa-file-csv"></i></span>{{ __('Csv File') }}
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#">
                  <span class="icon mr-14"><i class="fa-solid fa-file-pdf"></i></span>{{ __('Pdf File') }}
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#">
                  <span class="icon mr-10"><i class="fa-solid fa-table-columns"></i></span>{{ __('Column Header') }}
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- toolbar table end -->
    <!--  table start -->
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="thead">
          <tr>
            <th class="sorting_asc">
              <div class="check-box">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" />
                </div>
              </div>
            </th>
            <th class="sorting_desc">{{ __('SR No.') }}</th>
            <th class="sorting_desc">{{ __('Purchase ID') }}</th>
            <th class="sorting_desc">{{ __('Title') }}</th>
            <th class="sorting_desc">{{ __('User') }}</th>
            <th class="sorting_desc">{{ __('Assigned To') }}</th>
            <th class="sorting_desc">{{ __('Created By') }}</th>
            <th class="sorting_desc">{{ __('Create Date') }}</th>
            <th class="sorting_desc">{{ __('Status') }}</th>
            <th class="sorting_desc">{{ __('Priority') }}</th>
            <th class="sorting_desc">{{ __('Action') }}</th>
          </tr>
        </thead>
        <tbody class="tbody">
          <tr>
            <td>
              <div class="check-box">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" />
                </div>
              </div>
            </td>
            <td>{{ __('01') }}</td>
            <td>{{ __('IDL-963') }}</td>
            <td>{{ __('Add Dynamic Contact List') }}</td>
            <td>{{ __('Robert Downey') }}</td>
            <td>{{ __('Peter Parker') }}</td>
            <td>{{ __('Robert Downey') }}</td>
            <td>{{ __('Jan 14, 2022') }}</td>
            <td>
              <span class="badge-light-success">{{ __('Re-open') }}</span>
            </td>
            <td>
              <span class="badge-success">{{ __('High') }}</span>
            </td>
            <td>
              <div class="dropdown dropdown-action">
                <button type="button" class="btn-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-ellipsis"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#"><span class="icon mr-12"><i
                          class="fa-solid fa-pen-to-square"></i></span>
                      {{ __('Edit') }}</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>{{ __('Delete') }}
                    </a>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="check-box">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" />
                </div>
              </div>
            </td>
            <td>{{ __('01') }}</td>
            <td>{{ __('IDL-963') }}</td>
            <td>{{ __('Add Dynamic Contact List') }}</td>
            <td>{{ __('Robert Downey') }}</td>
            <td>{{ __('Peter Parker') }}</td>
            <td>{{ __('Robert Downey') }}</td>
            <td>{{ __('Jan 14, 2022') }}</td>
            <td>
              <span class="badge-light-danger">{{ __('Re-open') }}</span>
            </td>
            <td>
              <span class="badge-danger">{{ __('High') }}</span>
            </td>
            <td>
              <div class="dropdown dropdown-action">
                <button type="button" class="btn-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-ellipsis"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#"><span class="icon mr-12"><i
                          class="fa-solid fa-pen-to-square"></i></span>
                      {{ __('Edit') }}</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>{{ __('Delete') }}
                    </a>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="check-box">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" />
                </div>
              </div>
            </td>
            <td>{{ __('01') }}</td>
            <td>{{ __('IDL-963') }}</td>
            <td>{{ __('Add Dynamic Contact List') }}</td>
            <td>{{ __('Robert Downey') }}</td>
            <td>{{ __('Peter Parker') }}</td>
            <td>{{ __('Robert Downey') }}</td>
            <td>{{ __('Jan 14, 2022') }}</td>
            <td>
              <span class="badge-light-warning">{{ __('Re-open') }}</span>
            </td>
            <td>
              <span class="badge-warning">{{ __('High') }}</span>
            </td>
            <td>
              <div class="dropdown dropdown-action">
                <button type="button" class="btn-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-ellipsis"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#"><span class="icon mr-12"><i
                          class="fa-solid fa-pen-to-square"></i></span>
                      {{ __('Edit') }}</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>{{ __('Delete') }}
                    </a>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="check-box">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" />
                </div>
              </div>
            </td>
            <td>{{ __('01') }}</td>
            <td>{{ __('IDL-963') }}</td>
            <td>{{ __('Add Dynamic Contact List') }}</td>
            <td>{{ __('Robert Downey') }}</td>
            <td>{{ __('Peter Parker') }}</td>
            <td>{{ __('Robert Downey') }}</td>
            <td>{{ __('Jan 14, 2022') }}</td>
            <td>
              <span class="badge-light-primary">{{ __('Re-open') }}</span>
            </td>
            <td>
              <span class="badge-primary">{{ __('High') }}</span>
            </td>
            <td>
              <div class="dropdown dropdown-action">
                <button type="button" class="btn-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-ellipsis"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#"><span class="icon mr-12"><i
                          class="fa-solid fa-pen-to-square"></i></span>
                      {{ __('Edit') }}</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>{{ __('Delete') }}
                    </a>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--  table end -->
    <!--  pagination start -->
    <div class="ot-pagination d-flex justify-content-end align-content-center">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Previous">
              <span aria-hidden="true"><i class="fa fa-angle-left"></i></span>
            </a>
          </li>
          <li class="page-item">
            <a class="page-link active" href="#">{{ __('1') }}</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="#">{{ __('2') }}</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="#">{{ __('3') }}</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
              <span aria-hidden="true"><i class="fa fa-angle-right"></i></span>
            </a>
          </li>
        </ul>
      </nav>
    </div>

    <!--  pagination end -->
  </div>
</div>