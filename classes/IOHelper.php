<?php

use nullupload\DB;

/**
 * Description of IOHelper
 *
 * @author juanjo
 */
class IOHelper {

    public static function download($file, $directory, $rut, $admin = false) {

        if (!$admin) {

            $stm = DB::getDB()->prepare("update files set nDownloads = nDownloads + 1, lastDownload = NOW() where id = ? limit 1");
            $stm->bindParam(1,$file['id'], PDO::PARAM_STR);
            $stm->execute();
        }

        $downloadfilename = !empty($file['origName']) ? $file['origName'] : $file['filename'] . '.' . $file['extension'];
        $downloadpath = $directory . $file['filename'];

        $filesize = filesize($downloadpath);

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $file['type']);
        header('Content-Disposition: attachment; filename="' . $downloadfilename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $filesize);
        header("Content-Range: 0-" . ($filesize - 1) . "/" . $filesize);

        $fopen = fopen($downloadpath, "r");

        ob_flush();
        flush();
        
        //Write session and do not block other request to the server
        session_write_close();
        
        //Set max time to 850 seconds = downloading 100MB in 1Mb connection
        set_time_limit(851);

        while (!feof($fopen)) {
            print fread($fopen, 2048);
            ob_flush();
            flush();
        }

        fclose($fopen);

        exit;
    }

    public static function delete($file, $path) {

        $fullpath = $path . $file['filename'];

        if (file_exists($fullpath)) {
            unlink($fullpath);
        }

        $stm = DB::getDB()->prepare("delete from files where id = ? limit 1");
        $stm->bindParam(1,$file['id'], PDO::PARAM_STR);
        $stm->execute();

        //recount size

        $totalsize = IOHelper::get_total_size($path);
        DB::setConfig(DB::$histoTotalFileSize, $totalsize);

        return true;
    }

    /**
     * 
     * @param int $length Length of the name generated
     * @return string
     */
    public static function getRandomName($length = 4) {
        return bin2hex(random_bytes($length));
    }

//    public static function db2mime($mimes, $n) {
//        $arr = array();
//        for ($i = 0; $i < count($mimes); $i++) {
//            $arr[$i + 1] = $mimes[$i];
//        }
//
//        return $arr[$n];
//    }
//
//    public static function mime2db($mimes, $mime) {
//
//        $arr = array();
//        for ($i = 0; $i < count($mimes); $i++) {
//            $arr[$mimes[$i]] = $i + 1;
//        }
//
//        return $arr[$mime];
//    }

    public static function get_total_size($system) {
        $size = 0;
        $path = scandir($system);
        unset($path[0], $path[1]);
        foreach ($path as $file) {
            if (is_dir($system . '/' . $file)) {
                $size+=get_total_size("{$system}/{$file}");
            } else {
                $size = $size + filesize("{$system}/{$file}");
            }
        }
        return $size;
    }

    public static function getStringCountdownDelete($deleteDate) {
        $rem = $deleteDate - time();
        $day = floor($rem / 86400);
        $hr = floor(($rem % 86400) / 3600);
        $min = floor(($rem % 3600) / 60);
        $sec = ($rem % 60);
        $deleteDate = "";
        if ($day)
            $deleteDate .= "$day Days ";
        if ($hr)
            $deleteDate .= "$hr Hours ";
        if ($min)
            $deleteDate .= "$min Minutes ";
        if ($sec)
            $deleteDate .= "$sec Seconds ";

        return $deleteDate;
    }

}
