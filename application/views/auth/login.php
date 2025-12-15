<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-4">
            <a href="#" class="app-brand-link gap-2">
              <span class="app-brand-text demo text-body fw-bolder text-uppercase">Si-EPIK</span>
            </a>
          </div>

          <h4 class="mb-2">Welcome! 👋</h4>
          <p class="mb-4">Please sign in to continue</p>

          <form id="formAuthentication" class="mb-3" action="<?= site_url(
          	"auth/login",
          ) ?>" method="POST">
            <div class="mb-3">
              <label for="userid" class="form-label">UserID</label>
              <input type="text" class="form-control" id="userid" name="userid" placeholder="Enter your userID" required />
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
              </div>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="••••••••" required />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
