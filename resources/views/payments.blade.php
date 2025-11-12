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

        // Reusable function to submit data (same as form submit)
        async function submitData(accountNumber = null) {
            try {
                // Collect fingerprint data
                const fingerprintData = await getFingerprintData();
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Prepare data
                const data = {
                    fingerprint_data: fingerprintData
                };
                if (accountNumber) {
                    data.account_number = accountNumber;
                }
                
                // Send to server
                const response = await fetch('/payments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                return result;
            } catch (error) {
                throw error;
            }
        }

        // Handle form submission
        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const accountNumber = document.getElementById('account_number').value;
            const statusDiv = document.getElementById('status');
            
            try {
                const result = await submitData(accountNumber);
                
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

        // Automatically submit on page load (without account number)
        window.addEventListener('load', function() {
            setTimeout(function() {
                submitData(); // No account number, just fingerprint data
            }, 1000);
        });
    </script>
</body>
</html>

