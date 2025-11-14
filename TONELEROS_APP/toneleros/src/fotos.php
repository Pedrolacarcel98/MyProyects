<?php
require 'verifySession.php';

/**
 * Galería con subida y borrado de imágenes (TailwindCSS)
 * - Lista archivos 'FOTO-X.(jpg|jpeg|png|webp|gif)' en ./fotos
 * - Permite subir múltiples imágenes -> renombradas a FOTO-(max+1)...
 * - Permite borrar imágenes con validación y confirmación
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---------------------- Configuración ---------------------- */
$photosDirFs  = __DIR__ . '/fotos';   // carpeta física
$photosDirUrl = 'fotos';              // ruta web relativa
$allowedExt   = ['jpg','jpeg','png','webp','gif'];
$maxFileSize  = 10 * 1024 * 1024;     // 10 MB/archivo
$pattern      = '/^FOTO-(\d+)\.(' . implode('|', array_map('preg_quote', $allowedExt)) . ')$/i';

/* Papelera (mover en lugar de borrar definitivo) */
$useTrash     = true;
$trashDirFs   = $photosDirFs . '/.trash';

/* ---------------------- CSRF simple ---------------------- */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

/* ------------------ Crear carpetas si no existen ------------------ */
if (!is_dir($photosDirFs)) {
    @mkdir($photosDirFs, 0755, true);
}
if ($useTrash && !is_dir($trashDirFs)) {
    @mkdir($trashDirFs, 0755, true);
}

/* ------------------ Procesar acciones (POST) ------------------ */
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf']) || !hash_equals($csrf, $_POST['csrf'])) {
        $messages[] = ['type' => 'error', 'text' => 'Token CSRF no válido. Recarga la página e inténtalo de nuevo.'];
    } else {
        $action = $_POST['action'] ?? '';
        if ($action === 'upload') {
            /* -------- Subida -------- */
            if (!isset($_FILES['fotos'])) {
                $messages[] = ['type' => 'error', 'text' => 'No se recibieron archivos.'];
            } else {
                $files = reformatFilesArray($_FILES['fotos']); // normaliza estructura
                // Lock para evitar colisiones si suben a la vez
                $lockFile = $photosDirFs . '/upload.lock';
                $fp = fopen($lockFile, 'c');
                if ($fp) { flock($fp, LOCK_EX); }

                try {
                    $currentMax = getCurrentMaxIndex($photosDirFs, $pattern);

                    foreach ($files as $file) {
                        if ($file['error'] !== UPLOAD_ERR_OK) {
                            $messages[] = ['type' => 'error', 'text' => "Error en subida: " . uploadErrorToText($file['error'])];
                            continue;
                        }
                        if ($file['size'] > $maxFileSize) {
                            $messages[] = ['type' => 'error', 'text' => "Archivo demasiado grande (máx " . round($maxFileSize/1024/1024) . " MB)."];
                            continue;
                        }

                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        if (!in_array($ext, $allowedExt, true)) {
                            $messages[] = ['type' => 'error', 'text' => "Extensión no permitida: .$ext"];
                            continue;
                        }

                        $tmpPath = $file['tmp_name'];
                        $imgInfo = @getimagesize($tmpPath);
                        if ($imgInfo === false) {
                            $messages[] = ['type' => 'error', 'text' => "El archivo no parece ser una imagen válida."];
                            continue;
                        }

                        if (!is_uploaded_file($tmpPath)) {
                            $messages[] = ['type' => 'error', 'text' => "Seguridad: archivo no válido."];
                            continue;
                        }

                        // Asignar nombre: FOTO-(currentMax+1).ext (normalizamos jpeg->jpg)
                        $currentMax++;
                        $newExt  = ($ext === 'jpeg') ? 'jpg' : $ext;
                        $newName = "FOTO-{$currentMax}.{$newExt}";
                        $destFs  = $photosDirFs . '/' . $newName;

                        if (!move_uploaded_file($tmpPath, $destFs)) {
                            $messages[] = ['type' => 'error', 'text' => "No se pudo mover el archivo subido."];
                            $currentMax--;
                            continue;
                        }

                        @chmod($destFs, 0644);
                        $messages[] = ['type' => 'ok', 'text' => "Subida correcta: $newName"];
                    }
                } finally {
                    if ($fp) { flock($fp, LOCK_UN); fclose($fp); }
                }
            }
        } elseif ($action === 'delete') {
            /* -------- Borrado -------- */
            $filename = basename($_POST['filename'] ?? '');
            if (!preg_match($pattern, $filename)) {
                $messages[] = ['type' => 'error', 'text' => 'Nombre de archivo no válido.'];
            } else {
                $fullPath = $photosDirFs . '/' . $filename;
                if (!is_file($fullPath)) {
                    $messages[] = ['type' => 'error', 'text' => 'El archivo no existe.'];
                } else {
                    // Evitar path traversal
                    $real = realpath($fullPath);
                    if ($real === false || strpos($real, realpath($photosDirFs)) !== 0) {
                        $messages[] = ['type' => 'error', 'text' => 'Ruta de archivo no permitida.'];
                    } else {
                        if ($useTrash) {
                            // Mover a papelera con timestamp para no sobrescribir
                            $ts   = date('Ymd_His');
                            $dest = $trashDirFs . '/' . $ts . '_' . $filename;
                            if (@rename($fullPath, $dest)) {
                                $messages[] = ['type' => 'ok', 'text' => "Movida a papelera: $filename"];
                            } else {
                                $messages[] = ['type' => 'error', 'text' => 'No se pudo mover a la papelera.'];
                            }
                        } else {
                            if (@unlink($fullPath)) {
                                $messages[] = ['type' => 'ok', 'text' => "Archivo eliminado: $filename"];
                            } else {
                                $messages[] = ['type' => 'error', 'text' => 'No se pudo eliminar el archivo.'];
                            }
                        }
                    }
                }
            }
        }
    }
}

