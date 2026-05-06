$files = Get-ChildItem -Path "c:\Users\qkiki\Documents\RSCM\DIKLAT\sipelatih3\sipelatih\resources\views" -Recurse -Filter "*.blade.php"

foreach ($file in $files) {
    $content = Get-Content $file.FullName -Raw
    
    if ($content -match '`n') {
        $newContent = $content.Replace('`n', "`n")
        Set-Content -Path $file.FullName -Value $newContent -NoNewline
        Write-Host "Fixed $($file.FullName)"
    }
}
