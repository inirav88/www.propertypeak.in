# Production Deployment Guide

## Overview

This guide covers the complete production deployment process, including safety checks, approval workflow, and rollback procedures.

## 🔒 Key Safety Principle

**Production deployments require manual approval.** This ensures a human checkpoint before any changes go live.

---

## Deployment Methods

### Method 1: Promotion Workflow (Recommended)

**Best for:** Most production deployments

**Steps:**

1. **Verify staging is working**
   ```bash
   # Visit staging site
   https://staging.propertypeak.in
   
   # Test critical features:
   - User login/registration
   - Property search
   - Contact forms
   - CRM functionality
   ```

2. **Run promotion workflow**
   - Go to **GitHub → Actions**
   - Select **"Promote Staging to Production"**
   - Click **"Run workflow"** button
   - Type **"PROMOTE"** (all caps) in the confirmation field
   - Click **"Run workflow"**

3. **Monitor the workflow**
   - Workflow will verify staging health
   - Workflow will merge `staging` → `main`
   - Workflow will trigger production deployment
   - **Deployment will pause for approval**

4. **Approve deployment**
   - Click **"Review deployments"** button
   - Select **"production"** environment
   - Review the changes being deployed
   - Click **"Approve and deploy"**

5. **Verify production**
   ```bash
   # Visit production site
   https://propertypeak.in
   
   # Verify critical features work
   # Monitor error logs
   ```

---

### Method 2: Manual Merge

**Best for:** Emergency hotfixes or when you need direct control

**Steps:**

1. **Ensure staging is tested**
   ```bash
   # Test on staging first
   https://staging.propertypeak.in
   ```

2. **Merge staging to main**
   ```bash
   # Update local branches
   git checkout staging
   git pull origin staging
   
   git checkout main
   git pull origin main
   
   # Merge staging into main
   git merge staging -m "chore: promote staging to production"
   
   # Push to main
   git push origin main
   ```

3. **Approve deployment** (same as Method 1, step 4)

4. **Verify production** (same as Method 1, step 5)

---

## Pre-Deployment Checklist

Before promoting to production, verify:

- [ ] All changes tested on staging
- [ ] Critical user flows verified
- [ ] No open critical bugs
- [ ] Database migrations tested (if any)
- [ ] No breaking changes (or communicated to team)
- [ ] Staging has been stable for at least 1 hour
- [ ] Team notified of upcoming deployment

---

## Automated Safety Checks

The deployment workflow automatically checks:

### Staging Verification
- ✅ Staging branch exists and is ahead of main
- ✅ Staging server is responding (HTTP 200/302)
- ✅ Staging was updated recently (within 7 days)

### Pre-Deployment Checks
- ✅ Server is on `main` branch
- ✅ Deployment directory exists
- ✅ Sufficient disk space (500MB minimum)
- ✅ PHP artisan is available
- ✅ Current commit recorded for rollback

---

## Manual Approval Process

### Who Can Approve?

Only team members configured as reviewers in the `production` environment can approve deployments.

**To configure reviewers (one-time setup):**

1. Go to **Settings → Environments**
2. Click **"production"** environment
3. Under **"Deployment protection rules"**:
   - ✅ Enable **"Required reviewers"**
   - Add authorized team members
   - Set wait timer (optional, default: 0 minutes)

### How to Approve

1. **Navigate to Actions**
   - Go to GitHub repository
   - Click **"Actions"** tab
   - Find the **"Deploy to Production"** workflow run

2. **Review the deployment**
   - Click on the workflow run
   - Review the staging verification results
   - Check the commits being deployed
   - Verify pre-deployment checks passed

3. **Approve or reject**
   - Click **"Review deployments"** button
   - Select **"production"** environment
   - Add optional comment
   - Click **"Approve and deploy"** or **"Reject"**

### Approval Timeout

- Deployments wait indefinitely for approval
- No automatic timeout (manual intervention required)
- Can be rejected at any time before approval

---

## Post-Deployment Verification

After deployment completes:

### 1. Verify Site is Accessible
```bash
curl -I https://propertypeak.in
# Should return HTTP 200 or 302
```

### 2. Check Critical Features

- [ ] Homepage loads correctly
- [ ] User authentication works
- [ ] Property search functions
- [ ] Contact forms submit
- [ ] CRM dashboard accessible
- [ ] WhatsApp bot responds (if applicable)

### 3. Monitor Error Logs

```bash
# SSH into production server
ssh propertypeak@propertypeak.in

# Check Laravel logs
cd ~/htdocs/www.propertypeak.in
tail -f storage/logs/laravel.log

# Check web server logs
tail -f /var/log/apache2/error.log  # or nginx
```

