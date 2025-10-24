<?php
require 'verifySession.php';

/**
 * Galería de vídeos con subida (TailwindCSS)
 * - Lista y reproduce vídeos 'VIDEO-X.(mp4|webm|ogg)' en ./videos
 * - Permite subir múltiples vídeos; se renombran a VIDEO-(max+1), VIDEO-(max+2)...
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---------------------- Configuración ---------------------- */
$videosDirFs  = __DIR__ . '/videos';  // carpeta física
$videosDirUrl = 'videos';             // ruta web relativa
$allowedExt   = ['mp4','webm','ogg'];
$maxFileSize  = 256 * 1024 * 1024;    // 256 MB
$pattern      = '/^VIDEO-(\d+)\.(' . implode('|', array_map('preg_quote', $allowedExt)) . ')$/i';

/* ---------------------- CSRF simple ---------------------- */
if (empty($_SESSION['csrf_token_v'])) {
    $_SESSION['csrf_token_v'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token_v'];

/* ------------------ Crear carpeta si no existe ------------------ */
if (!is_dir($videosDirFs)) {
    @mkdir($videosDirFs, 0755, true);
}

/* ------------------ Procesar subida (POST) ------------------ */
$messages = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf']) && hash_equals($csrf, $_POST['csrf'])) {

    if (!isset($_FILES['videos'])) {
        $messages[] = ['type' => 'error', 'text' => 'No se recibieron archivos.'];
    } else {
        $files = reformatFilesArray($_FILES['videos']); // normaliza estructura

        // Lock para evitar colisiones si suben a la vez
        $lockFile = $videosDirFs . '/upload.lock';
        $fp = fopen($lockFile, 'c');
        if ($fp) {
            flock($fp, LOCK_EX);
        }

        try {
            $currentMax = getCurrentMaxIndex($videosDirFs, $pattern);

            // Para validar MIME real
            $finfo = new finfo(FILEINFO_MIME_TYPE);

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

                // Validar que realmente es un vídeo (MIME)
                $mime = $finfo->file($tmpPath) ?: '';
                if (strpos($mime, 'video/') !== 0) {
                    $messages[] = ['type' => 'error', 'text' => "El archivo no parece ser un vídeo válido (MIME: $mime)."];
                    continue;
                }

                if (!is_uploaded_file($tmpPath)) {
                    $messages[] = ['type' => 'error', 'text' => "Seguridad: archivo no válido."];
                    continue;
                }

                // Asignar nombre: VIDEO-(currentMax+1).ext
                $currentMax++;
                $newName = "VIDEO-{$currentMax}.$ext";
                $destFs  = $videosDirFs . '/' . $newName;

                if (!move_uploaded_file($tmpPath, $destFs)) {
                    $messages[] = ['type' => 'error', 'text' => "No se pudo mover el archivo subido."];
                    $currentMax--;
                    continue;
                }

                @chmod($destFs, 0644);
                $messages[] = ['type' => 'ok', 'text' => "Subida correcta: $newName"];
            }
        } finally {
            if ($fp) {
                flock($fp, LOCK_UN);
                fclose($fp);
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messages[] = ['type' => 'error', 'text' => 'Token CSRF no válido. Recarga la página e inténtalo de nuevo.'];
}

/* ------------------ Listar vídeos existentes ------------------ */
$videos = [];
if (is_dir($videosDirFs)) {
    $dir = new DirectoryIterator($videosDirFs);
    foreach ($dir as $fileinfo) {
        if ($fileinfo->isFile()) {
            $filename = $fileinfo->getFilename();
            if (preg_match($pattern, $filename, $m)) {
                $num = (int)$m[1];
                $ext = strtolower($m[2]);
                $videos[] = [
                    'num'  => $num,
                    'name' => $filename,
                    'ext'  => $ext,
                    'url'  => $videosDirUrl . '/' . rawurlencode($filename),
                ];
            }
        }
    }
    usort($videos, fn($a, $b) => $a['num'] <=> $b['num']);
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
  <title>Videos</title>

  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

  <meta name="robots" content="noindex,nofollow">
</head>
<body class="min-h-screen bg-zinc-900 text-zinc-100 flex items-center">

  <div class="container mx-auto px-4 py-10">
    <div class="mx-auto max-w-5xl bg-zinc-800/60 backdrop-blur rounded-2xl shadow-xl p-6 md:p-8">

      <!-- Volver -->
      <div class="mb-6">
        <a href="archivos.php" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl border border-zinc-600 hover:bg-zinc-700 transition">
          <i class="fa-solid fa-arrow-left"></i><span>Volver</span>
        </a>
      </div>

      <!-- Cabecera -->
      <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Galería de Videos</h1>
        <div class="text-sm text-zinc-300">
          Carpeta: <code class="px-2 py-1 rounded bg-zinc-700/60"><?= htmlspecialchars($videosDirUrl) ?>/</code>
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
        <div class="rounded-2xl border-2 border-dashed border-zinc-600 hover:border-zinc-500 transition p-6">
          <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
            <div class="shrink-0 text-4xl">
              <i class="fa-solid fa-film"></i>
            </div>
            <div class="flex-1">
              <p class="font-semibold">Sube tus vídeos</p>
              <ul class="text-xs text-zinc-400 mt-2 list-disc pl-5">
                <li>Tamaño máximo: <?= round($maxFileSize/1024/1024) ?> MB por archivo</li>
              </ul>
              <div class="mt-4 flex items-center gap-3">
                <label class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-zinc-600 hover:bg-zinc-700 cursor-pointer">
                  <i class="fa-solid fa-upload"></i>
                  <span>Elegir archivos</span>
                  <input type="file" name="videos[]" accept="video/*" multiple class="hidden" />
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
      <?php if (!is_dir($videosDirFs)): ?>
        <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-4">
          <p class="text-red-200"><i class="fa-solid fa-triangle-exclamation mr-2"></i>La carpeta <b><?= htmlspecialchars($videosDirUrl) ?></b> no existe y no se pudo crear.</p>
        </div>
      <?php elseif (empty($videos)): ?>
        <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 p-4">
          <p class="text-amber-200"><i class="fa-regular fa-folder-open mr-2"></i>No hay vídeos aún. ¡Sube algunos!</p>
        </div>
      <?php else: ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <?php foreach ($videos as $v): ?>
            <div class="bg-zinc-800/70 border border-zinc-700 rounded-2xl overflow-hidden hover:border-zinc-500 transition">
              <div class="relative aspect-video">
                <video
                  src="<?= htmlspecialchars($v['url']) ?>"
                  class="w-full h-full object-cover"
                  controls
                  preload="metadata"
                ></video>
              </div>
              <div class="p-4 flex items-center justify-between">
                <div>
                  <p class="font-semibold">VIDEO-<?= (int)$v['num'] ?>.<?= htmlspecialchars($v['ext']) ?></p>
                  <p class="text-xs text-zinc-400"><?= htmlspecialchars($v['name']) ?></p>
                </div>
                <a
                  href="<?= htmlspecialchars($v['url']) ?>"
                  download="<?= htmlspecialchars($v['name']) ?>"
                  class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-zinc-600 hover:bg-zinc-700 transition"
                  title="Descargar"
                >
                  <i class="fa-solid fa-download"></i>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-10 text-xs text-zinc-400 text-center">
          <i class="fa-solid fa-video mr-1"></i> Mostrando <?= count($videos) ?> vídeo(s).
        </div>
      <?php endif; ?>

    </div>
  </div>

</body>
</html>
