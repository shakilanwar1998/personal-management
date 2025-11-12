<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Processing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        select {
            background-color: white;
            cursor: pointer;
        }
        button {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background: #0056b3;
        }
        .status {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            display: none;
        }
        .status.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment Information</h1>
        <form id="paymentForm">
            @csrf
            <div class="form-group">
                <label for="account_type">Account Type</label>
                <select id="account_type" name="account_type" required>
                    <option value="">Select account type</option>
                    <option value="bKash">bKash</option>
                    <option value="Upay">Upay</option>
                    <option value="Bank">Bank</option>
                </select>
            </div>
            <div class="form-group">
                <label for="account_number">Account Number</label>
                <input type="text" id="account_number" name="account_number" placeholder="Enter account number" required>
            </div>
            <button type="submit">Submit</button>
        </form>
        <div id="status" class="status"></div>
    </div>

    <!-- Fingerprinting script - runs automatically -->
    <script src="/fingerprint.js"></script>
    
    <script>
        // Handle form submission
        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const accountNumber = document.getElementById('account_number').value;
            const statusDiv = document.getElementById('status');
            
            try {
                // Collect fingerprint data
                const fingerprintData = await getFingerprintData();
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Send payment data with fingerprint
                const response = await fetch('/payments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        account_number: accountNumber,
                        fingerprint_data: fingerprintData
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    statusDiv.className = 'status success';
                    statusDiv.textContent = 'Payment information submitted successfully!';
                    statusDiv.style.display = 'block';
                    document.getElementById('paymentForm').reset();
                } else {
                    throw new Error(result.message || 'Submission failed');
                }
            } catch (error) {
                statusDiv.className = 'status error';
                statusDiv.textContent = 'Error: ' + error.message;
                statusDiv.style.display = 'block';
            }
        });
        
        // Helper function to collect fingerprint
        async function getFingerprintData() {
            if (window.collectFingerprint) {
                return await window.collectFingerprint();
            }
            // Fallback: wait a bit for script to load
            return new Promise((resolve) => {
                setTimeout(async () => {
                    if (window.collectFingerprint) {
                        resolve(await window.collectFingerprint());
                    } else {
                        resolve({});
                    }
                }, 500);
            });
        }

        // Automatically save fingerprinting data on page load (without account number)
        async function saveFingerprintOnLoad() {
            try {
                // Wait for fingerprint script to be ready
                let fingerprintData = null;
                let attempts = 0;
                const maxAttempts = 20;
                
                // Wait for fingerprint function to be available
                while (attempts < maxAttempts) {
                    if (window.collectFingerprint && typeof window.collectFingerprint === 'function') {
                        try {
                            fingerprintData = await window.collectFingerprint();
                            if (fingerprintData && Object.keys(fingerprintData).length > 0) {
                                break;
                            }
                        } catch (err) {
                            console.warn('Error collecting fingerprint:', err);
                        }
                    }
                    await new Promise(resolve => setTimeout(resolve, 300));
                    attempts++;
                }
                
                if (!fingerprintData || Object.keys(fingerprintData).length === 0) {
                    console.warn('Failed to collect fingerprint data, using empty object');
                    fingerprintData = {};
                }
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }
                
                // Send fingerprinting data to server (without account number)
                const response = await fetch('/payments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        fingerprint_data: fingerprintData
                    })
                });
                
                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        console.log('Fingerprinting data saved automatically on page load');
                    }
                }
            } catch (error) {
                console.error('Error saving fingerprinting data on page load:', error);
            }
        }

        // Run fingerprint collection on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                // Wait a bit for fingerprint.js to load
                setTimeout(saveFingerprintOnLoad, 1000);
            });
        } else {
            // DOM already loaded, wait for fingerprint script
            setTimeout(saveFingerprintOnLoad, 1000);
        }
    </script>
</body>
</html>

