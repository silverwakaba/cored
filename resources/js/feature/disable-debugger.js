(function(){
    // Home page detection - more flexible
    const isHomePage = ['/', '/index.html', '/home'].includes(window.location.pathname);

    // State tracking
    let devToolsDetected = false;
    let debuggerActive = false;

    // Enhanced DevTools detection
    function isDevToolsOpen() {
        // Skip if already detected
        if (devToolsDetected) return true;

        // 1. Window size difference (most reliable)
        const threshold = 160;
        const widthDiff = window.outerWidth - window.innerWidth;
        const heightDiff = window.outerHeight - window.innerHeight;
        
        if (widthDiff > threshold || heightDiff > threshold) {
            return true;
        }

        // 2. Console detection
        try {
            if (!/native code/.test(console.log.toString())) {
                return true;
            }
        } catch(e) {
            return true;
        }

        // 3. Performance timing check (more accurate)
        try {
            const start = performance.now();
            const debugTest = new Function('debugger');
            debugTest();
            return performance.now() - start > 100;
        } catch(e) {
            return false;
        }
    }

    // Debugger lock with anti-bypass
    function activateDebuggerLock() {
        if (debuggerActive) return;
        
        devToolsDetected = true;
        debuggerActive = true;
        
        // Nuclear debugger loop
        const debugLoop = () => {
            try {
                // Obfuscated debugger call
                Function('d', 'e', 'b', 'u', 'g', 'g', 'e', 'r')(0,0,0,0,0,0,0);
                setTimeout(debugLoop, 50);
            } catch(e) {
                // If debugger is disabled, force redirect
                // window.location.replace('/?security_lock=1');

                window.location.href = "/";
            }
        };
        debugLoop();
    }

    // Handle DevTools detection
    function handleDevToolsOpen() {
        if (isHomePage) {
            activateDebuggerLock();
        } else {
            window.location.replace('/');
        }
    }

    // Inspect Detection
    function setupInspectDetection() {
        // Block navigation when debugger is active
        window.addEventListener('beforeunload', (e) => {
            if (devToolsDetected) {
                e.preventDefault();
                e.returnValue = 'DevTools detected - navigation blocked';
                return e.returnValue;
            }
        });

        // Enhanced keyboard detection
        document.addEventListener('keydown', (e) => {
            // Ctrl+U, F12, Ctrl+Shift+I/J/C
            if ((e.ctrlKey && e.key === 'u') || 
                e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && ['I','J','C'].includes(e.key))) {
                e.preventDefault();
                e.stopImmediatePropagation();
                activateDebuggerLock();
            }
        });

        // Right-click prevention
        document.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            if (isDevToolsOpen()) {
                activateDebuggerLock();
            }
        });

        // Mouse selection monitoring
        let lastSelection = '';
        document.addEventListener('mouseup', () => {
            const current = window.getSelection().toString();
            if (current && current !== lastSelection) {
                setTimeout(() => {
                    if (isDevToolsOpen()) {
                        activateDebuggerLock();
                    }
                }, 100);
            }
            lastSelection = current;
        });
    }

    // Initial check with delay to prevent false positives
    setTimeout(() => {
        if (isDevToolsOpen()) {
            handleDevToolsOpen();
        }
    }, 500);

    // Continuous monitoring
    const observer = new MutationObserver(() => {
        if (!debuggerActive && isDevToolsOpen()) {
            handleDevToolsOpen();
        }
    });

    observer.observe(document, {
        childList: true,
        subtree: true,
        attributes: true
    });

    // Periodic forced checks
    setInterval(() => {
        if (!debuggerActive && isDevToolsOpen()) {
            handleDevToolsOpen();
        }
    }, 1000);

    // Setup inspect detection
    setupInspectDetection();

    // Server-side header check
    fetch(window.location.href, {
        headers: { 'X-Inspect-Block': 'true' }, cache: 'no-store',
    }).catch(() => {});

    // Fallback for disabled JavaScript
    document.documentElement.setAttribute('data-js-enabled', 'true');
})();