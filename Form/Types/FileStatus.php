<?php
namespace Olc\Form\Types;

use Olc\Data\Enum;

class FileStatus extends Enum
{
    const OK = 'ok';
    const ERROR_TOO_BIG = 'error-size';
    const ERROR_PARTIAL = 'error-partial';
    const ERROR_CANNOT_WRITE = 'error-other';
    const MISSING = 'missing';

    public static function fromUpload($error)
    {
        switch ($error) {
        case UPLOAD_ERR_OK:
            return self::OK();
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return self::ERROR_TOO_BIG();
        case UPLOAD_ERR_PARTIAL:
            return self::ERROR_PARTIAL();
        case UPLOAD_ERR_NO_FILE:
            return self::MISSING();
        case UPLOAD_ERR_NO_TMP_DIR:
        case UPLOAD_ERR_CANT_WRITE:
        case UPLOAD_ERR_EXTENSION:
            return self::ERROR_CANNOT_WRITE();
        default:
            return self::MISSING();
        }
    }
}
