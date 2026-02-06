# Developer Safety Guide

## 🛡️ Git Hygiene and Safety Rules

This document outlines critical safety practices to prevent accidental damage to protected branches and maintain repository integrity.

---

## ⚠️ NEVER Use Force Push on Protected Branches

### What is Force Push?

```bash
git push --force
git push -f
git push --force-with-lease
```

These commands **rewrite history** and can cause data loss.

### Why is it Dangerous?

- Overwrites commits on the remote branch
- Can delete other developers' work
- Breaks the commit history
- Causes merge conflicts for the entire team

### Protected Branches

The following branches are protected and **will reject force pushes**:

- `main` (production)
- `staging` (integration)

### What Happens If You Try?

**GitHub will reject the force push:**

```
remote: error: GH013: Repository rule violations found
! [remote rejected] staging -> staging (protected branch hook declined)
error: failed to push some refs
```

---

## ✅ Safe Alternatives to Force Push

### If You Need to Fix a Commit

**Instead of force push, create a new commit:**

```bash
# Make your fix
git add .
git commit -m "fix: corrected previous commit"
git push origin feature/your-branch
```

### If You Need to Undo Changes

**Use `git revert` instead of `git reset --hard`:**

```bash
# Revert the last commit (creates new commit)
git revert HEAD
git push origin feature/your-branch
```

### If Your Branch is Behind

**Merge or rebase, don't force push:**

```bash
# Option 1: Merge
git pull origin staging
git push origin feature/your-branch

# Option 2: Rebase (only on YOUR feature branch)
git fetch origin
git rebase origin/staging
git push origin feature/your-branch
```

---

## 🚫 Never Work Directly on Protected Branches

### Forbidden Actions

```bash
# ❌ NEVER do this
git checkout staging
git commit -m "quick fix"
git push origin staging  # This will be REJECTED

# ❌ NEVER do this
git checkout main
git commit -m "hotfix"
git push origin main  # This will be REJECTED
```

### Correct Workflow

```bash
# ✅ ALWAYS do this
git checkout staging
git pull origin staging
git checkout -b feature/quick-fix
git commit -m "fix: description"
git push -u origin feature/quick-fix
# Then open a PR on GitHub
```

---

## 🔒 Branch Protection Enforcement

GitHub enforces these rules automatically:

| Rule | Effect |
|------|--------|
| No direct pushes | All changes must go through PRs |
| Require PR approval | At least 1 reviewer must approve |
| Require status checks | CI must pass before merge |
| No force pushes | History rewriting is blocked |
| No deletions | Protected branches cannot be deleted |

**You cannot bypass these rules.** They exist to protect production and staging environments.

---

## 📋 Pre-Push Checklist

Before pushing code, verify:

- [ ] I am on a `feature/*` branch (not `staging` or `main`)
- [ ] I am NOT using `--force` or `-f` flags
- [ ] My commits have clear, descriptive messages
- [ ] I have tested my changes locally
- [ ] I have pulled the latest changes from `staging`

---

## 🆘 Common Mistakes and Solutions

### Mistake 1: Committed to `staging` by Accident

**Solution:**

```bash
# Don't push! Create a new branch from current state
git checkout -b feature/accidental-commits

# Reset staging to match remote
git checkout staging
git reset --hard origin/staging

# Switch back to feature branch and push
git checkout feature/accidental-commits
git push -u origin feature/accidental-commits
```

### Mistake 2: Need to Update PR After Review

**Solution:**

```bash
# Make changes on your feature branch
git checkout feature/your-branch
# Make changes
git add .
git commit -m "fix: address review comments"
git push origin feature/your-branch
# PR updates automatically
```

### Mistake 3: Feature Branch is Behind Staging

**Solution:**

```bash
git checkout feature/your-branch
git fetch origin
git merge origin/staging
# Resolve any conflicts
git push origin feature/your-branch
```

---

## 🎯 Summary

1. **Never force push** to `main` or `staging`
2. **Never commit directly** to `main` or `staging`
3. **Always use feature branches** for development
4. **Always open PRs** for code review
5. **Wait for CI and approval** before merging

Following these rules keeps the repository safe and the team productive.

---

## Need Help?

- Review `docs/BRANCHING_GUIDE.md` for workflow details
- Check the PR template for checklist items
- Ask team leads if you're unsure about a git operation
