<?php
/**
 * Upload
 * Maneja subida segura de imágenes para productos u otros tipos de archivos.
 */

class Upload
{
    private $dir;
    private $maxSize;
    private $allowedTypes;

    public function __construct(
        $dir = "../public/uploads/",
        $maxSize = 5 * 1024 * 1024, // 5 MB
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp']
    ) {
        $this->dir = $dir;
        $this->maxSize = $maxSize;
        $this->allowedTypes = $allowedTypes;

        // Crear carpeta si no existe
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    /**
     * Subir imagen
     * - Validación del archivo
     * - Renombrado seguro
     * - Guardado en carpeta correspondiente
     */
    public function subirImagen($file)
    {
        try {
            // Validar error de subida
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Error al subir archivo.");
            }

            // Validar tamaño
            if ($file['size'] > $this->maxSize) {
                throw new Exception("Archivo demasiado grande.");
            }

            // Validar MIME real del archivo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $this->allowedTypes)) {
                throw new Exception("Formato no permitido. Solo JPG/PNG/WEBP.");
            }

            // Extensión dependiendo del MIME
            $ext = match ($mime) {
                'image/jpeg' => '.jpg',
                'image/png' => '.png',
                'image/webp' => '.webp',
                default => throw new Exception("Tipo MIME no válido.")
            };

            // Crear nombre único
            $nombre = uniqid("img_", true) . $ext;
            $destino = $this->dir . $nombre;

            // Mover archivo
            if (!move_uploaded_file($file['tmp_name'], $destino)) {
                throw new Exception("No se pudo guardar el archivo.");
            }

            // Retornar nombre de archivo para guardar en BD
            return $nombre;

        } catch (Exception $e) {
            error_log("Upload error: " . $e->getMessage());
            return false;
        }
    }
}
