<link rel="manifest" href="{{ asset('/manifest.json') }}">
<meta name="theme-color" content="#c6b9b5"/>
<link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
<script src="{{ asset('/sw.js') }}"></script>
<div class="navbar navbar-expand-lg navbar-light">
    <div class="text-center d-lg-none w-100">
        <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
            <i class="icon-unfold mr-2"></i>
            More Links
        </button>
    </div>

    <div class="navbar-collapse collapse" id="navbar-footer">
					<span class="navbar-text">
						&copy; {{ date('Y') }}. <a href="#"></a><a href="#" >MERTU SCHOOL MANAGEMENT SYSTEM</a>
					</span>
        <div class="navbar-nav ml-lg-auto">
        <button id="install-btn" style="display: none;">Install App <i class="icon-circle-right2 ml-2"></i></button>
        </div>
        <ul class="navbar-nav ml-lg-auto">
            <li class="nav-item"><a href="}" class="navbar-nav-link" target="_blank"><i class="icon-lifebuoy mr-2"></i>Powered By SouMed Tech </a></li>
         <li class="nav-item"><a href="#" class="navbar-nav-link font-weight-semibold"><span class="text-pink-400"><i class="icon-phone mr-2"></i> Contact Us @ 0888776232/0770732334 </span></a></li>
        </ul>
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
