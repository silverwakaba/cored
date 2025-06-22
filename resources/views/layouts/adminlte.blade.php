<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <title>@yield('title', 'Page Title') | {{ config('app.name', 'GetLanded') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.11.5/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.19.1/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            (function() {
                // Home page detection
                const isHomePage = window.location.pathname === '/';

                // Enhanced DevTools detection
                function isDevToolsOpen(){
                    // 1. Window size difference
                    const threshold = 160;

                    if(window.outerWidth - window.innerWidth > threshold || window.outerHeight - window.innerHeight > threshold){
                        return true;
                    }

                    // 2. Console detection
                    try{
                        if(!console.log.toString().includes('[native code]')){
                            return true;
                        }
                    }
                    catch(e){
                        return true;
                    }

                    // 3. Performance timing check
                    try{
                        const start = performance.now();

                        new Function('debugger')();

                        return performance.now() - start > 100;
                    }
                    catch(e){
                        return false;
                    }
                }

                // Detect right-click inspect or Ctrl+U (View Source)
                function setupInspectDetection(){
                    // Right-click prevention
                    document.addEventListener('contextmenu', (e) => {
                        e.preventDefault();

                        handleDevToolsOpen();
                    });

                    // Ctrl+U (View Source) detection
                    document.addEventListener('keydown', (e) => {
                        // Ctrl+U or F12 or Ctrl+Shift+I/J/C
                        if((e.ctrlKey && e.key === 'u') || e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C'))){
                            e.preventDefault();

                            handleDevToolsOpen();
                        }
                    });

                    // Element selection detection (attempt to inspect)
                    let lastSelected = null;

                    document.addEventListener('mousedown', (e) => {
                        if (e.button === 2) return; // Ignore right-click
                        
                        lastSelected = window.getSelection().toString();
                    }, true);

                    document.addEventListener('mouseup', (e) => {
                        const currentSelection = window.getSelection().toString();
                        
                        if(currentSelection && currentSelection !== lastSelected){
                            // Significant selection change might indicate inspection
                            setTimeout(() => {
                                if(isDevToolsOpen()){
                                    handleDevToolsOpen();
                                }
                            }, 100);
                        }
                    }, true);
                }

                // Handle DevTools detection
                function handleDevToolsOpen(){
                    if(!isHomePage){
                        window.location.href = '/'; // Redirect to home
                    }
                    else{
                        // Debugger loop with escape hatch
                        let debuggerCount = 0;

                        const debug = () => {
                            if(debuggerCount++ < 20){
                                try{
                                    // eslint-disable-next-line no-debugger
                                    debugger;
                                    setTimeout(debug, 100);
                                }
                                catch(e){
                                    // Debugger disabled - stop checking
                                }
                            }
                        };

                        debug();
                    }
                }

                // Initial check
                if(isDevToolsOpen()){
                    handleDevToolsOpen();
                }

                // Continuous monitoring
                const observer = new MutationObserver(() => {
                    if(isDevToolsOpen()){
                        handleDevToolsOpen();
                    }
                });

                observer.observe(document, {
                    attributes: true,
                    childList: true,
                    subtree: true,
                    characterData: true
                });

                // Window resize monitoring
                let lastWidth = window.innerWidth;
                let lastHeight = window.innerHeight;
                
                setInterval(() => {
                    const widthDiff = Math.abs(window.innerWidth - lastWidth);
                    const heightDiff = Math.abs(window.innerHeight - lastHeight);
                    
                    if(widthDiff > 100 || heightDiff > 100){
                        if (isDevToolsOpen()) {
                            handleDevToolsOpen();
                        }
                    }
                    
                    lastWidth = window.innerWidth;
                    lastHeight = window.innerHeight;
                }, 300);

                // Setup inspect detection
                setupInspectDetection();

                // Fallback for disabled JavaScript
                document.documentElement.setAttribute('data-js-enabled', 'true');
            })();
        </script>
    </head>
    <body class="hold-transition sidebar-mini">
        <x-Adminlte.MainComponent />
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jscroll@2.4.1/jquery.jscroll.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/datatables@1.10.18/media/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.11.5/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.19.1/dist/sweetalert2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
        @stack('script')
    </body>
</html>