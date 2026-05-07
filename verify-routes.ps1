$ErrorActionPreference = 'SilentlyContinue'
$base = 'http://127.0.0.1:8000'

function NewSession() { return New-Object Microsoft.PowerShell.Commands.WebRequestSession }

function Login($sess, $email) {
    $r = Invoke-WebRequest -UseBasicParsing -WebSession $sess -Uri "$base/login" -Method GET
    if ($r.Content -match 'name="_token" value="([^"]+)"') { $tok = $Matches[1] } else { return }
    $body = @{ _token = $tok; email = $email; password = 'password' }
    try {
        $r2 = Invoke-WebRequest -UseBasicParsing -WebSession $sess -Uri "$base/login" -Method POST -Body $body -MaximumRedirection 0
        Write-Host ("LOGIN $email => " + $r2.StatusCode)
    } catch {
        if ($_.Exception.Response) { Write-Host ("LOGIN $email => " + [int]$_.Exception.Response.StatusCode) }
    }
}

function Hit($sess, $path) {
    try {
        $r = Invoke-WebRequest -UseBasicParsing -WebSession $sess -Uri ($base + $path) -Method GET -MaximumRedirection 0
        Write-Host ("GET $path => " + $r.StatusCode)
    } catch {
        if ($_.Exception.Response) {
            Write-Host ("GET $path => " + [int]$_.Exception.Response.StatusCode)
        } else {
            Write-Host ("GET $path => ERROR " + $_.Exception.Message)
        }
    }
}

function Register($sess, $name, $email, $password) {
    $r = Invoke-WebRequest -UseBasicParsing -WebSession $sess -Uri "$base/register" -Method GET
    if ($r.Content -match 'name="_token" value="([^"]+)"') { $tok = $Matches[1] } else { Write-Host "REGISTER => no CSRF"; return }
    $body = @{ _token = $tok; name = $name; email = $email; password = $password; password_confirmation = $password }
    try {
        $r2 = Invoke-WebRequest -UseBasicParsing -WebSession $sess -Uri "$base/register" -Method POST -Body $body -MaximumRedirection 0
        $loc = $r2.Headers.Location
        Write-Host ("REGISTER $email => " + $r2.StatusCode + " -> " + $loc)
    } catch {
        if ($_.Exception.Response) {
            $code = [int]$_.Exception.Response.StatusCode
            $loc = $_.Exception.Response.Headers['Location']
            Write-Host ("REGISTER $email => " + $code + " -> " + $loc)
        } else {
            Write-Host ("REGISTER $email => ERROR " + $_.Exception.Message)
        }
    }
}

Write-Host "=== REGISTER ==="
$s = NewSession
$ts = [int][double]::Parse((Get-Date -UFormat %s))
Register $s 'Test User' "register-$ts@example.com" 'Password123!'
Hit $s '/dashboard'         # owner gets 302 -> /customer/vehicles
Hit $s '/customer/vehicles' # confirm landing page is reachable

Write-Host "=== ADMIN ==="
$s = NewSession
Login $s 'admin@example.com'
Hit $s '/dashboard'
Hit $s '/vehicles'
Hit $s '/vehicles/create/modal'
Hit $s '/vehicles/1/modal'
Hit $s '/vehicles/1/edit/modal'
Hit $s '/admin/appointments'
Hit $s '/admin/appointments/create/modal'
Hit $s '/admin/appointments/1/modal'
Hit $s '/admin/appointments/1/edit/modal'
Hit $s '/admin/maintenance'
Hit $s '/admin/maintenance/1/modal'
Hit $s '/admin/maintenance/1/edit/modal'
Hit $s '/reports'
Hit $s '/admin/users'
Hit $s '/admin/users/create/modal'
Hit $s '/admin/users/1/edit/modal'
Hit $s '/admin/users/suggest?q=a'
Hit $s '/admin/appointments/suggest?q=a'
Hit $s '/vehicles/suggest?q=a'

Write-Host "=== MECHANIC ==="
$s = NewSession
Login $s 'mechanic@example.com'
Hit $s '/mechanic/tasks'
Hit $s '/mechanic/in-progress'
Hit $s '/mechanic/completed'
Hit $s '/mechanic/team-overview'
Hit $s '/mechanic/team-reports'
Hit $s '/mechanic/customer-vehicles'
# Use schedule ids that belong to Mike Mechanic; just hit the first 3 schedules
Hit $s '/mechanic/tasks/1/details/modal'
Hit $s '/mechanic/tasks/1/start/modal'
Hit $s '/mechanic/tasks/1/assign/modal'
Hit $s '/mechanic/tasks/19/note/modal'
Hit $s '/mechanic/tasks/19/complete/modal'
Hit $s '/mechanic/tasks/1/report/modal'

Write-Host "=== CUSTOMER ==="
$s = NewSession
Login $s 'customer@example.com'
Hit $s '/customer/vehicles'
Hit $s '/customer/appointments'
Hit $s '/customer/service-history'
Hit $s '/customer/vehicles/add/modal'
Hit $s '/customer/appointments/book/modal'
Hit $s '/customer/vehicles/1/modal'
Hit $s '/customer/vehicles/1/schedule/modal'
