$files = Get-ChildItem -Path "c:\Users\qkiki\Documents\RSCM\DIKLAT\sipelatih3\sipelatih\resources\views" -Recurse -Filter "*.blade.php"

foreach ($file in $files) {
    $content = Get-Content $file.FullName -Raw
    
    $modified = $false
    
    # regex for matching "+ Tambah Something" or "＋ Tambah Something" or "➕ Tambah Something"
    $pattern = '(?m)^(\s*)(?:\+|＋|➕)\s*(Tambah\s+.*)$'
    
    if ($content -match $pattern) {
        $replacement = '$1<svg class="w-5 h-5 mr-2 inline-block -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">`n$1    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>`n$1</svg>`n$1$2'
        $newContent = [regex]::Replace($content, $pattern, $replacement)
        
        if ($newContent -cne $content) {
            Set-Content -Path $file.FullName -Value $newContent -NoNewline
            Write-Host "Updated $($file.FullName)"
        }
    }
}
