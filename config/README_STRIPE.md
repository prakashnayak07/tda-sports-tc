# Stripe Configuration Setup

## Security Notice
Stripe API keys have been moved to environment variables for security. This prevents sensitive keys from being committed to version control.

## Setup Instructions

### 1. Environment File Setup
1. Copy the example file: `cp config/stripe.env.example config/stripe.env`
2. Edit `config/stripe.env` and add your actual Stripe API keys:
   ```
   STRIPE_SECRET_KEY=sk_test_your_actual_secret_key
   STRIPE_PUBLISHABLE_KEY=pk_test_your_actual_publishable_key
   STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
   ```

### 2. File Security
- `config/stripe.env` is automatically ignored by git (added to .gitignore)
- Only `config/stripe.env.example` is committed to version control
- The actual API keys are loaded from environment variables at runtime

### 3. How It Works
- Environment variables are loaded early in the application bootstrap
- Configuration files now use `getenv()` to read the keys
- If environment variables are not set, empty strings are used as fallbacks

### 4. Production Deployment
For production, set the environment variables directly on your server:
```bash
export STRIPE_SECRET_KEY=sk_live_your_production_key
export STRIPE_PUBLISHABLE_KEY=pk_live_your_production_key
export STRIPE_WEBHOOK_SECRET=whsec_your_production_webhook_secret
```

## Files Modified
- `config/autoload/local.php` - Updated to use environment variables
- `config/autoload/global.php` - Updated to use environment variables  
- `public/index.php` - Added environment variable loading
- `config/load_env.php` - New file to load environment variables
- `config/stripe.env.example` - Example configuration file
- `.gitignore` - Added protection for sensitive files 