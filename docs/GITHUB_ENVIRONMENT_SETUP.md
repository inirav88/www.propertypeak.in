# GitHub Environment Setup Guide

## One-Time Setup Required

To enable manual approval for production deployments, you need to configure a GitHub Environment.

---

## Steps to Configure Production Environment

### 1. Navigate to Repository Settings

1. Go to your GitHub repository: `https://github.com/YOUR_USERNAME/www.propertypeak.in`
2. Click **"Settings"** tab (top right)
3. In the left sidebar, click **"Environments"**

### 2. Create Production Environment

1. Click **"New environment"** button
2. Name: `production` (exactly as shown, lowercase)
3. Click **"Configure environment"**

### 3. Configure Protection Rules

#### Required Reviewers

1. Under **"Deployment protection rules"**
2. Check ✅ **"Required reviewers"**
3. Click **"Add reviewers"**
4. Add team members who should approve production deployments:
   - Repository administrators
   - Senior developers
   - DevOps team members
5. Click **"Save protection rules"**

#### Optional: Wait Timer

1. Check ✅ **"Wait timer"** (optional)
2. Set minutes to wait before deployment (e.g., 5 minutes)
3. This adds a cooling-off period before deployment

#### Deployment Branches

1. Under **"Deployment branches"**
2. Select **"Selected branches"**
3. Add rule: `main`
4. This ensures only `main` branch can deploy to production

### 4. Save Configuration

1. Click **"Save protection rules"**
2. Environment is now configured ✅

---

## Verification

### Test the Approval Workflow

1. **Create a test commit to main:**
   ```bash
   git checkout main
   git commit --allow-empty -m "test: verify approval workflow"
   git push origin main
   ```

2. **Check GitHub Actions:**
   - Go to **Actions** tab
   - Find the **"Deploy to Production"** workflow
   - Workflow should show **"Waiting"** status
   - You should see **"Review deployments"** button

3. **Test approval:**
   - Click **"Review deployments"**
   - Select **"production"** environment
   - Click **"Approve and deploy"**
   - Workflow should continue and deploy

4. **Clean up test commit (optional):**
   ```bash
   git reset --hard HEAD~1
   git push origin main --force
   ```
   ⚠️ Only do this if it was a test commit with no real changes

---

## Who Can Approve Deployments?

### Default Permissions

- Repository administrators (always)
- Users explicitly added as reviewers

### Adding/Removing Reviewers

1. Go to **Settings → Environments → production**
2. Under **"Required reviewers"**
3. Click **"Edit"** (pencil icon)
4. Add or remove team members
5. Click **"Save protection rules"**

---

## Environment Configuration Summary

| Setting | Recommended Value | Purpose |
|---------|------------------|---------|
| **Environment name** | `production` | Matches workflow configuration |
| **Required reviewers** | ✅ Enabled | Requires manual approval |
| **Reviewers** | 1-3 team members | Who can approve |
| **Wait timer** | 0-5 minutes (optional) | Cooling-off period |
| **Deployment branches** | `main` only | Restricts to production branch |

---

## Troubleshooting

### "Review deployments" button not visible

**Problem:** Can't see approval button in GitHub Actions

**Solutions:**
1. Ensure you're logged in as an authorized reviewer
2. Check you're on the correct workflow run page
3. Verify the environment name is exactly `production`
4. Refresh the page

### Workflow doesn't pause for approval

**Problem:** Deployment runs without waiting

**Solutions:**
1. Verify environment name in workflow file is `production`
2. Check environment exists in Settings → Environments
3. Ensure "Required reviewers" is enabled
4. Verify deployment branch is set to `main`

### Can't add reviewers

**Problem:** Unable to add team members as reviewers

**Solutions:**
1. Ensure you have admin access to the repository
2. Verify team members have at least read access
3. Try adding by username instead of email

---

## Security Best Practices

### Reviewer Selection

- **Minimum:** 1 reviewer (yourself if solo)
- **Recommended:** 2-3 reviewers for redundancy
- **Include:** Senior developers, DevOps, team leads

### Review Process

- **Always review:**
  - Commits being deployed
  - Staging verification results
  - Pre-deployment check results
- **Never approve if:**
  - Staging is broken
  - Critical bugs are open
  - You haven't reviewed the changes

### Emergency Access

- Ensure at least 2 people can approve deployments
- Document emergency contact information
- Have rollback plan ready

---

## Next Steps

After configuring the environment:

1. ✅ Test the approval workflow (see Verification section)
2. ✅ Notify team members they've been added as reviewers
3. ✅ Share the [Production Deployment Guide](PRODUCTION_DEPLOYMENT.md)
4. ✅ Document who can approve in team wiki/docs

---

## Additional Resources

- [GitHub Environments Documentation](https://docs.github.com/en/actions/deployment/targeting-different-environments/using-environments-for-deployment)
- [Production Deployment Guide](PRODUCTION_DEPLOYMENT.md)
- [Developer Safety Guide](DEVELOPER_SAFETY.md)
