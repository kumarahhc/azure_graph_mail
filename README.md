## PHP Microsoft Graph Mailer

A simple PHP implementation to send emails using Microsoft Graph API (Office 365 / Azure AD) with OAuth2 authentication.

This project demonstrates how to:

- Authenticate using Azure AD (client credentials flow)
- Generate an access token
- Send emails via Microsoft Graph API
- Attach files and send HTML/Text emails

### Features
Send emails using Microsoft Graph API
Supports:
- To / CC / BCC recipients
- HTML and plain text emails
- File attachments
- Inline images
Uses OAuth2 Client Credentials Flow
Lightweight (no external libraries required)

### Azure Setup
Before using this project, you must configure an app in Microsoft Azure Portal:
1. Go to Azure Active Directory
2. Register a new application
3. Note the following:
  - Tenant ID
  - Client ID
4. Create a Client Secret
5. Add API permissions:  Mail.Send (Application permission)

### 📂 Project Structure

├── graphI3cMailer.php   # Reusable mailer class
├── sendMail.php        # Example script
|── phpMailSend.php        # Example script
