# Branching Guide

## ⚠️ CRITICAL RULE

**Direct work on `staging` is FORBIDDEN.**

All development must happen on `feature/*` branches.

---

## Branch Types

| Branch | Purpose | Direct Commits |
|--------|---------|----------------|
| `main` | Production | ❌ Never |
| `staging` | Integration only (PR target) | ❌ Never |
| `feature/*` | All development | ✅ Yes |

---

## Required Workflow

### 1. Create Feature Branch from Staging

```bash
git checkout staging
git pull origin staging
git checkout -b feature/your-feature-name
```

### 2. Commit Changes Only on Feature Branch

```bash
# Make your changes
git add .
git commit -m "feat: description of changes"
```

### 3. Push Feature Branch

```bash
git push -u origin feature/your-feature-name
```

### 4. Open Pull Request → staging

- Go to GitHub
- Create PR: `feature/your-feature-name` → `staging`
- Fill out the PR template checklist
- Request review

### 5. CI Must Pass

- GitHub Actions will run automatically
- The `deploy` status check must pass
- Fix any failures before merging

### 6. PR Approval Required

- At least 1 approval is required
- Reviewers with write access must approve
- Address any review comments

### 7. Merge PR

- Once approved and CI passes, merge the PR
- Deployment to staging happens automatically
- Delete the feature branch after merge

---

## Workflow Diagram

```
┌─────────────┐
│   staging   │ ◄─── Pull from here
└──────┬──────┘
       │
       │ git checkout -b feature/xyz
       ▼
┌─────────────┐
│ feature/xyz │ ◄─── Work here (commits allowed)
└──────┬──────┘
       │
       │ git push origin feature/xyz
       ▼
┌─────────────┐
│   GitHub    │
│  Open PR    │ ◄─── Create PR to staging
└──────┬──────┘
       │
       │ CI checks + Approval
       ▼
┌─────────────┐
│  Merge PR   │ ──► Deploys to staging automatically
└─────────────┘
```

---

## ⚠️ What Happens If You Try to Push Directly to Staging?

**GitHub will reject your push with an error:**

```
remote: error: GH013: Repository rule violations found for refs/heads/staging.
! [remote rejected] staging -> staging (push declined due to repository rule violations)
error: failed to push some refs
```

This is by design. **Always use feature branches and PRs.**

---

## 🚀 Staging to Production Promotion

Once changes are tested on staging, they can be promoted to production.

### Option 1: Promotion Workflow (Recommended)

**Easiest and safest method:**

1. Go to **GitHub → Actions**
2. Select **"Promote Staging to Production"**
3. Click **"Run workflow"**
4. Type **"PROMOTE"** to confirm
5. Wait for workflow to complete
6. **Approve the production deployment** when prompted

The workflow will:
- ✅ Verify staging is healthy
- ✅ Automatically merge `staging` → `main`
- ⏸️ Pause for manual approval
- ✅ Deploy to production after approval

### Option 2: Manual Merge

**For advanced users:**

```bash
# Ensure you're up to date
git checkout staging
git pull origin staging

git checkout main
git pull origin main

# Merge staging into main
git merge staging

# Push to main (triggers production workflow)
git push origin main

# Go to GitHub Actions and approve deployment
```

### ⚠️ Important: Manual Approval Required

**Production deployments require manual approval.** After pushing to `main`:

1. Go to **GitHub → Actions**
2. Find the **"Deploy to Production"** workflow
3. Click **"Review deployments"**
4. Approve the deployment

See [Production Deployment Guide](PRODUCTION_DEPLOYMENT.md) for detailed instructions.

---

## Complete Workflow Diagram

```
┌─────────────┐
│   staging   │ ◄─── Pull from here
└──────┬──────┘
       │
       │ git checkout -b feature/xyz
       ▼
┌─────────────┐
│ feature/xyz │ ◄─── Work here (commits allowed)
└──────┬──────┘
       │
       │ git push origin feature/xyz
       ▼
┌─────────────┐
│   GitHub    │
│  Open PR    │ ◄─── Create PR to staging
└──────┬──────┘
       │
       │ CI checks + Approval
       ▼
┌─────────────┐
│  Merge PR   │ ──► Deploys to staging automatically
└──────┬──────┘
       │
       │ Test on staging
       ▼
┌─────────────┐
│  Promote    │ ◄─── Use promotion workflow OR manual merge
│ to Production│
└──────┬──────┘
       │
       │ Manual approval required ⏸️
       ▼
┌─────────────┐
│ Production  │ ──► Deploys after approval ✅
└─────────────┘
```

---

## Quick Reference

```bash
# Start new feature
git checkout staging
git pull origin staging
git checkout -b feature/my-feature

# Work on feature
git add .
git commit -m "feat: my changes"
git push -u origin feature/my-feature

# Open PR on GitHub → staging
# Get approval + CI pass
# Merge PR → staging auto-deploys

# Test on staging

# Promote to production (use GitHub Actions workflow)
# OR manually: git checkout main && git merge staging && git push

# Approve deployment in GitHub Actions
```

---

## Need Help?

- Review the PR template checklist before opening PRs
- Check `docs/DEVELOPER_SAFETY.md` for safety guidelines
- Check `docs/PRODUCTION_DEPLOYMENT.md` for deployment process
- Ask team leads if unsure about workflow
