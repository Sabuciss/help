# 🔧 IT Help Desk - Troubleshooting & Q&A

## ❓ Bieži Uzdotie Jautājumi

### Q1: Kā sākt darboties ar sistēmu?

**A:** Seko šiem soļiem:
```bash
# 1. Veikthi datubāzes migrācijas
php artisan migrate

# 2. Izsēj testa datus
php artisan db:seed

# 3. Palaist serveri
php artisan serve

# 4. Atvērt pārlūkprogrammā
# http://localhost:8000

# 5. Pieslēgties ar test kontiem
# User: janis@example.com / password
# Admin: anna@example.com / password
```

### Q2: Kā pievienot jaunu administratoru?

**A:** Izmantojot artisan tinker:
```bash
php artisan tinker

# Palaist šīs komandas:
>>> use App\Models\User;
>>> User::create([
...   'name' => 'Jaunas Admin',
...   'email' => 'newadmin@example.com',
...   'password' => bcrypt('password'),
...   'role' => 'admin',
...   'department' => 'IT Support'
... ]);
```

### Q3: Kā dzēst visus testa datus?

**A:** 
```bash
php artisan tinker

# Dzēst visu
>>> User::truncate();
>>> Ticket::truncate();
>>> TicketComment::truncate();
>>> TicketAttachment::truncate();
```

### Q4: Kā mainīt lietotāja lomu?

**A:**
```bash
php artisan tinker

>>> $user = User::find(1);
>>> $user->update(['role' => 'admin']);
>>> // vai
>>> $user->update(['role' => 'user']);
```

### Q5: Kur tiek glabāti augšupielādētie faili?

**A:** Faili tiek glabāti šajā mapē:
```
storage/app/public/tickets/{ticket_id}/{filename}
```

Publiskā piekļuve:
```
http://localhost:8000/storage/tickets/{ticket_id}/{filename}
```

### Q6: Kāds ir maksimālais faila lielums augšupielādei?

**A:** Pašlaik iestatīts uz **10 MB**. Lai mainītu, rediģējiet:

