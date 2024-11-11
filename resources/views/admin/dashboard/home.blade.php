@extends('admin.layouts.admin')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">

                  <div class="col-lg-3 col-md-12 col-3 mb-6">
                      <div class="card h-100">
                          <div class="card-body">
                              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                  <div class="avatar flex-shrink-0 user">
                                      <i class='bx bx-user bx-border bx-lg'></i>
                                  </div>
                              </div>
                              <p class="mb-1 fs-5">Users</p>
                              <h4 class="card-title mb-3">{{$users}}</h4>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-3 col-md-12 col-3 mb-6 ">
                      <div class="card h-100 ">
                          <div class="card-body">
                              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                  <div class="avatar flex-shrink-0 category">
                                      <i class='bx bx-category bx-border bx-lg'></i>
                                  </div>
                              </div>
                              <p class="mb-1 fs-5">Categories</p>
                              <h4 class="card-title mb-3">{{$categories}}</h4>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-3 col-md-12 col-3 mb-6">
                      <div class="card h-100">
                          <div class="card-body">
                              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                  <div class="avatar flex-shrink-0 product">
                                      <i class='bx bx-basket bx-border bx-lg'></i>
                                  </div>
                              </div>
                              <p class="mb-1 fs-5">Products</p>
                              <h4 class="card-title mb-3">{{$products}}</h4>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-3 col-md-12 col-3 mb-6">
                      <div class="card h-100">
                          <div class="card-body">
                              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                  <div class="avatar flex-shrink-0 shop">
                                      <i class='bx bx-store-alt bx-border bx-lg'></i>

                                  </div>
                              </div>
                              <p class="mb-1 fs-5">Shoppes</p>
                              <h4 class="card-title mb-3">{{$shoppes}}</h4>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row ">
                  <div class="col-6 bg-white">
                        <canvas id="polarAreaChart" width="400" height="400"></canvas>
                  </div>
                  <div class="col-6 bg-white">
                        <canvas id="barChart" width="400" height="400"></canvas>
                  </div>
              </div>
  </div>
  <script>
      // Data passed from Laravel to JavaScript
      const productsCount = {{ $products }};
      const usersCount = {{ $users }};
      const categoriesCount = {{ $categories }};
      const shoppesCount = {{ $shoppes }};

      const ctx = document.getElementById('polarAreaChart').getContext('2d');

      // Initialize the Polar Area Chart
      new Chart(ctx, {
          type: 'polarArea',
          data: {
              labels: ['Products', 'Users', 'Categories', 'Shoppers'],
              datasets: [{
                  label: 'Dashboard Stats',
                  data: [productsCount, usersCount, categoriesCount, shoppesCount],
                  backgroundColor: [
                      'rgba(255, 99, 132, 0.6)', // Color for Products
                      'rgba(54, 162, 235, 0.6)', // Color for Users
                      'rgba(255, 206, 86, 0.6)', // Color for Categories
                      'rgba(75, 192, 192, 0.6)'  // Color for Shoppers
                  ],
                  borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'top',
                  },
                  title: {
                      display: true,
                      text: 'Dashboard Stats (Polar Area Chart)'
                  }
              }
          }
      });

      // Bar Chart Configuration
      const ctxBar = document.getElementById('barChart').getContext('2d');
      new Chart(ctxBar, {
          type: 'bar',
          data: {
              labels: ['Products', 'Users', 'Categories', 'Shoppers'],
              datasets: [{
                  label: 'Dashboard Stats',
                  data: [productsCount, usersCount, categoriesCount, shoppesCount],
                  backgroundColor: [
                      'rgba(255, 99, 132, 0.6)',
                      'rgba(54, 162, 235, 0.6)',
                      'rgba(255, 206, 86, 0.6)',
                      'rgba(75, 192, 192, 0.6)'
                  ],
                  borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: { position: 'top' },
                  title: {
                      display: true,
                      text: 'Dashboard Stats (Bar Chart)'
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true
                  }
              }
          }
      });
  </script>

    </script>
@endsection
