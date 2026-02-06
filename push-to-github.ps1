# Git Push to GitHub Script
Set-Location "c:\xampp\htdocs\AMVRS ARMED"

# Configure git credential helper for Windows
git config --global credential.helper manager

# Try to push
Write-Host "Pushing to GitHub..."
git push -u origin main --force

if ($LASTEXITCODE -eq 0) {
    Write-Host "Push successful!"
} else {
    Write-Host "Push failed. Opening browser for authentication..."
    # If push failed, try with browser auth
    git push -u origin main --force
}
