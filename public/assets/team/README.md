# Foto anggota tim (Credits page)

Drop real member photos here. Filename must match the `'avatar'` path in
`app/Http/Controllers/PageController.php` → `credits()`.

Current expected files:
- fulan-rahman.jpg
- aisyah-putri.jpg
- nadia-salsabila.jpg
- zaki-alfarizi.jpg
- anggota-5.jpg
- anggota-6.jpg
- anggota-7.jpg

Until a file exists, the credits card shows a person-icon placeholder
(accent-tinted circle). Dropping the file in (with the matching name) is
enough — no code change needed. To use a different filename, update the
`'avatar'` line for that member in the controller.
