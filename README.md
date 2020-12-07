# php-oauth-office365

![version](https://img.shields.io/badge/version-1.0.0-blue)

**php-oauth-office365** is a demo showing how to implement OAuth2 for signing in with Microsoft Office365 using PHP.

# Requirements

You need to install the PHP Microsoft Graph SDK with Composer. Either run `composer require microsoft/microsoft-graph` or edit your `composer.json` file:
```
{
    "require": {
        "microsoft/microsoft-graph": "^1.20"
    }
}
```

# Azure Configuration Guide

### Step 1
![Step 1](/images/step_1.png)

### Step 2
![Step 2](/images/step_2.png)

### Step 3
![Step 3](/images/step_3.png)

### Step 4
![Step 4](/images/step_4.png)

### Step 5
![Step 5](/images/step_5.png)

### Step 6
![Step 6](/images/step_6.png)

### Step 7
![Step 7](/images/step_7.png)

### Step 8
![Step 8](/images/step_8.png)

### Step 9
Edit TENANT_ID, CLIENT_ID, CLIENT_SECRET and URL_REDIRECT constants in the PHP file to match with the Azure configuration above.

### Step 9
Place the PHP file on server with its path matching URL_REDIRECT.