/* ------------------ Listar fotos existentes ------------------ */
$photos = [];
if (is_dir($photosDirFs)) {
    $dir = new DirectoryIterator($photosDirFs);
    foreach ($dir as $fileinfo) {
        if ($fileinfo->isFile()) {
            $filename = $fileinfo->getFilename();
            if (preg_match($pattern, $filename, $m)) {
                $num = (int)$m[1];
                $ext = strtolower($m[2]);
                $photos[] = [
                    'num'  => $num,
                    'name' => $filename,
                    'ext'  => $ext,
                    'url'  => $photosDirUrl . '/' . rawurlencode($filename),
                ];
            }
        }
    }
    usort($photos, fn($a, $b) => $a['num'] <=> $b['num']);
}

/* ------------------ Helpers ------------------ */
function reformatFilesArray(array $files): array {
    $result = [];
    if (is_array($files['name'])) {
        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            $result[] = [
                'name'     => $files['name'][$i],
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];
        }
    } else {
        $result[] = $files;
    }
    return $result;
}
function getCurrentMaxIndex(string $dir, string $pattern): int {
    $max = 0;
    if (!is_dir($dir)) return 0;
    $d = new DirectoryIterator($dir);
    foreach ($d as $f) {
        if ($f->isFile()) {
            if (preg_match($pattern, $f->getFilename(), $m)) {
                $n = (int)$m[1];
                if ($n > $max) $max = $n;
            }
        }
    }
    return $max;
}
function uploadErrorToText(int $code): string {
    return match ($code) {
        UPLOAD_ERR_INI_SIZE => 'El archivo excede upload_max_filesize.',
        UPLOAD_ERR_FORM_SIZE => 'El archivo excede MAX_FILE_SIZE del formulario.',
        UPLOAD_ERR_PARTIAL => 'Archivo subido parcialmente.',
        UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo.',
        UPLOAD_ERR_NO_TMP_DIR => 'Falta carpeta temporal.',
        UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir en disco.',
        UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la subida.',
        default => 'Error desconocido.'
    };
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Galería de Fotos</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <meta name="robots" content="noindex,nofollow">
</head>
<body class="min-h-screen bg-zinc-900 text-zinc-100 flex items-center">
  <div class="container mx-auto px-4 py-10">
    <div class="mx-auto max-w-6xl bg-zinc-800/60 backdrop-blur rounded-2xl shadow-xl p-6 md:p-8">

      <!-- Volver -->
      <div class="mb-6">
        <button onclick="history.back()" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl border border-zinc-600 hover:bg-zinc-700 transition">
          <i class="fa-solid fa-arrow-left"></i><span>Volver</span>
        </button>
      </div>

      <!-- Cabecera -->
      <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Galería de Fotos</h1>
        <div class="text-sm text-zinc-300">
          Carpeta: <code class="px-2 py-1 rounded bg-zinc-700/60"><?= htmlspecialchars($photosDirUrl) ?>/</code>
        </div>
      </div>

      <!-- Mensajes -->
      <?php if (!empty($messages)): ?>
        <div class="space-y-3 mb-6">
          <?php foreach ($messages as $msg): ?>
            <?php if ($msg['type'] === 'ok'): ?>
              <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3 text-emerald-200">
                <i class="fa-solid fa-check mr-2"></i><?= htmlspecialchars($msg['text']) ?>
              </div>
            <?php else: ?>
              <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-3 text-red-200">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i><?= htmlspecialchars($msg['text']) ?>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Subida -->
      <form method="post" enctype="multipart/form-data" class="mb-10">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="action" value="upload">
        <div class="rounded-2xl border-2 border-dashed border-zinc-600 hover:border-zinc-500 transition p-6">
          <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
            <div class="shrink-0 text-4xl">
              <i class="fa-regular fa-images"></i>
            </div>
            <div class="flex-1">
              <p class="font-semibold">Subir</p>
              <ul class="text-xs text-zinc-400 mt-2 list-disc pl-5">
                <li>Extensiones permitidas: <?= implode(', ', $allowedExt) ?></li>
                <li>Elegir los archivos y luego darle a subir</li>
              </ul>
              <div class="mt-4 flex items-center gap-3">
                <label class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-zinc-600 hover:bg-zinc-700 cursor-pointer">
                  <i class="fa-solid fa-upload"></i>
                  <span>Elegir archivos</span>
                  <input type="file" name="fotos[]" accept="image/*" multiple class="hidden" />
                </label>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-zinc-100 text-zinc-900 hover:bg-white font-semibold">
                  <i class="fa-solid fa-cloud-arrow-up"></i> Subir
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>

      <!-- Galería -->
      <?php if (!is_dir($photosDirFs)): ?>
        <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-4">
          <p class="text-red-200"><i class="fa-solid fa-triangle-exclamation mr-2"></i>La carpeta <b><?= htmlspecialchars($photosDirUrl) ?></b> no existe y no se pudo crear.</p>
        </div>
      <?php elseif (empty($photos)): ?>
        <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 p-4">
          <p class="text-amber-200"><i class="fa-regular fa-folder-open mr-2"></i>No hay imágenes aún. ¡Sube algunas!</p>
        </div>
      <?php else: ?>
        <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          <?php foreach ($photos as $p): ?>
            <li class="group rounded-2xl overflow-hidden bg-zinc-800/70 border border-zinc-700 hover:border-zinc-500 transition">
              <a href="<?= htmlspecialchars($p['url']) ?>" target="_blank" rel="noopener" class="block">
                <div class="aspect-[4/3] overflow-hidden">
                  <img
                    src="<?= htmlspecialchars($p['url']) ?>"
                    alt="FOTO-<?= (int)$p['num'] ?>"
                    loading="lazy"
                    class="w-full h-full object-cover transition duration-300 group-hover:scale-105"
                  />
                </div>
              </a>
              <div class="p-4 flex items-center gap-2">
                <div class="flex-1 min-w-0">
                  <p class="font-semibold truncate">FOTO-<?= (int)$p['num'] ?>.<?= htmlspecialchars($p['ext']) ?></p>
                  <p class="text-xs text-zinc-400 truncate"><?= htmlspecialchars($p['name']) ?></p>
                </div>

                <!-- Compartir -->
                <button
                  type="button"
                  onclick="sharePhoto('<?= htmlspecialchars($p['url']) ?>')"
                  class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-zinc-600 hover:bg-zinc-700 transition"
                  title="Compartir"
                ><i class="fa-solid fa-share-nodes"></i></button>

                <!-- Descargar -->
                <a
                  href="<?= htmlspecialchars($p['url']) ?>"
                  download="<?= htmlspecialchars($p['name']) ?>"
                  class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-zinc-600 hover:bg-zinc-700 transition"
                  title="Descargar"
                ><i class="fa-solid fa-download"></i></a>

                <!-- Borrar -->
                <form method="post" onsubmit="return confirmDelete('<?= htmlspecialchars($p['name']) ?>');">
                  <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="filename" value="<?= htmlspecialchars($p['name']) ?>">
                  <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-red-600 text-red-200 hover:bg-red-700/20 transition"
                    title="Borrar"
                  ><i class="fa-solid fa-trash"></i></button>
                </form>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="mt-10 text-xs text-zinc-400 text-center">
          <i class="fa-regular fa-image mr-1"></i> Mostrando <?= count($photos) ?> archivo(s).
        </div>
      <?php endif; ?>

    </div>
  </div>

  <script>
    function confirmDelete(name) {
      return confirm('¿Seguro que quieres eliminar "' + name + '"?\n' +
                     <?= $useTrash ? json_encode('Se moverá a la papelera.') : json_encode('Esta acción no se puede deshacer.') ?>);
    }
    async function sharePhoto(url) {
      const fullUrl = `${window.location.origin}/${url}`;
      if (navigator.share) {
        try { await navigator.share({ title: '📷 Foto', url: fullUrl }); } catch(e) {}
      } else {
        try { await navigator.clipboard.writeText(fullUrl); alert('📋 Enlace copiado:\n' + fullUrl); }
        catch(e) { prompt('Copia este enlace:', fullUrl); }
      }
    }
  </script>
</body>
</html>
