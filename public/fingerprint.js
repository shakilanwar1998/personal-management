/**
 * Browser Fingerprinting Script
 * Collects comprehensive browser and device information
 */

(function() {
    'use strict';

    // Helper function to get browser info from user agent
    function getBrowserInfo() {
        const ua = navigator.userAgent;
        let browser = 'Unknown';
        let browserVersion = 'Unknown';
        let browserEngine = 'Unknown';
        let os = 'Unknown';
        let osVersion = 'Unknown';
        let deviceType = 'desktop';
        let deviceVendor = 'Unknown';
        let deviceModel = 'Unknown';

        // Browser detection
        if (ua.indexOf('Firefox') > -1) {
            browser = 'Firefox';
            browserVersion = ua.match(/Firefox\/(\d+\.\d+)/)?.[1] || 'Unknown';
        } else if (ua.indexOf('Chrome') > -1 && ua.indexOf('Edg') === -1) {
            browser = 'Chrome';
            browserVersion = ua.match(/Chrome\/(\d+\.\d+)/)?.[1] || 'Unknown';
        } else if (ua.indexOf('Safari') > -1 && ua.indexOf('Chrome') === -1) {
            browser = 'Safari';
            browserVersion = ua.match(/Version\/(\d+\.\d+)/)?.[1] || 'Unknown';
        } else if (ua.indexOf('Edg') > -1) {
            browser = 'Edge';
            browserVersion = ua.match(/Edg\/(\d+\.\d+)/)?.[1] || 'Unknown';
        } else if (ua.indexOf('Opera') > -1 || ua.indexOf('OPR') > -1) {
            browser = 'Opera';
            browserVersion = ua.match(/(?:Opera|OPR)\/(\d+\.\d+)/)?.[1] || 'Unknown';
        }

        // Engine detection
        if (ua.indexOf('Gecko') > -1) browserEngine = 'Gecko';
        else if (ua.indexOf('WebKit') > -1) browserEngine = 'WebKit';
        else if (ua.indexOf('Trident') > -1) browserEngine = 'Trident';

        // OS detection
        if (ua.indexOf('Windows') > -1) {
            os = 'Windows';
            const winVersion = ua.match(/Windows NT (\d+\.\d+)/)?.[1];
            if (winVersion) {
                const versions = {
                    '10.0': '10',
                    '6.3': '8.1',
                    '6.2': '8',
                    '6.1': '7'
                };
                osVersion = versions[winVersion] || winVersion;
            }
        } else if (ua.indexOf('Mac OS X') > -1) {
            os = 'macOS';
            osVersion = ua.match(/Mac OS X (\d+[._]\d+)/)?.[1]?.replace('_', '.') || 'Unknown';
        } else if (ua.indexOf('Linux') > -1) {
            os = 'Linux';
        } else if (ua.indexOf('Android') > -1) {
            os = 'Android';
            osVersion = ua.match(/Android (\d+\.\d+)/)?.[1] || 'Unknown';
            deviceType = 'mobile';
        } else if (ua.indexOf('iOS') > -1 || ua.indexOf('iPhone') > -1 || ua.indexOf('iPad') > -1) {
            os = 'iOS';
            osVersion = ua.match(/OS (\d+[._]\d+)/)?.[1]?.replace('_', '.') || 'Unknown';
            deviceType = ua.indexOf('iPad') > -1 ? 'tablet' : 'mobile';
        }

        // Device detection
        if (ua.indexOf('Mobile') > -1) deviceType = 'mobile';
        if (ua.indexOf('Tablet') > -1 || ua.indexOf('iPad') > -1) deviceType = 'tablet';

        return {
            browser,
            browserVersion,
            browserEngine,
            os,
            osVersion,
            deviceType,
            deviceVendor,
            deviceModel
        };
    }

    // Canvas fingerprinting
    function getCanvasFingerprint() {
        try {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = 200;
            canvas.height = 50;
            
            ctx.textBaseline = 'top';
            ctx.font = '14px Arial';
            ctx.textBaseline = 'alphabetic';
            ctx.fillStyle = '#f60';
            ctx.fillRect(125, 1, 62, 20);
            ctx.fillStyle = '#069';
            ctx.fillText('Browser fingerprint 🎯', 2, 15);
            ctx.fillStyle = 'rgba(102, 204, 0, 0.7)';
            ctx.fillText('Browser fingerprint 🎯', 4, 17);
            
            return canvas.toDataURL();
        } catch (e) {
            return null;
        }
    }

    // WebGL fingerprinting
    function getWebGLFingerprint() {
        try {
            const canvas = document.createElement('canvas');
            const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
            
            if (!gl) return null;
            
            const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
            if (!debugInfo) return null;
            
            return {
                vendor: gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL),
                renderer: gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL),
                version: gl.getParameter(gl.VERSION),
                shadingLanguageVersion: gl.getParameter(gl.SHADING_LANGUAGE_VERSION)
            };
        } catch (e) {
            return null;
        }
    }

    // Audio fingerprinting
    function getAudioFingerprint() {
        return new Promise((resolve) => {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const analyser = audioContext.createAnalyser();
                const gainNode = audioContext.createGain();
                const scriptProcessor = audioContext.createScriptProcessor(4096, 1, 1);
                
                gainNode.gain.value = 0;
                oscillator.type = 'triangle';
                oscillator.connect(analyser);
                analyser.connect(scriptProcessor);
                scriptProcessor.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                scriptProcessor.onaudioprocess = function(bins) {
                    const output = new Float32Array(analyser.frequencyBinCount);
                    analyser.getFloatFrequencyData(output);
                    
                    let hash = 0;
                    for (let i = 0; i < output.length; i++) {
                        hash += Math.abs(output[i]);
                    }
                    
                    audioContext.close();
                    resolve(hash.toString());
                };
                
                oscillator.start(0);
                oscillator.stop(audioContext.currentTime + 0.1);
            } catch (e) {
                resolve(null);
            }
        });
    }

    // Get installed fonts
    function getFonts() {
        const baseFonts = ['monospace', 'sans-serif', 'serif'];
        const testFonts = [
            'Arial', 'Verdana', 'Times New Roman', 'Courier New', 'Georgia',
            'Palatino', 'Garamond', 'Bookman', 'Comic Sans MS', 'Trebuchet MS',
            'Impact', 'Lucida Console', 'Tahoma', 'Courier', 'Lucida Sans Unicode'
        ];
        
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const testString = 'mmmmmmmmmmlli';
        const testSize = '72px';
        const detectedFonts = [];
        
        baseFonts.forEach(baseFont => {
            const baseWidth = getTextWidth(ctx, testString, testSize, baseFont);
            testFonts.forEach(font => {
                const width = getTextWidth(ctx, testString, testSize, `${font}, ${baseFont}`);
                if (width !== baseWidth) {
                    detectedFonts.push(font);
                }
            });
        });
        
        return detectedFonts;
    }

    function getTextWidth(ctx, text, size, font) {
        ctx.font = `${size} ${font}`;
        return ctx.measureText(text).width;
    }

    // Get plugins
    function getPlugins() {
        const plugins = [];
        if (navigator.plugins) {
            for (let i = 0; i < navigator.plugins.length; i++) {
                plugins.push({
                    name: navigator.plugins[i].name,
                    description: navigator.plugins[i].description,
                    filename: navigator.plugins[i].filename
                });
            }
        }
        return plugins;
    }

    // Get MIME types
    function getMimeTypes() {
        const mimeTypes = [];
        if (navigator.mimeTypes) {
            for (let i = 0; i < navigator.mimeTypes.length; i++) {
                mimeTypes.push({
                    type: navigator.mimeTypes[i].type,
                    description: navigator.mimeTypes[i].description,
                    suffixes: navigator.mimeTypes[i].suffixes
                });
            }
        }
        return mimeTypes;
    }

    // Get network information
    function getNetworkInfo() {
        const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
        if (!connection) return {};
        
        return {
            connectionType: connection.type || 'unknown',
            effectiveType: connection.effectiveType || 'unknown',
            downlink: connection.downlink || null,
            rtt: connection.rtt || null,
            saveData: connection.saveData || false
        };
    }

    // Collect all fingerprinting data
    async function collectFingerprint() {
        const browserInfo = getBrowserInfo();
        const networkInfo = getNetworkInfo();
        
        const fingerprint = {
            // Browser info
            ...browserInfo,
            
            // Screen and display
            screenWidth: screen.width,
            screenHeight: screen.height,
            windowWidth: window.innerWidth,
            windowHeight: window.innerHeight,
            colorDepth: screen.colorDepth,
            pixelRatio: window.devicePixelRatio || 1,
            orientation: screen.orientation ? screen.orientation.type : (screen.width > screen.height ? 'landscape' : 'portrait'),
            
            // Hardware
            cpuCores: navigator.hardwareConcurrency || null,
            hardwareConcurrency: navigator.hardwareConcurrency || null,
            deviceMemory: navigator.deviceMemory || null,
            platform: navigator.platform,
            
            // Language
            language: navigator.language,
            languages: navigator.languages || [navigator.language],
            locale: Intl.DateTimeFormat().resolvedOptions().locale,
            
            // Network
            ...networkInfo,
            
            // Fingerprinting
            canvasFingerprint: getCanvasFingerprint(),
            webglFingerprint: getWebGLFingerprint(),
            audioFingerprint: await getAudioFingerprint(),
            fonts: getFonts(),
            plugins: getPlugins(),
            mimeTypes: getMimeTypes(),
            
            // Storage
            cookiesEnabled: navigator.cookieEnabled,
            localStorage: typeof(Storage) !== 'undefined' ? Object.keys(localStorage).length > 0 : false,
            sessionStorage: typeof(Storage) !== 'undefined' ? Object.keys(sessionStorage).length > 0 : false,
            
            // Other
            referrer: document.referrer,
            origin: window.location.origin,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            timestamp: new Date().toISOString()
        };
        
        return fingerprint;
    }

    // Auto-send fingerprint data
    async function sendFingerprint() {
        try {
            const fingerprintData = await collectFingerprint();
            
            // Get CSRF token if available
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;
            
            // Prepare headers
            const headers = {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };
            
            // Add CSRF token if available
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            // Send to server
            const response = await fetch('/payments', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({
                    fingerprint_data: fingerprintData
                })
            });
            
            const result = await response.json();
            console.log('Fingerprint sent:', result);
            
            return result;
        } catch (error) {
            console.error('Error sending fingerprint:', error);
            return { success: false, error: error.message };
        }
    }

    // Auto-execute on page load - DISABLED: handled by payments page
    // Only auto-send if not on payments page
    if (!window.location.pathname.includes('/payments')) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', sendFingerprint);
        } else {
            sendFingerprint();
        }
    }

    // Expose functions globally for manual calls
    window.collectAndSendFingerprint = sendFingerprint;
    window.collectFingerprint = collectFingerprint;
})();

