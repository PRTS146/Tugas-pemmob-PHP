<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CRUD Mahasiswa</title>
<style>
  body { font-family: Arial, sans-serif; max-width: 900px; margin: 24px auto; padding: 0 12px; }
  h1 { margin-bottom: 8px; }
  .nav { display: flex; gap: 8px; margin-bottom: 16px; flex-wrap: wrap; }
  a.btn, button { padding: 8px 14px; border: 0; border-radius: 6px; background: #2563eb; color: #fff;
                  cursor: pointer; text-decoration: none; font-size: 14px; }
  button.del { background: #dc2626; }
  button.edit { background: #d97706; }
  button.gray, a.gray { background: #6b7280; }
  form { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin-bottom: 16px; }
  input { padding: 8px; border: 1px solid #ccc; border-radius: 6px; }
  .actions { grid-column: span 2; display: flex; gap: 8px; }
  table { width: 100%; border-collapse: collapse; }
  th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
  th { background: #f3f4f6; }
  #msg { margin: 8px 0; font-weight: bold; }
</style>
</head>
<body>

<h1>CRUD Mahasiswa</h1>

<div class="nav">
  <a class="btn" href="read.php" target="_blank">Buka read.php (JSON)</a>
  <a class="btn gray" href="http://localhost/phpmyadmin" target="_blank">phpMyAdmin</a>
  <button class="gray" onclick="loadData()">Refresh</button>
</div>

<div id="msg"></div>

<form id="form">
  <input type="hidden" id="id">
  <input id="nim" placeholder="NIM" required>
  <input id="nama" placeholder="Nama" required>
  <input id="jurusan" placeholder="Jurusan">
  <input id="email" type="email" placeholder="Email">
  <div class="actions">
    <button type="submit" id="btnSave">Tambah</button>
    <button type="button" class="gray" onclick="resetForm()">Batal</button>
  </div>
</form>

<table>
  <thead>
    <tr><th>ID</th><th>NIM</th><th>Nama</th><th>Jurusan</th><th>Email</th><th>Aksi</th></tr>
  </thead>
  <tbody id="tbody"></tbody>
</table>

<script>
const $ = id => document.getElementById(id);

function show(text, error = false) {
  $('msg').style.color = error ? '#dc2626' : '#16a34a';
  $('msg').textContent = text;
}

async function api(url, method, body) {
  const res = await fetch(url, {
    method,
    headers: { 'Content-Type': 'application/json' },
    body: body ? JSON.stringify(body) : undefined
  });
  return res.json();
}

async function loadData() {
  const data = await api('read.php', 'GET');
  $('tbody').innerHTML = data.map(m => `
    <tr>
      <td>${m.id}</td><td>${esc(m.nim)}</td><td>${esc(m.nama)}</td>
      <td>${esc(m.jurusan)}</td><td>${esc(m.email)}</td>
      <td>
        <button class="edit" onclick='editRow(${JSON.stringify(m)})'>Edit</button>
        <button class="del" onclick="removeRow(${m.id})">Hapus</button>
      </td>
    </tr>`).join('') || '<tr><td colspan="6">Belum ada data</td></tr>';
}

function esc(s) {
  return String(s ?? '').replace(/[&<>"']/g, c =>
    ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

function editRow(m) {
  $('id').value = m.id;
  $('nim').value = m.nim;
  $('nama').value = m.nama;
  $('jurusan').value = m.jurusan;
  $('email').value = m.email;
  $('btnSave').textContent = 'Update';
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
  $('form').reset();
  $('id').value = '';
  $('btnSave').textContent = 'Tambah';
}

$('form').addEventListener('submit', async e => {
  e.preventDefault();
  const body = {
    nim: $('nim').value.trim(),
    nama: $('nama').value.trim(),
    jurusan: $('jurusan').value.trim(),
    email: $('email').value.trim()
  };
  const editing = $('id').value !== '';
  if (editing) body.id = parseInt($('id').value);

  const r = await api(editing ? 'update.php' : 'create.php', editing ? 'PUT' : 'POST', body);
  if (r.error) return show(r.error, true);
  show(r.message);
  resetForm();
  loadData();
});

async function removeRow(id) {
  if (!confirm('Hapus data ini?')) return;
  const r = await api('delete.php', 'DELETE', { id });
  if (r.error) return show(r.error, true);
  show(r.message);
  loadData();
}

loadData();
</script>
</body>
</html>