[TicketController.php](TicketController.php#L60)
```php
'attachments.*' => 'file|max:10240', // 10240 KB = 10 MB
```

Mainiet uz piemēram 20480 (20 MB):
```php
'attachments.*' => 'file|max:20480',
```

### Q7: Kā palaist migrācijas atpakaļ?

**A:**
```bash
# Atpakaļ visas migrācijas
php artisan migrate:rollback

# Atpakaļ un atkārtot
php artisan migrate:refresh

# Atpakaļ ar seedēšanu
php artisan migrate:refresh --seed
```

### Q8: Kā skatīt datubāzes pieprasījumus (queries)?

**A:** Rediģējiet [TicketController.php](TicketController.php#L1) un pievienojiet:
```php
use Illuminate\Support\Facades\Log;
use DB;

// Sākumā kontrolliera
DB::listen(function ($query) {
    Log::info($query->sql);
    Log::info($query->bindings);
});
```

### Q9: Kā nodrošināt SSL (HTTPS)?

**A:** `.env` failā iestatiet:
```env
APP_URL=https://yourdomain.com
SESSION_SECURE_COOKIES=true
```

Apache konfigurācijā pievieno:
```apache
<VirtualHost *:443>
    SSLEngine on
    SSLCertificateFile /path/to/cert.pem
    # ...
</VirtualHost>
```

### Q10: Kādi ir biļešu filtri?

**A:** Pašlaik filtri nav ieviesti, bet varat pievienot:

```php
// TicketController.php
public function index(Request $request) {
    $tickets = Auth::user()->tickets();
    
    if ($request->status) {
        $tickets = $tickets->where('status', $request->status);
    }
    
    if ($request->priority) {
        $tickets = $tickets->where('priority', $request->priority);
    }
    
    return view('tickets.index', [
        'tickets' => $tickets->paginate(10)
    ]);
}
```

## 🐛 Problēmu Novēršana

### Problema 1: "SQLSTATE[HY000]: General error"

**Iemesls**: Datubāzes savienojuma problēma

**Risinājums**:
```bash
# Pārbaudiet .env datubāzes iestatījumus
# Pārbaudiet, vai MySQL darbojas
# Pārbaudiet autentificēšanas datus

# Restart MySQL:
# Windows: net stop MySQL80 && net start MySQL80
# Mac: brew services restart mysql@5.7
```

### Problema 2: "No query results for model"

**Iemesls**: Biļete neeksistē vai lietotājs nav autorizēts

**Risinājums**:
```bash
# Pārbaudiet, vai biļete eksistē
php artisan tinker
>>> Ticket::find(1)

# Pārbaudiet lietotāja autorizāciju
>>> auth()->check()
>>> auth()->user()
```

### Problema 3: Faili neparādās augšupielādēšanas laikā

**Iemesls**: Storage symlink nav izveidots

**Risinājums**:
```bash
# Izveidojiet symlink
php artisan storage:link

# Pārbaudiet tiesības:
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Problema 4: 419 Session Expired

**Iemesls**: CSRF token ir bijis derīgs pārāk ilgi vai sesija ir beigusies

**Risinājums**:
```bash
# Attīriet aplikācijas kešu
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Problema 5: Biļetes neparādās lapā

**Iemesls**: Datubāzes query problēma

**Risinājums**:
```bash
# Pārbaudiet datubāzes saturu
php artisan tinker
>>> Ticket::all()
>>> Ticket::count()

# Pārbaudiet, vai seeders tika palaists
php artisan db:seed
```

### Problema 6: Admin skatījums neparāda biļetes

**Iemesls**: Lietotājs nav ar admin lomu

**Risinājums**:
```bash
php artisan tinker
>>> $user = User::find(1);
>>> $user->role = 'admin';
>>> $user->save();
```

### Problema 7: "No Application Encryption Key"

**Iemesls**: APP_KEY nav ģenerēts

**Risinājums**:
```bash
php artisan key:generate
```

### Problema 8: "Target class not found"

**Iemesls**: Route nevarēja atrast kontrollieri

**Risinājums**:
```bash
# Attīriet route cache
php artisan route:clear

# Pārbaudiet route definīcijas
php artisan route:list
```

### Problema 9: CSS/JS neparādās pareizi

**Iemesls**: Assets nav attīrīti no keša

**Risinājums**:
```bash
# Attīriet keši
php artisan cache:clear
php artisan view:clear

# Atjaunojiet lapu (Ctrl+Shift+R)
```

### Problema 10: Email notifikācijas nedarbojas

**Iemesls**: SMTP nav konfigurēts

**Risinājums**: Redактējiet `.env`:
```env
MAIL_DRIVER=log  # Pagaidām lietojiet log
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="IT Help Desk"
```

## 📊 Performanses Optimizācija

### 1. Datubāzes Queries Optimizācija

```php
// ❌ NEPAREIZI - N+1 Query Problem
$tickets = Ticket::all();
foreach ($tickets as $ticket) {
    echo $ticket->user->name; // Atsevišķa query katrai biļetei!
}

// ✅ PAREIZI - Eager Loading
$tickets = Ticket::with('user', 'comments', 'attachments')->get();
foreach ($tickets as $ticket) {
    echo $ticket->user->name; // Viens query
}
```

### 2. Caching

```php
// Keš biļešu sarakstu
use Illuminate\Support\Facades\Cache;

$tickets = Cache::remember('user_tickets', 3600, function() {
    return Auth::user()->tickets()->get();
});
```

### 3. Datubāzes Indeksi

Jau iestatīti uz:
- `user_id` - biļešu filtrēšanai
- `status` - statusu meklēšanai
- `assigned_to` - piešķirto biļešu meklēšanai

### 4. Lapošanas Optimizācija

```php
// Pagaidām: 10-15 biļetes uz lapu
$tickets = Ticket::paginate(15);

// Var mainīt uz
$tickets = Ticket::paginate(50); // Lielāks skaits
```

## 📚 Noderīgi Artisan Komandi

```bash
# Pamatkomandi
php artisan serve                   # Palaist serveri
php artisan migrate                 # Migrācijas
php artisan db:seed                 # Seedēšana
php artisan make:model Model        # Jauns modelis
php artisan make:controller Name    # Jauns controllers
php artisan make:migration table     # Jauna migrācija

# Attīrīšanas komandi
php artisan cache:clear             # Attīrīt keši
php artisan route:clear             # Attīrīt route keši
php artisan config:clear            # Attīrīt config keši
php artisan view:clear              # Attīrīt view keši

# Datubāzes komandi
php artisan tinker                  # Interactive shell
php artisan migrate:rollback        # Undo migrācijas
php artisan migrate:refresh         # Refresh all
php artisan migrate:fresh           # Fresh start (DANGERS!)

# Debugging
php artisan route:list              # Visu maršrutu saraksts
php artisan config:show             # Konfigurācijas skats
```

## 🔍 Debug Rezīms

### Ieslēgt Debug Rezīmu

`.env` failā:
```env
APP_DEBUG=true
```

### Debugging uz Lapas

```php
// app/Http/Controllers/TicketController.php
use Illuminate\Support\Facades\Log;

public function index() {
    Log::info('User ID: ' . Auth::id());
    Log::info('User Role: ' . Auth::user()->role);
    
    $tickets = Auth::user()->tickets()->latest()->paginate(10);
    
    Log::info('Tickets count: ' . $tickets->total());
    
    return view('tickets.index', compact('tickets'));
}
```

Skatiet logs: `storage/logs/laravel.log`

## 🚀 Produkcijā Deployment

Pirms deployment:
- [ ] Iestatieties `APP_DEBUG=false`
- [ ] Iestatieties `APP_ENV=production`
- [ ] Ģenerējiet `APP_KEY`
- [ ] Palaist `php artisan config:cache`
- [ ] Palaist `php artisan route:cache`
- [ ] Palaist `php artisan view:cache`
- [ ] Konfigurejiet SSL/HTTPS
- [ ] Iestatieties pareizos `.env` mainīgos

## 📞 Saziņa

Jautājumi vai problēmas? Kontaktējiet IT support:
- Email: support@example.com
- Tālrunis: +371 XXXXXXXXX

---

**Būtu problēmas? Paaugstiniet GitHub issue vai kontaktējiet izstrādātāju!**
