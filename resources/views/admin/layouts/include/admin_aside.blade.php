<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="index.html" class="app-brand-link">

            <!-- logo start -->
            <span class="app-brand-text demo menu-text fw-bold ms-2">Pc Tech</span>
            <!-- logo end -->

          </a>
          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboards -->
          <li class="menu-item">
            <a href={{route('dashboard')}} class="menu-link">
              <!-- <i class="menu-icon tf-icons bx bx-crown"></i> -->
              <i class=' menu-icon tf-icons bx bx-home' ></i>
              <div class="text-truncate" data-i18n="Boxicons"> Dashboard</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{route('category.index')}}" class="menu-link">
              <i class='menu-icon tf-icons bx bx-category' ></i>
              <div class="text-truncate" data-i18n="Boxicons">Categories</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{route('product.index')}}" class="menu-link">
              <i class='menu-icon tf-icons bx bx-basket'></i>
              <div class="text-truncate" data-i18n="Boxicons">Product</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{route('store.index')}}" class="menu-link">
              <i class='menu-icon tf-icons bx bx-store-alt' ></i>
            <div class="text-truncate" data-i18n="Boxicons">Shoppes</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{route('users.index')}}" class="menu-link">
              <i class='menu-icon tf-icons bx bx-user' ></i>
              <div class="text-truncate" data-i18n="Boxicons">Users</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{route('contact.index')}}" class="menu-link">
              <i class=' menu-icon tf-icons bx bxs-contact'></i>
              <div class="text-truncate" data-i18n="Boxicons">Contact</div>
            </a>
          </li>

          <li class="menu-item">
            <a href={{route("faq.index")}} class="menu-link">
              <i class='menu-icon tf-icons bx bx-question-mark'></i>
              <div class="text-truncate" data-i18n="Boxicons">FAQs</div>
            </a>
          </li>


        </ul>
      </aside>
