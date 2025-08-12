# SMTP Troubleshooting Guide

## Common SMTP Authentication Issues

### Error 535: Incorrect authentication data

This error typically occurs when:

1. **Wrong username/password**
2. **Incorrect SMTP host or port**
3. **Wrong encryption settings**
4. **Server requires specific authentication method**

## Current Configuration

```env
SMTP_HOST=s1310.sgp1.mysecurecloudhost.com
SMTP_PORT=465
EMAIL_USER=noreply@email.okkhor.com
EMAIL_PASSWORD="{Oq?t,6.S9#^uv67"
```

## Troubleshooting Steps

### 1. Verify Credentials
- Double-check the email username and password
- Ensure the password is correctly quoted in the .env file
- Try logging into the email account manually to verify credentials

### 2. Test Different Ports and Encryption
The EmailService now tries multiple configurations automatically:
- Port 465 with SSL
- Port 587 with TLS
- Port 465 with SSL (no certificate verification)
- Port 465 with SSL (with timeout and local domain)

### 3. Check Server Requirements
Some SMTP servers require:
- Specific authentication methods (LOGIN, PLAIN, CRAM-MD5)
- App-specific passwords instead of regular passwords
- Two-factor authentication to be disabled for SMTP

### 4. Test SMTP Connection Manually
You can test the SMTP connection using telnet:

```bash
# Test connection (replace with your actual host)
telnet s1310.sgp1.mysecurecloudhost.com 465

# Or test port 587
telnet s1310.sgp1.mysecurecloudhost.com 587
```

### 5. Check Firewall and Network
- Ensure port 465 or 587 is not blocked
- Check if your hosting provider allows SMTP connections
- Verify the server can reach the SMTP host

### 6. Alternative Solutions

#### Option 1: Use App Password
If your email provider supports app passwords:
1. Generate an app-specific password
2. Use that password instead of your regular password

#### Option 2: Enable "Less Secure Apps"
Some providers allow less secure app access:
1. Check your email provider's security settings
2. Enable "Less secure app access" if available

#### Option 3: Use Different SMTP Provider
Consider using alternative SMTP services:
- Gmail SMTP
- SendGrid
- Mailgun
- Amazon SES

## Testing the API

Use the test endpoint to verify configuration:

```bash
curl -X GET http://your-domain.com/api/email/test-config
```

## Log Analysis

Check Laravel logs for detailed error messages:

```bash
tail -f storage/logs/laravel.log
```

Look for entries like:
- "Trying SMTP configuration #1"
- "SMTP configuration #1 failed"
- "All SMTP configurations failed"

## Common Solutions

### For Gmail:
```env
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
EMAIL_USER=your-email@gmail.com
EMAIL_PASSWORD=your-app-password
```

### For Outlook/Hotmail:
```env
SMTP_HOST=smtp-mail.outlook.com
SMTP_PORT=587
EMAIL_USER=your-email@outlook.com
EMAIL_PASSWORD=your-password
```

### For Yahoo:
```env
SMTP_HOST=smtp.mail.yahoo.com
SMTP_PORT=587
EMAIL_USER=your-email@yahoo.com
EMAIL_PASSWORD=your-app-password
```

## Contact Support

If the issue persists:
1. Contact your email hosting provider
2. Verify SMTP settings with your hosting company
3. Check if there are any IP restrictions or whitelisting requirements
