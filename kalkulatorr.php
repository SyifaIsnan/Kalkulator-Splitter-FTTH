<?php
session_start();

/* =========================
   DATA SPLITTER
========================= */
$dataSplitter = [
    ["ratioSplitter" => "1:2", "nilaiRedaman" => -4],
    ["ratioSplitter" => "1:4", "nilaiRedaman" => -8],
    ["ratioSplitter" => "1:8", "nilaiRedaman" => -10],
    ["ratioSplitter" => "1:16", "nilaiRedaman" => -14],
];

$batasMaksimalRedaman = -23;


/* =========================
   RESET
========================= */
if (isset($_POST['reset'])) {
    session_destroy();
    header("Location: kalkulatorr.php");
    exit;
}


/* =========================
   HITUNG AWAL
========================= */
if (isset($_POST['hitung'])) {

    $inputSfpAwal = floatval($_POST['sfp']);

    if ($inputSfpAwal >= 1 && $inputSfpAwal <= 10) {
        $_SESSION['riwayatRedaman'] = [$inputSfpAwal];
        $_SESSION['indexRedamanAktif'] = 0;
    }

    header("Location: kalkulatorr.php");
    exit;
}


/* =========================
   TAMBAH REDAMAN
========================= */
if (isset($_POST['turun'])) {

    $nilaiRedamanBaru = floatval($_POST['nilai']);

    $_SESSION['riwayatRedaman'][] = $nilaiRedamanBaru;
    $_SESSION['indexRedamanAktif'] =
        count($_SESSION['riwayatRedaman']) - 1; // supaya indexnya sesuai 

    header("Location: kalkulatorr.php");
    exit;
}


/* =========================
   PINDAH REDAMAN + HAPUS SETELAHNYA
========================= */
if (isset($_POST['pilih'])) {

    $indexDipilih = intval($_POST['pilih']);

    if (isset($_SESSION['riwayatRedaman'][$indexDipilih])) {

        $_SESSION['riwayatRedaman'] =
            array_slice($_SESSION['riwayatRedaman'], 0, $indexDipilih + 1);

        $_SESSION['indexRedamanAktif'] = $indexDipilih;
    }

    header("Location: kalkulatorr.php");
    exit;
}


/* =========================
   AMBIL DATA SESSION
========================= */
$riwayatRedaman     = $_SESSION['riwayatRedaman'] ?? [];
$indexRedamanAktif  = $_SESSION['indexRedamanAktif'] ?? 0;
$nilaiRedamanAktif  = $riwayatRedaman[$indexRedamanAktif] ?? null;

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Kalkulator Splitter FTTH</title>

<style>* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.box {
    background: #ffffff;
    width: 800px;
    padding: 40px;
    border-radius: 24px;
    box-shadow: 0 30px 60px rgba(0,0,0,0.08);
    animation: fadeIn 0.4s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

h2 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 600;
    color: #1e3a8a;
}

/* INPUT */
label {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

input {
    width: 100%;
    padding: 12px 14px;
    margin-top: 8px;
    border-radius: 12px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    transition: all 0.2s ease;
}

input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59,130,246,0.15);
}

/* BUTTON BASE */
button {
    padding: 12px;
    border-radius: 14px;
    border: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
}

/* PRIMARY BUTTON */
button[name="hitung"],
button[name="turun"] {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    box-shadow: 0 8px 20px rgba(59,130,246,0.25);
}

button[name="hitung"]:hover,
button[name="turun"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(59,130,246,0.35);
}

/* RESET BUTTON */
.reset-btn {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 8px 20px rgba(239,68,68,0.25);
}

.reset-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(239,68,68,0.35);
}

/* BUTTON ROW */
.button-row {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.button-row form {
    flex: 1;
}

.button-row button {
    width: 100%;
}

/* HISTORY */
.history {
    margin-top: 25px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
}

.history ul {
    list-style: none;
    padding: 0;
    margin: 10px 0 0 0;
}

.history li {
    margin-bottom: 8px;
}

.history button {
    background: #e0f2fe;
    color: #1e3a8a;
    border: 1px solid #bfdbfe;
    padding: 6px 12px;
    border-radius: 10px;
}

.history button:hover {
    background: #bfdbfe;
}

/* TABLE */
table {
    width: 100%;
    margin-top: 30px;
    border-collapse: collapse;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0,0,0,0.05);
}

th {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: white;
    padding: 14px;
    font-weight: 600;
}

td {
    padding: 14px;
    text-align: center;
    border-bottom: 1px solid #f1f5f9;
}

tr:nth-child(even) {
    background: #f9fafb;
}

tr:hover {
    background: #eef2ff;
    transition: 0.2s ease;
}

/* WARNING */
.danger {
    color: #dc2626;
    font-weight: 700;
}
 


</style>
</head>

<body>

<div class="box">

<h2>Kalkulator Splitter FTTH</h2>

<form method="post">
    <label>Input SFP (1 - 10)</label>
    <input type="number" name="sfp" min="1" max="10" step="0.1" required>
    <div class="button-row">
    <button name="hitung">Hitung</button>
</form>

<form method="post">
    <button 
        name="reset" 
        class="reset-btn"
        onclick="return confirm('Anda yakin mau reset semua redaman?')">
        Reset
    </button>
</form>
    </div>
<?php if (!empty($riwayatRedaman)): ?>
<div class="history">
    <strong>Riwayat Redaman:</strong>
    <ul>
        <?php foreach ($riwayatRedaman as $index => $nilai): ?>
        <li>
            <form method="post" style="display:inline;">
                <input type="hidden" name="pilih" value="<?= $index ?>">
                <button
                    onclick="return confirm('Anda yakin ingin kembali ke redaman <?= $index ?> : <?= $nilai ?> dB ?')"
                    style="width:auto; padding:5px 10px;">
                    Redaman <?= $index ?> : <?= $nilai ?> dB
                </button>
            </form>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
<?php if ($nilaiRedamanAktif !== null): ?>
<table>
<tr>
    <th>SFP</th>
    <th>Splitter</th>
    <th>Redaman</th>
    <th>Aksi</th>
</tr>

<?php foreach ($dataSplitter as $dataSatuSplitter):

    $hasilRedamanBaru =
        round($nilaiRedamanAktif + $dataSatuSplitter['nilaiRedaman'], 2);

    $statusMelebihiBatas =
        $hasilRedamanBaru <= $batasMaksimalRedaman;
?>
<tr> 
    <td><?= $nilaiRedamanAktif ?></td>
    <td><?= $dataSatuSplitter['ratioSplitter'] ?></td>
    <td class="<?= $statusMelebihiBatas ? 'danger' : '' ?>">
        <?= $hasilRedamanBaru ?> dB
        <?= $statusMelebihiBatas ? "⚠ Melebihi batas" : "" ?>
    </td>
    <td>
        <form method="post">
            <input type="hidden" name="nilai" value="<?= $hasilRedamanBaru ?>">
            <button name="turun" style="width:auto;">
                Turunkan
            </button>
        </form>
    </td>
</tr>
<?php endforeach; ?>

</table>
<?php endif; ?>

</div>

</body>
</html>