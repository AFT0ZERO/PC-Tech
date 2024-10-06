
<nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
              <i class="bx bx-menu bx-md"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center">
              <div class="nav-item d-flex align-items-center">

                  @yield('search')

              </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Place this tag where you want the button to render. -->
              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                      @if(Auth::user()->image == null)
                          <img src="https://afn.ca/wp-content/uploads/2022/12/unknown_staff-500x500.webp" alt="admin image" class="w-px-35 rounded-circle">
                      @else
                          <img src="{{asset(Auth::user()->image)}}" alt="admin image" class="w-px-35 rounded-circle" >
                      @endif

                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href={{route('admin.index')}}>
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                              @if(Auth::user()->image == null)
                                  <img src="https://afn.ca/wp-content/uploads/2022/12/unknown_staff-500x500.webp" alt="admin image" class="w-px-35 rounded-circle">
                              @else
                                  <img src="{{asset(Auth::user()->image)}}" alt="admin image" class="w-px-35 rounded-circle" >
                              @endif
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-0">{{Auth::user()->fname}}</h6>
                          <small class="text-muted">{{Auth::user()->role}}</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider my-1"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href={{route('admin.index')}}>
                      <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                    </a>
                  </li>

                  <li>
                      <a class="dropdown-item" href="{{ route('logout') }}"
                         onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                          <i class="bx bx-power-off bx-md me-3"></i><span>{{ __('Logout') }}</span>

                      </a>

                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                          @csrf
                      </form>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>
