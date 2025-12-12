@extends('layouts.login_master')

@section('content')

<script src="{{ asset('/sw.js') }}"></script>
<script>
    // Check if the browser supports the beforeinstallprompt event
    if ('serviceWorker' in navigator && 'BeforeInstallPromptEvent' in window) {
    // Listen for the beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (event) => {
    // Prevent the default "Add to Home Screen" prompt
    event.preventDefault();
    // Show the "Install App" button
    const installButton = document.getElementById('install-btn');
    installButton.style.display = 'block';
    // Save the event for later use
    let deferredPrompt = event;
    // Add event listener to the "Install App" button
    installButton.addEventListener('click', () => {
    // Trigger the "Add to Home Screen" prompt
    deferredPrompt.prompt();
    // Wait for the user to respond to the prompt
    deferredPrompt.userChoice
    .then((choiceResult) => {
    // Reset the prompt variable
    deferredPrompt = null;
    // Hide the "Install App" button after the prompt is shown
    installButton.style.display = 'none';
    });
    });
    });
    }
    </script>

    <div class="page-content login-cover" >

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center">

                <!-- Login card -->
                <form class="login-form" method="post" action="{{ route('login') }}">
                    @csrf
                    <div class="card mb-0 shadow-lg border-0" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2 p-2 rounded-circle bg-indigo-100 text-indigo-600">
                                     <img src="{{asset('logo.jpeg') }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                                </div>
                                <h5 class="mb-0 font-weight-bold">Welcome Back</h5>
                                <span class="d-block text-muted">Sign in to your account</span>
                            </div>

                                @if ($errors->any())
                                <div class="alert alert-danger border-0 alert-dismissible fade show">
                                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <span class="font-weight-semibold">Error:</span> {{ implode('<br>', $errors->all()) }}
                                </div>
                                @endif

                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control" name="identity" value="{{ old('identity') }}" placeholder="Login ID or Email">
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                            </div>

                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input required name="password" type="password" class="form-control" placeholder="{{ __('Password') }}">
                                <div class="form-control-feedback">
                                    <i class="icon-lock2 text-muted"></i>
                                </div>
                            </div>

                            <div class="form-group d-flex align-items-center justify-content-between">
                                <div class="form-check mb-0">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="remember" class="form-input-styled" {{ old('remember') ? 'checked' : '' }} data-fouc> 
                                        <span class="ml-2">Remember me</span>
                                    </label>
                                </div>

                                <a href="{{ route('password.request') }}" class="text-primary font-weight-medium">Forgot password?</a>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block shadow-sm">Sign in <i class="icon-arrow-right14 ml-2"></i></button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

  {{-- <button id="install-btn" style="display: none;">Install App <i class="icon-circle-right2 ml-2"></i></button> --}}
        </div>

        <script>
  // Check if the browser supports the beforeinstallprompt event
  if ('serviceWorker' in navigator && 'BeforeInstallPromptEvent' in window) {
    // Listen for the beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (event) => {
      // Prevent the default "Add to Home Screen" prompt
      event.preventDefault();

      // Show the "Install App" button
      const installButton = document.getElementById('install-btn');
      installButton.style.display = 'block';

      // Save the event for later use
      let deferredPrompt = event;

      // Add event listener to the "Install App" button
      installButton.addEventListener('click', () => {
        // Trigger the "Add to Home Screen" prompt
        deferredPrompt.prompt();

        // Wait for the user to respond to the prompt
        deferredPrompt.userChoice
          .then((choiceResult) => {
            // Reset the prompt variable
            deferredPrompt = null;
            // Hide the "Install App" button after the prompt is shown
            installButton.style.display = 'none';
          });
      });
    });
  }
     </script>

    </div>
    @endsection
