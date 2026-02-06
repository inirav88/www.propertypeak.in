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

# Open PR on GitHub
# Get approval + CI pass
# Merge PR
# Deployment happens automatically
```

---

## Need Help?

- Review the PR template checklist before opening PRs
- Check `docs/DEVELOPER_SAFETY.md` for safety guidelines
- Ask team leads if unsure about workflow