### 4. Monitor for 15-30 Minutes

- Watch for error spikes
- Check user reports
- Monitor server resources

---

## Rollback Procedures

### When to Rollback

Rollback if:
- Critical functionality is broken
- Site is inaccessible
- Data corruption detected
- Security vulnerability introduced
- Performance severely degraded

### Emergency Rollback

**Fast rollback using the script:**

```bash
# SSH into production
ssh propertypeak@propertypeak.in

# Navigate to project directory
cd ~/htdocs/www.propertypeak.in

# Run rollback script
bash scripts/rollback-production.sh

# Follow prompts:
# 1. Type "YES" to confirm
# 2. Enter commit hash or tag to rollback to
# 3. Type "YES" again to confirm

# Clear caches
php artisan optimize:clear
php artisan optimize

# Verify site is working
```

### Finding Rollback Target

**Get recent commits:**
```bash
git log --oneline -10
```

**Get previous deployment commit:**
```bash
# Check deployment summary in GitHub Actions
# Look for "Previous commit: <hash>"
```

### Manual Rollback (Alternative)

```bash
ssh propertypeak@propertypeak.in
cd ~/htdocs/www.propertypeak.in

# Find the commit to rollback to
git log --oneline -5

# Reset to specific commit
git reset --hard <commit-hash>

# Clear caches
php artisan optimize:clear
php artisan optimize
```

### Post-Rollback Actions

1. **Verify site is working**
2. **Notify team of rollback**
3. **Investigate root cause**
4. **Fix issue on feature branch**
5. **Test on staging**
6. **Re-deploy when ready**

---

## Deployment Workflow Summary

```
┌─────────────────────────────────────────┐
│  1. Test on Staging                     │
│     https://staging.propertypeak.in     │
└────────────────┬────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────┐
│  2. Run Promotion Workflow              │
│     GitHub → Actions → Promote          │
└────────────────┬────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────┐
│  3. Automated Checks                    │
│     ✓ Staging health                    │
│     ✓ Merge staging → main              │
│     ✓ Pre-deployment checks             │
└────────────────┬────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────┐
│  4. Manual Approval Required ⏸️         │
│     Review → Approve → Deploy           │
└────────────────┬────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────┐
│  5. Production Deployment ✅            │
│     Automatic after approval            │
└────────────────┬────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────┐
│  6. Verify Production                   │
│     Test features + Monitor logs        │
└─────────────────────────────────────────┘
```

---

## Troubleshooting

### Deployment Stuck on Approval

**Problem:** Workflow waiting for approval but no button visible

**Solution:**
1. Refresh the GitHub Actions page
2. Ensure you're logged in as an authorized reviewer
3. Check the "Environments" section on the workflow run page

### Approval Button Not Visible

**Problem:** Can't see "Review deployments" button

**Solution:**
- You're not configured as a reviewer
- Ask repository admin to add you to production environment reviewers

### Deployment Failed After Approval

**Problem:** Deployment approved but failed during execution

**Solution:**
1. Check the error logs in GitHub Actions
2. SSH into server and check application logs
3. Verify server is accessible
4. Consider rollback if production is broken

### Staging Verification Failed

**Problem:** Workflow fails at staging verification step

**Solution:**
1. Check staging server is accessible
2. Verify staging branch exists
3. Ensure staging has recent commits
4. Fix staging issues before promoting

---

## Best Practices

### Timing

- **Avoid deployments during:**
  - Peak traffic hours
  - Weekends (unless emergency)
  - Late nights (reduced monitoring capability)

- **Best deployment windows:**
  - Early morning (low traffic)
  - Mid-afternoon (team available)
  - After thorough staging testing

### Communication

- **Before deployment:**
  - Notify team in Slack/communication channel
  - Mention expected downtime (if any)
  - List major changes being deployed

- **After deployment:**
  - Confirm deployment success
  - Report any issues encountered
  - Update team on monitoring status

### Testing

- **Always test on staging first**
- **Wait at least 1 hour** after staging deployment
- **Test all critical user flows**
- **Check database migrations** (if any)

---

## Emergency Contacts

If deployment issues occur:

1. **Check GitHub Actions logs** first
2. **Review server logs** via SSH
3. **Contact team lead** if unsure
4. **Rollback immediately** if critical

---

## Additional Resources

- [Developer Safety Guide](DEVELOPER_SAFETY.md) - Git safety rules
- [Branching Guide](BRANCHING_GUIDE.md) - Branch workflow
- [Rollback Script](../scripts/rollback-production.sh) - Emergency rollback
- [GitHub Actions](../.github/workflows/) - Workflow definitions
